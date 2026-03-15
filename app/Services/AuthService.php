<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthService
{
    /**
     * Maksimum percobaan login sebelum akun terkunci.
     */
    private const MAX_ATTEMPTS = 5;

    /**
     * Durasi lockout dalam menit.
     */
    private const LOCKOUT_MINUTES = 15;

    /**
     * Attempt login dengan proteksi brute force.
     *
     * @return array{success: bool, message: string}
     */
    public function attemptLogin(array $credentials, bool $remember, Request $request): array
    {
        $user = User::where('email', $credentials['email'])->first();

        // Cek apakah user ada
        if (! $user) {
            return [
                'success' => false,
                'message' => 'Email atau password salah.',
            ];
        }

        // Cek apakah akun terkunci
        if ($this->isAccountLocked($user)) {
            $minutesLeft = (int) now()->diffInMinutes($user->locked_until, false);

            return [
                'success' => false,
                'message' => "Akun terkunci. Coba lagi dalam {$minutesLeft} menit.",
            ];
        }

        // Attempt login
        if (! Auth::attempt($credentials, $remember)) {
            $this->incrementLoginAttempts($user);

            $attemptsLeft = self::MAX_ATTEMPTS - $user->fresh()->login_attempts;

            if ($attemptsLeft <= 0) {
                return [
                    'success' => false,
                    'message' => 'Akun terkunci selama ' . self::LOCKOUT_MINUTES . ' menit karena terlalu banyak percobaan gagal.',
                ];
            }

            return [
                'success' => false,
                'message' => "Email atau password salah. Sisa percobaan: {$attemptsLeft}.",
            ];
        }

        // Login berhasil — reset counter & catat info
        $this->resetLoginAttempts($user);
        $this->recordLoginInfo($user, $request);

        // Regenerate session untuk mencegah session fixation
        $request->session()->regenerate();

        return [
            'success' => true,
            'message' => 'Login berhasil.',
        ];
    }

    /**
     * Logout user dan invalidate session.
     */
    public function logout(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * Cek apakah akun sedang terkunci.
     */
    public function isAccountLocked(User $user): bool
    {
        if (! $user->locked_until) {
            return false;
        }

        if (now()->gte($user->locked_until)) {
            // Lockout sudah expired, reset
            $user->update([
                'login_attempts' => 0,
                'locked_until'   => null,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Tambah counter percobaan login gagal.
     * Jika sudah mencapai batas, kunci akun.
     */
    private function incrementLoginAttempts(User $user): void
    {
        $attempts = $user->login_attempts + 1;

        $data = ['login_attempts' => $attempts];

        if ($attempts >= self::MAX_ATTEMPTS) {
            $data['locked_until'] = now()->addMinutes(self::LOCKOUT_MINUTES);
        }

        $user->update($data);
    }

    /**
     * Reset counter percobaan login setelah berhasil.
     */
    private function resetLoginAttempts(User $user): void
    {
        $user->update([
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);
    }

    /**
     * Catat informasi login terakhir.
     */
    private function recordLoginInfo(User $user, Request $request): void
    {
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);
    }
}

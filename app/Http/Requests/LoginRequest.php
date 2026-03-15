<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email harus diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
        ];
    }

    /**
     * Pastikan request tidak sedang di-rate-limit.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    /**
     * Hit rate limiter setiap kali login gagal.
     */
    public function hitRateLimiter(): void
    {
        RateLimiter::hit($this->throttleKey(), 60);
    }

    /**
     * Clear rate limiter setelah login berhasil.
     */
    public function clearRateLimiter(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Generate throttle key unik berdasarkan email + IP.
     */
    private function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email')) . '|' . $this->ip()
        );
    }
}

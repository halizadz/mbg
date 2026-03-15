<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        return response()->view('login')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    /**
     * Proses login.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // Rate limiting per IP+email
        $request->ensureIsNotRateLimited();

        $result = $this->authService->attemptLogin(
            $request->only('email', 'password'),
            $request->boolean('remember'),
            $request
        );

        if (! $result['success']) {
            $request->hitRateLimiter();

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => $result['message']]);
        }

        $request->clearRateLimiter();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout user.
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout(request());

        return redirect()->route('login')
            ->with('status', 'Anda telah logout.');
    }
}

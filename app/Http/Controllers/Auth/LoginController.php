<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login (Layar 2).
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isVendor()
                ? redirect()->route('vendor.dashboard')
                : redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses otentikasi login dengan throttle rate limiter dan pesan error umum anti-enumerasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        // 1. Throttle: batasi setelah 5 kali kegagalan login
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal. Akses dibatasi sementara, silakan coba lagi dalam {$seconds} detik.",
            ])->withInput($request->only('email'));
        }

        // 2. Percobaan otentikasi login
        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($throttleKey, 60);

            // Pesan gagal selalu umum (anti-enumerasi email)
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput($request->only('email'));
        }

        // 3. Login sukses: bersihkan throttle dan regenerasi sesi
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $user = Auth::user();

        // 4. Redirect sesuai peran
        if ($user->isVendor()) {
            return redirect()->intended(route('vendor.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout: akhiri sesi secara penuh dan regenerasi token CSRF.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari akun.');
    }
}


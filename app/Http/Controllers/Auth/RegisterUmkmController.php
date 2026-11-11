<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterUmkmController extends Controller
{
    /**
     * Tampilkan formulir Registrasi UMKM (Layar 3).
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register-umkm');
    }

    /**
     * Proses pendaftaran: 1 organisasi + 1 admin UMKM dalam satu transaksi, lalu langsung login.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Validasi Data UMKM
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'unique:organizations,slug',
            ],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'opening_hours' => ['nullable', 'string', 'max:100'],

            // Validasi Akun Admin UMKM
            'admin_name' => ['required', 'string', 'max:100'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'not_in:123456,12345678,password,qwerty,admin123,123456789',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'name.required' => 'Nama UMKM wajib diisi.',
            'slug.required' => 'Slug URL UMKM wajib diisi.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung (-). Contoh: peyeum-kuningan.',
            'slug.unique' => 'Slug UMKM ":input" sudah digunakan. Silakan gunakan slug lain.',
            'category.required' => 'Kategori usaha wajib dipilih.',
            'admin_name.required' => 'Nama admin pengelola wajib diisi.',
            'admin_email.required' => 'Email admin wajib diisi.',
            'admin_email.email' => 'Format email admin tidak valid.',
            'admin_email.unique' => 'Email admin ":input" sudah terdaftar di sistem.',
            'password.required' => 'Password admin wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.not_in' => 'Password terlalu lemah dan mudah ditebak.',
        ]);

        // Simpan 1 baris organizations + 1 baris users secara atomik dalam satu submit
        $user = DB::transaction(function () use ($validated) {
            $organization = Organization::create([
                'name' => trim($validated['name']),
                'slug' => strtolower(trim($validated['slug'])),
                'category' => $validated['category'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'opening_hours' => $validated['opening_hours'] ?? null,
                'is_active' => true,
            ]);

            return User::create([
                'name' => trim($validated['admin_name']),
                'email' => strtolower(trim($validated['admin_email'])),
                'password' => Hash::make($validated['password']),
                'role' => 'umkm_admin',
                'organization_id' => $organization->id,
            ]);
        });

        // Langsung login sebagai admin UMKM tersebut
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Selamat datang! Pendaftaran UMKM "' . $user->organization->name . '" berhasil dan Anda telah login sebagai admin.');
    }
}


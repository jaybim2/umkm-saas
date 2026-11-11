<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UmkmProfileController extends Controller
{
    /**
     * Tampilkan formulir Kelola Profil UMKM milik admin yang login (Layar 7).
     */
    public function edit(): View
    {
        $user = Auth::user();
        $organization = $user->organization;

        if (!$organization) {
            abort(404, 'Data UMKM tidak ditemukan.');
        }

        return view('dashboard.profile', [
            'user' => $user,
            'organization' => $organization,
        ]);
    }

    /**
     * Proteksi IDOR: Jika ada yang mencoba mengakses /dashboard/profil/{id} dengan ID UMKM lain.
     */
    public function editById(int $id): View
    {
        $user = Auth::user();

        if ((int)$id !== (int)$user->organization_id) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk mengelola profil UMKM lain.');
        }

        return $this->edit();
    }

    /**
     * Simpan perubahan data profil UMKM.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $organization = $user->organization;

        if (!$organization) {
            abort(404, 'Data UMKM tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('organizations', 'slug')->ignore($organization->id),
            ],
            'category' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'], // Wajib diisi sesuai langkah T10
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:100'],
            'cover_url' => ['nullable', 'url', 'max:500'],
        ], [
            'name.required' => 'Nama UMKM wajib diisi.',
            'slug.required' => 'Slug URL publik wajib diisi.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung (-).',
            'slug.unique' => 'Slug URL ":input" sudah digunakan oleh UMKM lain. Silakan pilih slug lain.',
            'category.required' => 'Bidang usaha wajib dipilih.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'email.required' => 'Email kontak admin wajib diisi.',
            'email.email' => 'Format email kontak tidak valid.',
            'email.unique' => 'Email kontak ":input" sudah digunakan oleh akun lain.',
            'cover_url.url' => 'Format URL foto sampul tidak valid (gunakan https://...).',
        ]);

        // Simpan pembaruan ke tabel organizations
        $organization->update([
            'name' => trim($validated['name']),
            'slug' => strtolower(trim($validated['slug'])),
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => trim($validated['phone']),
            'opening_hours' => $validated['opening_hours'] ?? null,
        ]);

        // Sinkronisasi email kontak ke akun user admin jika berubah
        if ($user->email !== strtolower(trim($validated['email']))) {
            $user->update([
                'email' => strtolower(trim($validated['email'])),
            ]);
        }

        return redirect()->route('umkm.profile.edit')->with('success', 'Profil UMKM "' . $organization->name . '" berhasil diperbarui!');
    }
}


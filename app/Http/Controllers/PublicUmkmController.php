<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicUmkmController extends Controller
{
    /**
     * Tampilkan Halaman Publik Profil UMKM (Layar 11).
     * Dapat diakses oleh publik tanpa login.
     */
    public function show(string $slug): View
    {
        // 1. Cari organisasi berdasarkan slug (tanpa batasan scope tenant)
        $organization = Organization::withoutTenant()
            ->where('slug', $slug)
            ->firstOrFail();

        // 2. Jika UMKM nonaktif, produk TIDAK tampil sama sekali
        if (!$organization->is_active) {
            return view('public.umkm', [
                'organization' => $organization,
                'products' => collect(),
                'categories' => collect(),
                'adminEmail' => null,
            ]);
        }

        // 3. Hanya ambil produk dengan status 'Tampil' (produk 'Sembunyi' tidak pernah dimuat)
        $products = Product::withoutTenant()
            ->where('organization_id', $organization->id)
            ->where('status', 'Tampil')
            ->orderBy('id', 'asc')
            ->get();

        // Ambil daftar kategori produk unik untuk chip filter
        $categories = $products->pluck('category')->unique()->filter()->values();

        // Ambil email admin pengelola untuk kontak
        $adminEmail = $organization->users()->first()?->email;

        return view('public.umkm', [
            'organization' => $organization,
            'products' => $products,
            'categories' => $categories,
            'adminEmail' => $adminEmail,
        ]);
    }

    /**
     * Simpan pertanyaan baru dari pengunjung halaman publik (Layar 11 & Layar 9).
     */
    public function storeInquiry(Request $request, string $slug): RedirectResponse
    {
        // 1. Cari UMKM berdasarkan slug
        $organization = Organization::withoutTenant()
            ->where('slug', $slug)
            ->firstOrFail();

        // 2. Jika UMKM nonaktif, tolak kirim pertanyaan
        if (!$organization->is_active) {
            return redirect()->to(url('/u/' . $slug))
                ->with('inquiry_error', 'Mohon maaf, halaman UMKM ini sedang tidak aktif dan tidak menerima pesan.');
        }

        // 3. Validasi input pengunjung (nama wajib, kontak opsional, pesan wajib)
        $validated = $request->validate([
            'visitor_name' => ['required', 'string', 'max:150'],
            'visitor_contact' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'visitor_name.required' => 'Nama lengkap Anda wajib diisi.',
            'visitor_name.max' => 'Nama lengkap maksimal 150 karakter.',
            'visitor_contact.max' => 'Kontak pengirim maksimal 100 karakter.',
            'message.required' => 'Pesan atau pertanyaan Anda wajib diisi.',
            'message.max' => 'Isi pesan pertanyaan maksimal 2000 karakter.',
        ]);

        // 4. Simpan pertanyaan baru ke tenant UMKM ini dengan status default 'Baru'
        Inquiry::create([
            'organization_id' => $organization->id,
            'visitor_name' => trim($validated['visitor_name']),
            'visitor_contact' => !empty($validated['visitor_contact']) ? trim($validated['visitor_contact']) : null,
            'message' => trim($validated['message']),
            'status' => 'Baru',
        ]);

        return redirect()->to(url('/u/' . $slug . '#pertanyaan'))
            ->with('inquiry_success', 'Terima kasih, pertanyaan Anda telah berhasil dikirim ke pengelola ' . $organization->name . '! Kami akan segera menindaklanjutinya.');
    }
}

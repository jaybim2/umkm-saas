<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VendorDashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Vendor Platform (Layar 4 Figcraft).
     * Angka agregat seluruh ekosistem tanpa menampilkan isi pesan UMKM.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Metrik Agregat Ekosistem Platform
        $totalOrganizations = Organization::count();
        $totalProducts = Product::aggregateCount();
        $totalInquiries = Inquiry::aggregateCount(); // Angka saja, bukan isi pesan

        // 5 UMKM Pendaftar Terbaru (Layar 4 Figcraft)
        $organizations = Organization::latest()->take(5)->get([
            'id',
            'name',
            'slug',
            'category',
            'is_active',
            'created_at',
        ]);

        return view('vendor.dashboard', [
            'user' => $user,
            'stats' => [
                'total_organizations' => $totalOrganizations,
                'total_products' => $totalProducts,
                'total_inquiries' => $totalInquiries,
            ],
            'organizations' => $organizations,
        ]);
    }

    /**
     * Tampilkan Layar 5 Figcraft: Kelola Seluruh UMKM Terdaftar.
     * Dilengkapi filter nama/slug, filter status (Semua, Aktif, Nonaktif),
     * toggle status cepat, serta formulir tambah / ubah UMKM.
     */
    public function umkmIndex(Request $request): View
    {
        $user = Auth::user();
        $query = Organization::query();

        // Filter Pencarian Nama / Slug
        if ($request->filled('q')) {
            $search = '%' . trim($request->q) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('slug', 'like', $search);
            });
        }

        // Filter Status Aktif / Nonaktif
        if ($request->filled('status')) {
            if ($request->status === 'Aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'Nonaktif') {
                $query->where('is_active', false);
            }
        }

        $organizations = $query->latest('id')->get();

        // UMKM terpilih jika sedang dalam mode edit (?edit={id})
        $selectedUmkm = null;
        if ($request->filled('edit')) {
            $selectedUmkm = Organization::find($request->edit);
        }

        return view('vendor.umkm', [
            'user' => $user,
            'organizations' => $organizations,
            'selectedUmkm' => $selectedUmkm,
            'filters' => [
                'q' => $request->q,
                'status' => $request->status,
            ],
            'counts' => [
                'all' => Organization::count(),
                'active' => Organization::where('is_active', true)->count(),
                'inactive' => Organization::where('is_active', false)->count(),
            ],
        ]);
    }

    /**
     * Simpan UMKM Baru dari Form Vendor (Layar 5).
     */
    public function storeUmkm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:organizations,slug'],
            'category' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'status' => ['required', 'in:Aktif,Nonaktif'],
        ], [
            'name.required' => 'Nama UMKM wajib diisi.',
            'slug.required' => 'Slug website wajib diisi.',
            'slug.regex' => 'Format slug hanya boleh huruf kecil, angka, dan tanda hubung (-).',
            'slug.unique' => 'Slug website sudah digunakan oleh UMKM lain.',
            'category.required' => 'Bidang usaha wajib dipilih.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
        ]);

        Organization::create([
            'name' => trim($validated['name']),
            'slug' => strtolower(trim($validated['slug'])),
            'category' => $validated['category'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'is_active' => $validated['status'] === 'Aktif',
        ]);

        return redirect()->route('vendor.umkm.index')->with('success', 'UMKM "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    /**
     * Perbarui data UMKM dari Form Vendor (Layar 5).
     */
    public function updateUmkm(Request $request, Organization $organization): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:organizations,slug,' . $organization->id],
            'category' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'status' => ['required', 'in:Aktif,Nonaktif'],
        ], [
            'name.required' => 'Nama UMKM wajib diisi.',
            'slug.required' => 'Slug website wajib diisi.',
            'slug.regex' => 'Format slug hanya boleh huruf kecil, angka, dan tanda hubung (-).',
            'slug.unique' => 'Slug website sudah digunakan oleh UMKM lain.',
            'category.required' => 'Bidang usaha wajib dipilih.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
        ]);

        $organization->update([
            'name' => trim($validated['name']),
            'slug' => strtolower(trim($validated['slug'])),
            'category' => $validated['category'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'is_active' => $validated['status'] === 'Aktif',
        ]);

        return redirect()->route('vendor.umkm.index')->with('success', 'Data UMKM "' . $organization->name . '" berhasil diperbarui.');
    }

    /**
     * Toggle status aktif / nonaktif UMKM secara instan (Layar 5).
     */
    public function toggleStatus(Organization $organization): RedirectResponse
    {
        $organization->is_active = !$organization->is_active;
        $organization->save();

        $statusLabel = $organization->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Status operasional UMKM "' . $organization->name . '" berhasil ' . $statusLabel . '.');
    }

    /**
     * Hapus UMKM secara permanen (Layar 5).
     */
    public function destroyUmkm(Organization $organization): RedirectResponse
    {
        $name = $organization->name;
        $organization->delete();

        return redirect()->route('vendor.umkm.index')->with('success', 'UMKM "' . $name . '" beserta datanya berhasil dihapus.');
    }

    /**
     * Ekspor rekap agregat ekosistem UMKM untuk vendor.
     * HANYA berisi angka agregat dan data status UMKM, TANPA ISI PESAN (Sesuai Aturan Global #3).
     */
    public function exportCsv(): StreamedResponse
    {
        $filename = 'Rekap_Ekosistem_Vendor_' . date('Y-m-d') . '.csv';

        $organizations = Organization::latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($organizations) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fputs($file, "\xEF\xBB\xBF");

            // Header Agregat (tanpa isi pesan)
            fputcsv($file, [
                'No',
                'Nama UMKM',
                'Slug Halaman',
                'Kategori Usaha',
                'Status Operasional',
                'Tanggal Terdaftar',
                'Total Produk',
                'Produk Tampil',
                'Total Pertanyaan Masuk',
                'Pertanyaan Perlu Tindak Lanjut (Baru)',
            ]);

            $no = 1;
            foreach ($organizations as $org) {
                $totalProd = Product::withoutTenant()->where('organization_id', $org->id)->count();
                $activeProd = Product::withoutTenant()->where('organization_id', $org->id)->where('status', 'Tampil')->count();
                $totalInq = Inquiry::withoutTenant()->where('organization_id', $org->id)->count();
                $baruInq = Inquiry::withoutTenant()->where('organization_id', $org->id)->where('status', 'Baru')->count();

                fputcsv($file, [
                    $no++,
                    $this->sanitizeCsvField($org->name),
                    $org->slug,
                    $this->sanitizeCsvField($org->category),
                    $org->is_active ? 'Aktif' : 'Nonaktif',
                    $org->created_at->format('Y-m-d H:i:s'),
                    $totalProd,
                    $activeProd,
                    $totalInq,
                    $baruInq,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Sanitasi anti-CSV Formula Injection.
     */
    protected function sanitizeCsvField(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $dangerousPrefixes = ['=', '+', '-', '@', "\t", "\r"];
        $firstChar = substr($value, 0, 1);

        if (in_array($firstChar, $dangerousPrefixes, true)) {
            return "'" . $value;
        }

        return $value;
    }
}

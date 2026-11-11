<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Tampilkan Layar 10: Laporan & Ekspor UMKM.
     * Dilengkapi filter rentang tanggal, filter kategori, filter status pertanyaan,
     * kartu metrik, grafik ringkasan, dan tabel rekap.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;

        // 1. Inisialisasi Query Produk dengan Filter
        $productQuery = Product::query();

        if ($request->filled('start_date')) {
            $productQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $productQuery->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('category')) {
            $productQuery->where('category', $request->category);
        }

        $products = $productQuery->latest('id')->get();

        // 2. Inisialisasi Query Pertanyaan dengan Filter
        $inquiryQuery = Inquiry::query();

        if ($request->filled('start_date')) {
            $inquiryQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $inquiryQuery->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $inquiryQuery->where('status', $request->status);
        }

        $inquiries = $inquiryQuery->latest('id')->get();

        // 3. Hitung Kartu Metrik Ringkasan
        $stats = [
            'total_products' => $products->count(),
            'active_products' => $products->where('status', 'Tampil')->count(),
            'hidden_products' => $products->where('status', 'Sembunyi')->count(),
            'total_inquiries' => $inquiries->count(),
            'inquiries_baru' => $inquiries->where('status', 'Baru')->count(),
            'inquiries_dibalas' => $inquiries->where('status', 'Dibalas')->count(),
            'inquiries_selesai' => $inquiries->where('status', 'Selesai')->count(),
        ];

        // 4. Data untuk Grafik (Produk per Kategori & Pertanyaan per Status)
        $productsByCategory = $products->groupBy('category')->map->count()->toArray();
        $inquiriesByStatus = [
            'Baru' => $stats['inquiries_baru'],
            'Dibalas' => $stats['inquiries_dibalas'],
            'Selesai' => $stats['inquiries_selesai'],
        ];

        // 5. Daftar Kategori untuk Pilihan Filter
        $categories = Product::pluck('category')->unique()->filter()->values();

        $hasActiveFilter = $request->filled('start_date')
            || $request->filled('end_date')
            || $request->filled('category')
            || $request->filled('status');

        $isResultEmpty = $products->isEmpty() && $inquiries->isEmpty() && $hasActiveFilter;

        return view('dashboard.reports.index', [
            'organization' => $organization,
            'products' => $products,
            'inquiries' => $inquiries,
            'stats' => $stats,
            'productsByCategory' => $productsByCategory,
            'inquiriesByStatus' => $inquiriesByStatus,
            'categories' => $categories,
            'filters' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'category' => $request->category,
                'status' => $request->status,
            ],
            'hasActiveFilter' => $hasActiveFilter,
            'isResultEmpty' => $isResultEmpty,
        ]);
    }

    /**
     * Ekspor data rekap produk ke file format CSV.
     * Menggunakan filter aktif dan dilengkapi sanitasi anti-CSV Formula Injection.
     */
    public function exportProductsCsv(Request $request): StreamedResponse
    {
        $organization = Auth::user()->organization;
        $orgName = Str::studly($organization->name ?? 'UMKM');
        $filename = "Laporan_Produk_{$orgName}_" . date('Y-m-d') . '.csv';

        // Terapkan filter yang sama
        $query = Product::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->latest('id')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($products) {
            $file = fopen('php://output', 'w');

            // Tulis Byte Order Mark (BOM) UTF-8 agar kompatibel dengan Excel di Windows
            fputs($file, "\xEF\xBB\xBF");

            // Header Kolom CSV
            fputcsv($file, [
                'No',
                'Nama Produk',
                'Kategori',
                'Harga (Rp)',
                'Status Publikasi',
                'Tanggal Dibuat',
            ]);

            $no = 1;
            foreach ($products as $prod) {
                fputcsv($file, [
                    $no++,
                    $this->sanitizeCsvField($prod->name),
                    $this->sanitizeCsvField($prod->category),
                    (int)$prod->price,
                    $prod->status,
                    $prod->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Ekspor data rekap pesan pertanyaan pengunjung ke file format CSV.
     * Menggunakan filter aktif dan dilengkapi sanitasi anti-CSV Formula Injection.
     */
    public function exportInquiriesCsv(Request $request): StreamedResponse
    {
        $organization = Auth::user()->organization;
        $orgName = Str::studly($organization->name ?? 'UMKM');
        $filename = "Laporan_Pertanyaan_{$orgName}_" . date('Y-m-d') . '.csv';

        // Terapkan filter yang sama
        $query = Inquiry::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inquiries = $query->latest('id')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($inquiries) {
            $file = fopen('php://output', 'w');

            // Tulis Byte Order Mark (BOM) UTF-8
            fputs($file, "\xEF\xBB\xBF");

            // Header Kolom CSV
            fputcsv($file, [
                'No',
                'ID Pesan',
                'Tanggal Masuk',
                'Nama Pengunjung',
                'Kontak',
                'Status',
                'Isi Pesan',
            ]);

            $no = 1;
            foreach ($inquiries as $inq) {
                fputcsv($file, [
                    $no++,
                    $inq->id,
                    $inq->created_at->format('Y-m-d H:i:s'),
                    $this->sanitizeCsvField($inq->visitor_name),
                    $this->sanitizeCsvField($inq->visitor_contact ?? '-'),
                    $inq->status,
                    $this->sanitizeCsvField($inq->message),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Sanitasi pencegahan serangan CSV Formula Injection.
     * Jika diawali tanda '=', '+', '-', '@', atau tab, berikan awalan petik tunggal.
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

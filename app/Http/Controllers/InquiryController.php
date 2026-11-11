<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InquiryController extends Controller
{
    /**
     * Tampilkan daftar pertanyaan masuk milik UMKM yang login (Layar 9: Daftar Pertanyaan).
     */
    public function index(Request $request): View
    {
        $query = Inquiry::query();

        // 1. Filter Pencarian (Nama Pengunjung atau Isi Pesan)
        if ($request->filled('q')) {
            $term = '%' . trim($request->q) . '%';
            $query->where(function ($sub) use ($term) {
                $sub->where('visitor_name', 'like', $term)
                    ->orWhere('message', 'like', $term)
                    ->orWhere('visitor_contact', 'like', $term);
            });
        }

        // 2. Filter Status (Baru, Dibalas, Selesai)
        if ($request->filled('status') && in_array($request->status, ['Baru', 'Dibalas', 'Selesai'])) {
            $query->where('status', $request->status);
        }

        $inquiries = $query->latest('id')->paginate(10)->withQueryString();

        // Hitung statistik status untuk badge filter tab
        $counts = [
            'all' => Inquiry::count(),
            'baru' => Inquiry::where('status', 'Baru')->count(),
            'dibalas' => Inquiry::where('status', 'Dibalas')->count(),
            'selesai' => Inquiry::where('status', 'Selesai')->count(),
        ];

        return view('dashboard.inquiries.index', [
            'inquiries' => $inquiries,
            'counts' => $counts,
            'filters' => [
                'q' => $request->q,
                'status' => $request->status,
            ],
            'organization' => Auth::user()->organization,
        ]);
    }

    /**
     * Tampilkan detail pertanyaan pengunjung (Layar 9: Detail Pertanyaan).
     */
    public function show(Inquiry $inquiry): View
    {
        // Proteksi IDOR eksplisit
        $this->authorizeInquiryOwnership($inquiry);

        return view('dashboard.inquiries.show', [
            'inquiry' => $inquiry,
            'organization' => Auth::user()->organization,
        ]);
    }

    /**
     * Perbarui status pertanyaan (Alur: Baru -> Dibalas -> Selesai / Buka Lagi).
     */
    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        // Proteksi IDOR eksplisit
        $this->authorizeInquiryOwnership($inquiry);

        $validated = $request->validate([
            'status' => ['required', 'in:Baru,Dibalas,Selesai'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status hanya boleh Baru, Dibalas, atau Selesai.',
        ]);

        $oldStatus = $inquiry->status;
        $inquiry->update([
            'status' => $validated['status'],
        ]);

        $message = match ($validated['status']) {
            'Dibalas' => 'Status pertanyaan berhasil ditandai sebagai "Dibalas".',
            'Selesai' => 'Status pertanyaan berhasil ditandai sebagai "Selesai".',
            'Baru' => 'Pertanyaan berhasil dibuka kembali dengan status "Baru".',
            default => 'Status pertanyaan berhasil diperbarui.',
        };

        return back()->with('success', $message);
    }

    /**
     * Hapus pertanyaan dari database.
     */
    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        // Proteksi IDOR eksplisit
        $this->authorizeInquiryOwnership($inquiry);

        $inquiryId = $inquiry->id;
        $visitorName = $inquiry->visitor_name;
        $inquiry->delete();

        return redirect()->route('inquiries.index')
            ->with('success', 'Pertanyaan dari "' . $visitorName . '" (ID #' . $inquiryId . ') berhasil dihapus.');
    }

    /**
     * Helper proteksi IDOR: pastikan pertanyaan benar-benar milik UMKM user yang login.
     */
    protected function authorizeInquiryOwnership(Inquiry $inquiry): void
    {
        if ((int)$inquiry->organization_id !== (int)Auth::user()->organization_id) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk mengelola pesan pertanyaan milik UMKM lain.');
        }
    }
}


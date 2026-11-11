@extends('layouts.app', ['title' => 'Laporan Kinerja & Ekspor Data - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Figcraft Screen 10 -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal UMKM</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Laporan Kinerja &amp; Ekspor Data</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Analisis ringkasan performa katalog produk dan rekap pertanyaan pengunjung.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('reports.export-products', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                <span>📥 Ekspor CSV Produk</span>
            </a>
            <a href="{{ route('reports.export-inquiries', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#e1e8e3] bg-white hover:bg-[#f5f8fa] text-[#141d1a] text-xs font-semibold shadow-sm transition">
                <span>📥 Ekspor CSV Pertanyaan</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar Figcraft Screen 10 (Flt_Bar10) -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-5 shadow-sm">
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <!-- Dari Tanggal -->
            <div class="sm:col-span-3">
                <label for="start_date" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $filters['start_date'] ?? '' }}"
                    class="w-full px-3 py-2 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
            </div>

            <!-- Sampai Tanggal -->
            <div class="sm:col-span-3">
                <label for="end_date" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $filters['end_date'] ?? '' }}"
                    class="w-full px-3 py-2 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
            </div>

            <!-- Kategori -->
            <div class="sm:col-span-3">
                <label for="category" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">Kategori</label>
                <select name="category" id="category"
                    class="w-full px-3 py-2 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ ($filters['category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Pesan -->
            <div class="sm:col-span-2">
                <label for="status" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">Status Pesan</label>
                <select name="status" id="status"
                    class="w-full px-3 py-2 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    <option value="">Semua Status</option>
                    <option value="Baru" {{ ($filters['status'] ?? '') === 'Baru' ? 'selected' : '' }}>Baru</option>
                    <option value="Dibalas" {{ ($filters['status'] ?? '') === 'Dibalas' ? 'selected' : '' }}>Dibalas</option>
                    <option value="Selesai" {{ ($filters['status'] ?? '') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Tombol Filter & Reset -->
            <div class="sm:col-span-1 flex items-center gap-2">
                <button type="submit"
                    class="w-full py-2 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Box Figcraft Screen 10 (Sum10_Box) -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-sm">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 divide-y sm:divide-y-0 sm:divide-x divide-[#e1e8e3]">
            <!-- Total Produk -->
            <div class="pt-2 sm:pt-0 sm:px-3 text-center">
                <span class="block text-2xl font-extrabold text-[#141d1a]">{{ $stats['total_products'] }}</span>
                <span class="text-xs text-[#61716a] font-medium mt-1 block">Total Produk</span>
            </div>

            <!-- Produk Tampil -->
            <div class="pt-2 sm:pt-0 sm:px-3 text-center">
                <span class="block text-2xl font-extrabold text-[#16a34a]">{{ $stats['active_products'] }}</span>
                <span class="text-xs text-[#61716a] font-medium mt-1 block">Produk Tampil</span>
            </div>

            <!-- Total Pertanyaan -->
            <div class="pt-2 sm:pt-0 sm:px-3 text-center">
                <span class="block text-2xl font-extrabold text-[#141d1a]">{{ $stats['total_inquiries'] }}</span>
                <span class="text-xs text-[#61716a] font-medium mt-1 block">Total Pertanyaan</span>
            </div>

            <!-- Pertanyaan Baru -->
            <div class="pt-2 sm:pt-0 sm:px-3 text-center">
                <span class="block text-2xl font-extrabold text-[#b91c1c]">{{ $stats['inquiries_baru'] }}</span>
                <span class="text-xs text-[#61716a] font-medium mt-1 block">Pertanyaan Baru</span>
            </div>

            <!-- Dibalas -->
            <div class="pt-2 sm:pt-0 sm:px-3 text-center">
                <span class="block text-2xl font-extrabold text-[#b45309]">{{ $stats['inquiries_dibalas'] }}</span>
                <span class="text-xs text-[#61716a] font-medium mt-1 block">Dibalas</span>
            </div>

            <!-- Selesai -->
            <div class="pt-2 sm:pt-0 sm:px-3 text-center">
                <span class="block text-2xl font-extrabold text-[#15803d]">{{ $stats['inquiries_selesai'] }}</span>
                <span class="text-xs text-[#61716a] font-medium mt-1 block">Selesai</span>
            </div>
        </div>
    </div>

    <!-- 2 Kolom Rekapitulasi Figcraft Screen 10 (Box_RP10 & Box_RQ10) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rekapitulasi Produk per Kategori -->
        <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-sm">
            <h2 class="text-base font-bold text-[#141d1a] mb-4 pb-2 border-b border-[#e1e8e3] flex items-center gap-2">
                <span>📦</span>
                <span>Tabel Rekapitulasi Produk</span>
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#141d1a]">
                    <thead class="bg-[#f5f8fa] border-b border-[#e1e8e3] text-[#61716a] font-semibold">
                        <tr>
                            <th scope="col" class="px-4 py-3">Kategori</th>
                            <th scope="col" class="px-4 py-3 text-center">Jumlah</th>
                            <th scope="col" class="px-4 py-3 text-right">Harga Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1e8e3]">
                        @php
                            $groupedProducts = $products->groupBy('category');
                        @endphp
                        @forelse($groupedProducts as $catName => $catItems)
                            <tr class="hover:bg-[#f9fbf7] transition">
                                <td class="px-4 py-3 font-semibold text-[#141d1a]">
                                    {{ $catName ?: 'Lainnya' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#dcfce7] text-[#15803d]">
                                        {{ $catItems->count() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-[#16a34a] font-bold">
                                    Rp{{ number_format($catItems->avg('price'), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-[#61716a]">
                                    Belum ada data produk untuk filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rekapitulasi Pertanyaan per Status -->
        <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-sm">
            <h2 class="text-base font-bold text-[#141d1a] mb-4 pb-2 border-b border-[#e1e8e3] flex items-center gap-2">
                <span>💬</span>
                <span>Tabel Rekapitulasi Pertanyaan</span>
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#141d1a]">
                    <thead class="bg-[#f5f8fa] border-b border-[#e1e8e3] text-[#61716a] font-semibold">
                        <tr>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3 text-center">Jumlah</th>
                            <th scope="col" class="px-4 py-3 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1e8e3]">
                        @php
                            $totalInq = max($stats['total_inquiries'], 1);
                        @endphp
                        <tr class="hover:bg-[#f9fbf7] transition">
                            <td class="px-4 py-3 font-semibold text-[#b91c1c] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#b91c1c]"></span>
                                <span>Baru</span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">
                                {{ $stats['inquiries_baru'] }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-[#61716a]">
                                {{ round(($stats['inquiries_baru'] / $totalInq) * 100, 1) }}%
                            </td>
                        </tr>
                        <tr class="hover:bg-[#f9fbf7] transition">
                            <td class="px-4 py-3 font-semibold text-[#b45309] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#b45309]"></span>
                                <span>Dibalas</span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">
                                {{ $stats['inquiries_dibalas'] }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-[#61716a]">
                                {{ round(($stats['inquiries_dibalas'] / $totalInq) * 100, 1) }}%
                            </td>
                        </tr>
                        <tr class="hover:bg-[#f9fbf7] transition">
                            <td class="px-4 py-3 font-semibold text-[#15803d] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#15803d]"></span>
                                <span>Selesai</span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">
                                {{ $stats['inquiries_selesai'] }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-[#61716a]">
                                {{ round(($stats['inquiries_selesai'] / $totalInq) * 100, 1) }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

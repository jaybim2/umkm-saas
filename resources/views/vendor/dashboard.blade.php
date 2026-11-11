@extends('layouts.app', ['title' => 'Dashboard Utama Vendor - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Layar 4 Figcraft -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal VENDOR</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Dashboard Utama Vendor</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Pusat monitoring ekosistem platform UMKM terdaftar secara agregat.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('vendor.export') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#e1e8e3] bg-white hover:bg-[#f5f8fa] text-[#141d1a] text-xs font-semibold shadow-sm transition">
                <span>📥 Ekspor CSV Agregat</span>
            </a>
            <a href="{{ route('vendor.umkm.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                <span>+ Tambah UMKM</span>
            </a>
        </div>
    </div>

    <!-- 4 Stat Cards Figcraft Screen 4 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total UMKM Terdaftar -->
        <div class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-2xl">🏪</span>
                <span class="text-3xl font-extrabold text-[#141d1a]">{{ $stats['total_organizations'] }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a]">Total UMKM Terdaftar</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">UMKM terdaftar di platform</p>
            </div>
        </div>

        <!-- 2. UMKM Aktif -->
        @php
            $activeCount = $organizations->where('is_active', true)->count();
            $inactiveCount = $organizations->where('is_active', false)->count();
        @endphp
        <div class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-2xl">✅</span>
                <span class="text-3xl font-extrabold text-[#16a34a]">{{ $activeCount }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a]">UMKM Aktif</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">{{ $inactiveCount }} berstatus nonaktif</p>
            </div>
        </div>

        <!-- 3. Total Seluruh Produk -->
        <div class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-2xl">📦</span>
                <span class="text-3xl font-extrabold text-[#141d1a]">{{ $stats['total_products'] }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a]">Total Seluruh Produk</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">Katalog produk aktif platform</p>
            </div>
        </div>

        <!-- 4. Pertanyaan Masuk -->
        <div class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-2xl">💬</span>
                <span class="text-3xl font-extrabold text-[#141d1a]">{{ $stats['total_inquiries'] }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a]">Pertanyaan Masuk</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">Angka agregat lintas UMKM</p>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar 5 UMKM Terbaru Figcraft Screen 4 -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#e1e8e3] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-[#141d1a]">5 UMKM Pendaftar Terbaru</h2>
                <p class="text-xs text-[#61716a] mt-0.5">Menjaga privasi tenant: detail pesan dan percakapan hanya dapat diakses oleh admin UMKM terkait.</p>
            </div>
            <div>
                <a href="{{ route('vendor.umkm.index') }}" class="text-xs font-semibold text-[#16a34a] hover:underline flex items-center gap-1">
                    <span>Kelola Seluruh UMKM</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#141d1a]">
                <thead class="bg-[#f5f8fa] border-b border-[#e1e8e3] text-[#61716a] font-semibold">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">Nama UMKM</th>
                        <th scope="col" class="px-6 py-3.5">Slug Website</th>
                        <th scope="col" class="px-6 py-3.5">Bidang Usaha</th>
                        <th scope="col" class="px-6 py-3.5">Status</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e1e8e3]">
                    @forelse($organizations as $org)
                        <tr class="hover:bg-[#f9fbf7] transition">
                            <td class="px-6 py-4 font-semibold text-[#141d1a] flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#dcfce7] text-[#15803d] flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($org->name, 0, 1)) }}
                                </div>
                                <span>{{ $org->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-[#16a34a] font-medium">/u/{{ $org->slug }}</span>
                            </td>
                            <td class="px-6 py-4 text-[#61716a]">
                                {{ $org->category }}
                            </td>
                            <td class="px-6 py-4">
                                @if($org->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#dcfce7] text-[#15803d]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#f1f5f8] text-[#64748b]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ url('/u/' . $org->slug) }}" target="_blank"
                                    class="text-[#16a34a] hover:underline font-semibold inline-flex items-center gap-1">
                                    <span>Lihat Halaman</span>
                                    <span>↗</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#61716a]">
                                Belum ada UMKM terdaftar di platform.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Catatan Privasi Tenant & Keamanan -->
    <div class="p-4 rounded-xl bg-[#dcfce7]/40 border border-[#e1e8e3] text-xs text-[#141d1a] flex items-start gap-3">
        <span class="text-base">💡</span>
        <div>
            <span class="font-bold text-[#15803d]">Prinsip Keamanan Siber (Privasi Multi-Tenant):</span>
            <span class="text-[#61716a]"> Vendor hanya memiliki wewenang untuk memantau data UMKM dan agregat platform. Data privasi pesan pertanyaan pelanggan dilindungi penuh dan hanya dapat dibuka oleh admin UMKM masing-masing.</span>
        </div>
    </div>
</div>
@endsection

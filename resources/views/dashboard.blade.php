@extends('layouts.app', ['title' => 'Dashboard ' . ($organization->name ?? 'UMKM') . ' - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Layar 6 Figcraft -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal UMKM</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Dashboard {{ $organization->name ?? 'Toko Anda' }}</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Kelola produk, pantau pertanyaan masuk, dan bagikan etalase tokomu.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(isset($organization->slug))
                <a href="{{ url('/u/' . $organization->slug) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-[#e1e8e3] bg-white hover:bg-[#f5f8fa] text-[#141d1a] text-xs font-semibold shadow-sm transition">
                    <span>Lihat Halaman Saya ↗</span>
                </a>
                <button type="button" onclick="copySlugLink('{{ url('/u/' . $organization->slug) }}')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                    <span id="copy-text">📋 Salin Link</span>
                </button>
            @endif
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#dcfce7] hover:bg-[#bbf7d0] text-[#15803d] text-xs font-bold shadow-sm transition">
                <span>+ Produk</span>
            </a>
        </div>
    </div>

    <!-- 4 Stat Cards Figcraft Layar 6 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Produk -->
        <div class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-2xl">📦</span>
                <span class="text-3xl font-extrabold text-[#141d1a]">{{ $stats['total_products'] }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a]">Total Produk</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">Katalog lengkap terdaftar</p>
            </div>
        </div>

        <!-- 2. Produk Tampil -->
        <div class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-2xl">👁️</span>
                <span class="text-3xl font-extrabold text-[#16a34a]">{{ $stats['active_products'] }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a]">Produk Tampil</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">{{ $stats['hidden_products'] }} produk disembunyikan</p>
            </div>
        </div>

        <!-- 3. Pertanyaan Baru -->
        <a href="{{ route('inquiries.index', ['status' => 'Baru']) }}" class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm hover:border-[#16a34a] transition block group">
            <div class="flex items-center justify-between">
                <span class="text-2xl">🔴</span>
                <span class="text-3xl font-extrabold text-[#b91c1c]">{{ $stats['inquiries_baru'] }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a] group-hover:text-[#16a34a] transition">Pertanyaan Baru</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">Perlu segera ditindaklanjuti</p>
            </div>
        </a>

        <!-- 4. Pertanyaan Selesai -->
        <a href="{{ route('inquiries.index', ['status' => 'Selesai']) }}" class="bg-white rounded-2xl border border-[#e1e8e3] p-5 shadow-sm hover:border-[#16a34a] transition block group">
            <div class="flex items-center justify-between">
                <span class="text-2xl">✅</span>
                <span class="text-3xl font-extrabold text-[#16a34a]">{{ $stats['inquiries_selesai'] }}</span>
            </div>
            <div class="mt-3">
                <h3 class="text-xs font-bold text-[#141d1a] group-hover:text-[#16a34a] transition">Pertanyaan Selesai</h3>
                <p class="text-[11px] text-[#61716a] mt-0.5">Telah direspon admin</p>
            </div>
        </a>
    </div>

    <!-- Green Link Banner Figcraft Screen 6 -->
    @if(isset($organization->slug))
        <div class="bg-[#dcfce7] border border-[#bbf7d0] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-[#15803d]">
            <div class="flex items-center gap-2">
                <span class="font-bold text-sm">🔗</span>
                <span>Alamat Etalase Publikmu: <strong class="underline font-mono">{{ url('/u/' . $organization->slug) }}</strong></span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/u/' . $organization->slug) }}" target="_blank" class="hover:underline font-semibold">Buka Web ↗</a>
                <button type="button" onclick="copySlugLink('{{ url('/u/' . $organization->slug) }}')" class="font-bold hover:underline">Salin</button>
            </div>
        </div>
    @endif

    <!-- 2 Kolom: 5 Produk Terbaru & 5 Pertanyaan Pengunjung Terbaru Figcraft Screen 6 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Box Kiri: 5 Produk Terbaru -->
        <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#e1e8e3]">
                <h2 class="text-sm font-bold text-[#141d1a] flex items-center gap-2">
                    <span>📦</span>
                    <span>5 Produk Terbaru</span>
                </h2>
                <a href="{{ route('products.index') }}" class="text-xs font-semibold text-[#16a34a] hover:underline">
                    Kelola Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-[#e1e8e3]">
                @forelse($recentProducts as $prod)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[#141d1a] truncate">{{ $prod->name }}</p>
                            <p class="text-[11px] text-[#61716a] mt-0.5">{{ $prod->category ?? 'Umum' }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs font-bold text-[#16a34a]">Rp{{ number_format($prod->price, 0, ',', '.') }}</span>
                            @if($prod->status === 'Tampil')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#dcfce7] text-[#15803d]">
                                    Tampil
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#f1f5f8] text-[#64748b]">
                                    Sembunyi
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-[#61716a]">
                        Belum ada produk. <a href="{{ route('products.create') }}" class="text-[#16a34a] font-semibold hover:underline">Tambah produk pertama</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Box Kanan: 5 Pertanyaan Pengunjung Terbaru -->
        <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#e1e8e3]">
                <h2 class="text-sm font-bold text-[#141d1a] flex items-center gap-2">
                    <span>💬</span>
                    <span>5 Pertanyaan Pengunjung Terbaru</span>
                </h2>
                <a href="{{ route('inquiries.index') }}" class="text-xs font-semibold text-[#16a34a] hover:underline">
                    Lihat Kotak Masuk &rarr;
                </a>
            </div>

            <div class="divide-y divide-[#e1e8e3]">
                @forelse($recentInquiries as $inq)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[#141d1a]">{{ $inq->name }}</p>
                            <p class="text-[11px] text-[#61716a] truncate mt-0.5">{{ $inq->message }}</p>
                        </div>
                        <div class="shrink-0">
                            @if($inq->status === 'Baru')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#fee2e2] text-[#b91c1c]">
                                    Baru
                                </span>
                            @elseif($inq->status === 'Dibalas')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#fef3c7] text-[#b45309]">
                                    Dibalas
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#dcfce7] text-[#15803d]">
                                    Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-[#61716a]">
                        Belum ada pertanyaan masuk dari pengunjung.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function copySlugLink(url) {
        navigator.clipboard.writeText(url).then(function() {
            const btn = document.getElementById('copy-text');
            if (btn) {
                btn.textContent = '✓ Tersalin!';
                setTimeout(() => { btn.textContent = '📋 Salin Link'; }, 2000);
            }
        });
    }
</script>
@endsection

@extends('layouts.app', ['title' => 'Manajemen Katalog Produk - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Layar 8 Figcraft -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal UMKM</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Manajemen Katalog Produk</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Kelola produk atau layanan etalase tokomu dengan rapi dan aman.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                <span>+ Tambah Produk Baru</span>
            </a>
        </div>
    </div>

    <!-- Box Filter & Pencarian Layar 8 Figcraft -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-5 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-center gap-3">
            <!-- Search Box -->
            <div class="flex-1 min-w-[240px]">
                <input type="text" name="q" id="q" value="{{ $filters['q'] ?? '' }}"
                    placeholder="🔍 Cari Nama Produk..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
            </div>

            <!-- Status Pills Filter -->
            <div class="flex items-center gap-2">
                <a href="{{ route('products.index', array_merge(request()->except('status'), ['status' => ''])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ empty($filters['status']) ? 'bg-[#16a34a] text-white' : 'bg-[#f5f8fa] text-[#61716a] border border-[#e1e8e3] hover:bg-[#e1e8e3]' }}">
                    Semua
                </a>
                <a href="{{ route('products.index', array_merge(request()->except('status'), ['status' => 'Tampil'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($filters['status'] ?? '') === 'Tampil' ? 'bg-[#16a34a] text-white' : 'bg-[#f5f8fa] text-[#61716a] border border-[#e1e8e3] hover:bg-[#e1e8e3]' }}">
                    Tampil
                </a>
                <a href="{{ route('products.index', array_merge(request()->except('status'), ['status' => 'Sembunyi'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($filters['status'] ?? '') === 'Sembunyi' ? 'bg-[#16a34a] text-white' : 'bg-[#f5f8fa] text-[#61716a] border border-[#e1e8e3] hover:bg-[#e1e8e3]' }}">
                    Sembunyi
                </a>
            </div>

            @if(!empty($filters['q']) || !empty($filters['status']) || !empty($filters['category']))
                <a href="{{ route('products.index') }}" class="text-xs text-[#b91c1c] font-semibold hover:underline px-2">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Daftar Produk Figcraft Layar 8 -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl shadow-sm overflow-hidden">
        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#141d1a]">
                    <thead class="bg-[#f5f8fa] border-b border-[#e1e8e3] text-[#61716a] font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Foto &amp; Nama Produk</th>
                            <th scope="col" class="px-6 py-3.5">Kategori</th>
                            <th scope="col" class="px-6 py-3.5">Harga (Rp)</th>
                            <th scope="col" class="px-6 py-3.5">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1e8e3]">
                        @foreach($products as $product)
                            <tr class="hover:bg-[#f9fbf7] transition">
                                <!-- Foto & Nama Produk -->
                                <td class="px-6 py-4 flex items-center gap-3">
                                    @if($product->photo_url)
                                        <img src="{{ $product->photo_url }}" alt="{{ $product->name }}"
                                            class="w-9 h-9 rounded-lg object-cover border border-[#e1e8e3] bg-[#f5f8fa] shrink-0"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-9 h-9 rounded-lg bg-[#dcfce7] text-[#15803d] flex items-center justify-center font-bold text-xs shrink-0\'>☕</div>';">
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-[#dcfce7] text-[#15803d] flex items-center justify-center font-bold text-xs shrink-0">
                                            ☕
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-bold text-[#141d1a] truncate">{{ $product->name }}</p>
                                        @if($product->description)
                                            <p class="text-[#61716a] text-[11px] truncate max-w-xs">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="px-6 py-4 text-[#61716a]">
                                    {{ $product->category }}
                                </td>

                                <!-- Harga -->
                                <td class="px-6 py-4 font-bold text-[#16a34a] font-mono">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    @if($product->status === 'Tampil')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#dcfce7] text-[#15803d]">
                                            Tampil
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#f1f5f8] text-[#64748b]">
                                            Sembunyi
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="font-semibold text-[#16a34a] hover:underline">
                                            Ubah
                                        </a>
                                        <span class="text-[#e1e8e3]">&bull;</span>
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('products.destroy', $product) }}', '{{ addslashes($product->name) }}')"
                                            class="font-semibold text-[#b91c1c] hover:underline">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination jika lebih dari 1 halaman -->
            @if($products->hasPages())
                <div class="p-4 border-t border-[#e1e8e3] bg-[#fcfdfc]">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center text-xs text-[#61716a]">
                <p class="font-semibold text-[#141d1a]">Belum ada produk ditemukan.</p>
                <p class="mt-1">Silakan tambah produk baru untuk ditampilkan pada etalase tokomu.</p>
                <div class="mt-4">
                    <a href="{{ route('products.create') }}" class="px-4 py-2 rounded-xl bg-[#16a34a] text-white font-semibold inline-block">
                        + Tambah Produk Baru
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus Produk Figcraft -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-[#e1e8e3] max-w-sm w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🗑️</span>
            <div>
                <h3 class="text-sm font-bold text-[#141d1a]">Konfirmasi Hapus Produk</h3>
                <p class="text-xs text-[#61716a] mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <p class="text-xs text-[#141d1a]">
            Apakah Anda yakin ingin menghapus produk <strong id="deleteProductName" class="text-[#b91c1c]"></strong>?
        </p>
        <form id="deleteForm" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()"
                class="px-4 py-2 rounded-xl border border-[#e1e8e3] text-xs font-semibold text-[#61716a] hover:bg-[#f5f8fa] transition">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 rounded-xl bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca] text-xs font-bold transition">
                Ya, Hapus Produk
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl, productName) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteProductName').textContent = productName;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection

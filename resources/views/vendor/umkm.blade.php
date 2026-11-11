@extends('layouts.app', ['title' => 'Kelola Seluruh UMKM Terdaftar - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Layar 5 Figcraft -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal VENDOR</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Kelola Seluruh UMKM Terdaftar</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Pantau status operasional, ubah data identitas, atau nonaktifkan toko tenant.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('vendor.export') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#e1e8e3] bg-white hover:bg-[#f5f8fa] text-[#141d1a] text-xs font-semibold shadow-sm transition">
                <span>📥 Ekspor CSV Agregat</span>
            </a>
            <a href="{{ route('vendor.dashboard') }}"
                class="text-xs font-semibold text-[#61716a] hover:text-[#141d1a]">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-[#dcfce7] border border-[#bbf7d0] text-xs text-[#15803d] flex items-center gap-2 shadow-sm">
            <span class="text-base">✅</span>
            <span><strong>Berhasil:</strong> {{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-[#fee2e2] border border-[#fca5a5] text-xs text-[#b91c1c] space-y-1 shadow-sm">
            <div class="font-bold flex items-center gap-1.5">
                <span>⚠️ Mohon periksa kembali formulir UMKM:</span>
            </div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 2 Kolom Layout Figcraft Layar 5 (Kiri: Tabel UMKM, Kanan: Form Tambah/Ubah) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Kolom Kiri: Tabel UMKM Terdaftar (Tbl_Bg_L5, lg:col-span-8) -->
        <div class="lg:col-span-8 bg-white border border-[#e1e8e3] rounded-2xl shadow-sm overflow-hidden p-6 space-y-5">
            <!-- Filter & Search Bar Figcraft Layar 5 -->
            <form method="GET" action="{{ route('vendor.umkm.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[220px]">
                    <input type="text" name="q" id="q" value="{{ $filters['q'] ?? '' }}"
                        placeholder="🔍 Cari Nama UMKM atau Slug..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                </div>

                <!-- Status Pills Filter -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('vendor.umkm.index', array_filter(['q' => $filters['q'] ?? null])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ empty($filters['status']) ? 'bg-[#16a34a] text-white shadow-sm' : 'bg-[#f5f8fa] text-[#61716a] border border-[#e1e8e3] hover:bg-[#e1e8e3]' }}">
                        Semua ({{ $counts['all'] }})
                    </a>
                    <a href="{{ route('vendor.umkm.index', array_merge(array_filter(['q' => $filters['q'] ?? null]), ['status' => 'Aktif'])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($filters['status'] ?? '') === 'Aktif' ? 'bg-[#16a34a] text-white shadow-sm' : 'bg-[#f5f8fa] text-[#61716a] border border-[#e1e8e3] hover:bg-[#e1e8e3]' }}">
                        Aktif ({{ $counts['active'] }})
                    </a>
                    <a href="{{ route('vendor.umkm.index', array_merge(array_filter(['q' => $filters['q'] ?? null]), ['status' => 'Nonaktif'])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($filters['status'] ?? '') === 'Nonaktif' ? 'bg-[#16a34a] text-white shadow-sm' : 'bg-[#f5f8fa] text-[#61716a] border border-[#e1e8e3] hover:bg-[#e1e8e3]' }}">
                        Nonaktif ({{ $counts['inactive'] }})
                    </a>
                </div>

                @if(!empty($filters['q']) || !empty($filters['status']))
                    <a href="{{ route('vendor.umkm.index') }}" class="text-xs text-[#b91c1c] font-semibold hover:underline px-2">
                        Reset
                    </a>
                @endif
            </form>

            <!-- Tabel Daftar UMKM Figcraft Layar 5 -->
            <div class="overflow-x-auto border border-[#e1e8e3] rounded-xl">
                <table class="w-full text-left text-xs text-[#141d1a]">
                    <thead class="bg-[#f5f8fa] border-b border-[#e1e8e3] text-[#61716a] font-semibold">
                        <tr>
                            <th scope="col" class="px-5 py-3">Nama &amp; Slug</th>
                            <th scope="col" class="px-4 py-3">Bidang</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e1e8e3]">
                        @forelse($organizations as $org)
                            <tr class="hover:bg-[#f9fbf7] transition {{ $selectedUmkm && $selectedUmkm->id === $org->id ? 'bg-[#dcfce7]/30' : '' }}">
                                <!-- Nama & Slug -->
                                <td class="px-5 py-3.5">
                                    <p class="font-bold text-[#141d1a]">{{ $org->name }}</p>
                                    <p class="font-mono text-[#16a34a] text-[11px] mt-0.5">/u/{{ $org->slug }}</p>
                                </td>

                                <!-- Bidang -->
                                <td class="px-4 py-3.5 text-[#61716a]">
                                    {{ $org->category }}
                                </td>

                                <!-- Status Badge -->
                                <td class="px-4 py-3.5">
                                    @if($org->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#dcfce7] text-[#15803d]">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#f1f5f8] text-[#64748b]">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi Cepat: Lihat • Ubah • Nonaktifkan/Aktifkan -->
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ url('/u/' . $org->slug) }}" target="_blank"
                                            class="text-[#16a34a] font-semibold hover:underline" title="Buka etalase publik">
                                            Lihat ↗
                                        </a>
                                        <span class="text-[#e1e8e3]">&bull;</span>
                                        <a href="{{ route('vendor.umkm.index', array_merge(request()->query(), ['edit' => $org->id])) }}"
                                            class="text-[#141d1a] font-semibold hover:text-[#16a34a]" title="Ubah data UMKM ini">
                                            Ubah
                                        </a>
                                        <span class="text-[#e1e8e3]">&bull;</span>
                                        <form method="POST" action="{{ route('vendor.umkm.status', $org) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($org->is_active)
                                                <button type="submit" class="text-[#b91c1c] font-semibold hover:underline"
                                                    onclick="return confirm('Nonaktifkan etalase {{ addslashes($org->name) }} sementara?')">
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <button type="submit" class="text-[#15803d] font-semibold hover:underline"
                                                    onclick="return confirm('Aktifkan kembali etalase {{ addslashes($org->name) }}?')">
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-[#61716a]">
                                    Tidak ada data UMKM yang cocok dengan pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Kolom Kanan: Form Tambah / Ubah UMKM Figcraft Layar 5 (FBox5, lg:col-span-4) -->
        <div class="lg:col-span-4 bg-white border border-[#e1e8e3] rounded-2xl shadow-sm p-6 space-y-5">
            <div class="pb-3 border-b border-[#e1e8e3] flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-[#141d1a]">
                        {{ $selectedUmkm ? 'Ubah Data UMKM' : 'Form Tambah UMKM Baru' }}
                    </h2>
                    <p class="text-[11px] text-[#61716a] mt-0.5">
                        {{ $selectedUmkm ? 'Mengedit: ' . $selectedUmkm->name : 'Daftarkan UMKM baru ke dalam ekosistem platform.' }}
                    </p>
                </div>
                @if($selectedUmkm)
                    <a href="{{ route('vendor.umkm.index') }}" class="text-xs text-[#b91c1c] font-semibold hover:underline">
                        + Tambah Baru
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ $selectedUmkm ? route('vendor.umkm.update', $selectedUmkm) : route('vendor.umkm.store') }}" class="space-y-4">
                @csrf
                @if($selectedUmkm)
                    @method('PUT')
                @endif

                <!-- Nama UMKM -->
                <div>
                    <label for="form_name" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">
                        Nama UMKM <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="form_name" required maxlength="150"
                        value="{{ old('name', $selectedUmkm->name ?? '') }}"
                        placeholder="Contoh: Toko Kopi ABC"
                        class="w-full px-3.5 py-2.5 rounded-lg border @error('name') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug Website -->
                <div>
                    <label for="form_slug" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">
                        Slug Website <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex rounded-lg shadow-sm">
                        <span class="inline-flex items-center px-2.5 rounded-l-lg border border-r-0 border-[#e1e8e3] bg-[#f5f8fa] text-[#61716a] text-xs select-none">
                            /u/
                        </span>
                        <input type="text" name="slug" id="form_slug" required maxlength="150"
                            value="{{ old('slug', $selectedUmkm->slug ?? '') }}"
                            placeholder="toko-kopi-abc"
                            class="flex-1 min-w-0 block w-full px-3 py-2.5 rounded-none rounded-r-lg border @error('slug') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-xs text-[#16a34a] font-medium focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    </div>
                    @error('slug')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bidang Usaha -->
                <div>
                    <label for="form_category" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">
                        Bidang Usaha <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" id="form_category" required
                        class="w-full px-3.5 py-2.5 rounded-lg border @error('category') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @php
                            $cats = ['Kuliner', 'Fashion', 'Kerajinan', 'Jasa', 'Pertanian', 'Toko Kelontong', 'Lainnya'];
                            $currentCat = old('category', $selectedUmkm->category ?? '');
                        @endphp
                        <option value="">-- Pilih Bidang Usaha --</option>
                        @foreach($cats as $cat)
                            <option value="{{ $cat }}" {{ $currentCat === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon / WA -->
                <div>
                    <label for="form_phone" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">
                        Nomor Telepon / WA <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="phone" id="form_phone" required maxlength="30"
                        value="{{ old('phone', $selectedUmkm->phone ?? '') }}"
                        placeholder="081234567890"
                        class="w-full px-3.5 py-2.5 rounded-lg border @error('phone') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    @error('phone')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Kontak -->
                <div>
                    <label for="form_email" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">
                        Email Kontak
                    </label>
                    <input type="email" name="email" id="form_email" maxlength="150"
                        value="{{ old('email', $selectedUmkm->email ?? '') }}"
                        placeholder="kontak@tokokopiabc.com"
                        class="w-full px-3.5 py-2.5 rounded-lg border @error('email') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                </div>

                <!-- Status UMKM -->
                <div>
                    <label for="form_status" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1">
                        Status UMKM <span class="text-rose-500">*</span>
                    </label>
                    @php
                        $curStatus = old('status', ($selectedUmkm ? ($selectedUmkm->is_active ? 'Aktif' : 'Nonaktif') : 'Aktif'));
                    @endphp
                    <select name="status" id="form_status" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        <option value="Aktif" {{ $curStatus === 'Aktif' ? 'selected' : '' }}>Aktif (Publik Bisa Akses)</option>
                        <option value="Nonaktif" {{ $curStatus === 'Nonaktif' ? 'selected' : '' }}>Nonaktif (Ditutup Sementara)</option>
                    </select>
                </div>

                <!-- Tombol Aksi Simpan & Hapus Figcraft Screen 5 -->
                <div class="pt-3 border-t border-[#e1e8e3] flex items-center gap-2">
                    <button type="submit"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white font-semibold text-xs transition shadow-sm text-center">
                        {{ $selectedUmkm ? 'Simpan Perubahan' : 'Simpan UMKM' }}
                    </button>
                    @if($selectedUmkm)
                        <button type="button" onclick="confirmDeleteUmkm('{{ route('vendor.umkm.destroy', $selectedUmkm) }}', '{{ addslashes($selectedUmkm->name) }}')"
                            class="py-2.5 px-3 rounded-xl bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca] font-semibold text-xs transition">
                            Hapus...
                        </button>
                    @endif
                </div>
            </form>

            <!-- Modal_Info_Bg Figcraft Screen 5 -->
            <div class="bg-[#f5f8fa] border border-[#e1e8e3] rounded-xl p-3.5 text-xs text-[#141d1a] space-y-1">
                <span class="font-bold text-[#15803d] flex items-center gap-1.5">
                    <span>💡</span>
                    <span>Catatan Hapus &amp; Nonaktif:</span>
                </span>
                <p class="text-[11px] text-[#61716a] leading-relaxed">
                    Gunakan <strong>'Nonaktifkan'</strong> untuk pengoperasian harian agar data produk tidak terhapus permanen dari basis data.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus UMKM Figcraft -->
<div id="deleteUmkmModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-[#e1e8e3] max-w-sm w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⚠️</span>
            <div>
                <h3 class="text-sm font-bold text-[#141d1a]">Hapus UMKM Permanen</h3>
                <p class="text-xs text-[#61716a] mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        <p class="text-xs text-[#141d1a]">
            Apakah Anda yakin ingin menghapus UMKM <strong id="deleteUmkmName" class="text-[#b91c1c]"></strong> beserta seluruh produknya?
        </p>
        <form id="deleteUmkmForm" method="POST" action="" class="flex items-center justify-end gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteUmkmModal()"
                class="px-4 py-2 rounded-xl border border-[#e1e8e3] text-xs font-semibold text-[#61716a] hover:bg-[#f5f8fa] transition">
                Batal
            </button>
            <button type="submit"
                class="px-4 py-2 rounded-xl bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca] text-xs font-bold transition">
                Ya, Hapus Permanen
            </button>
        </form>
    </div>
</div>

<script>
    function confirmDeleteUmkm(actionUrl, umkmName) {
        document.getElementById('deleteUmkmForm').action = actionUrl;
        document.getElementById('deleteUmkmName').textContent = umkmName;
        document.getElementById('deleteUmkmModal').classList.remove('hidden');
    }

    function closeDeleteUmkmModal() {
        document.getElementById('deleteUmkmModal').classList.add('hidden');
    }

    // Auto-generate slug jika menambah UMKM baru
    const nameInp = document.getElementById('form_name');
    const slugInp = document.getElementById('form_slug');
    let userEditedSlug = {{ $selectedUmkm ? 'true' : 'false' }};

    if (slugInp) {
        slugInp.addEventListener('input', () => { userEditedSlug = true; });
    }
    if (nameInp && !userEditedSlug) {
        nameInp.addEventListener('input', () => {
            if (!userEditedSlug) {
                slugInp.value = nameInp.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    }
</script>
@endsection


@extends('layouts.app', ['title' => 'Tambah Produk Baru - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Figcraft Screen 8 -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal UMKM</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Tambah Produk Baru</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Lengkapi formulir untuk menambahkan produk ke katalog tokomu.</p>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-[#61716a] hover:text-[#141d1a]">
                &larr; Kembali ke Daftar Produk
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="font-semibold flex items-center gap-2 mb-1.5">
                <span>⚠️ Mohon periksa kembali formulir produk:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Box Figcraft Screen 8 (Form_P8) -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('products.store') }}" class="space-y-5">
            @csrf

            <!-- Nama Produk -->
            <div>
                <label for="name" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                    Nama Produk <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" required maxlength="150"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Kopi Susu Gula Aren"
                    class="w-full px-3.5 py-2.5 rounded-lg border @error('name') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori & Harga Jual -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" id="category" required
                        class="w-full px-3.5 py-2.5 rounded-lg border @error('category') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori...</option>
                        <option value="Minuman" {{ old('category') === 'Minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="Makanan" {{ old('category') === 'Makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="Kudapan" {{ old('category') === 'Kudapan' ? 'selected' : '' }}>Kudapan</option>
                        <option value="Kuliner" {{ old('category') === 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                        <option value="Fashion" {{ old('category') === 'Fashion' ? 'selected' : '' }}>Fashion</option>
                        <option value="Kerajinan" {{ old('category') === 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                        <option value="Jasa" {{ old('category') === 'Jasa' ? 'selected' : '' }}>Jasa</option>
                        <option value="Lainnya" {{ old('category') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Jual (Rp) -->
                <div>
                    <label for="price" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                        Harga Jual (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" required min="0" step="500"
                        value="{{ old('price', '18000') }}"
                        placeholder="18000"
                        class="w-full px-3.5 py-2.5 rounded-lg border @error('price') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    @error('price')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Foto Produk URL -->
            <div>
                <label for="photo_url" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                    Foto Produk (URL Gambar)
                </label>
                <input type="url" name="photo_url" id="photo_url" maxlength="500"
                    value="{{ old('photo_url') }}"
                    placeholder="https://images.unsplash.com/..."
                    class="w-full px-3.5 py-2.5 rounded-lg border @error('photo_url') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                @error('photo_url')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Produk -->
            <div>
                <label for="status" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                    Status Produk <span class="text-rose-500">*</span>
                </label>
                <select name="status" id="status" required
                    class="w-full px-3.5 py-2.5 rounded-lg border @error('status') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    <option value="Tampil" {{ old('status', 'Tampil') === 'Tampil' ? 'selected' : '' }}>Tampil (Muncul di Web)</option>
                    <option value="Sembunyi" {{ old('status') === 'Sembunyi' ? 'selected' : '' }}>Sembunyi (Disembunyikan)</option>
                </select>
            </div>

            <!-- Deskripsi Produk (Anti-XSS Escaped) -->
            <div>
                <label for="description" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                    Deskripsi Produk (Anti-XSS Escaped)
                </label>
                <textarea name="description" id="description" rows="3" maxlength="1000"
                    placeholder="Espresso dengan susu segar dan sirup gula aren asli."
                    class="w-full px-3.5 py-2.5 rounded-lg border @error('description') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi Simpan & Batal -->
            <div class="pt-4 border-t border-[#e1e8e3] flex items-center gap-3">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                    Simpan Produk
                </button>
                <a href="{{ route('products.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-[#e1e8e3] bg-white text-[#141d1a] hover:bg-[#f5f8fa] text-xs font-semibold transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app', ['title' => 'Pengaturan Profil Toko - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Layar 7 Figcraft -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal UMKM</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Pengaturan Profil Toko</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Perbarui identitas, kontak WhatsApp, dan informasi etalase tokomu.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-[#61716a] hover:text-[#141d1a]">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Info_Box7 Figcraft Screen 7 -->
    <div class="bg-[#dcfce7] border border-[#bbf7d0] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-[#15803d]">
        <div class="flex items-center gap-2">
            <span class="text-sm">🌐</span>
            <span>Halaman Publikmu: <strong id="publicUrlText" class="font-mono underline">{{ url('/u/' . $organization->slug) }}</strong></span>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a id="viewPageBtn" href="{{ url('/u/' . $organization->slug) }}" target="_blank"
                class="px-3.5 py-1.5 rounded-lg border border-[#e1e8e3] bg-white text-[#141d1a] font-semibold hover:bg-[#f5f8fa] transition shadow-sm">
                Lihat Halaman ↗
            </a>
            <button type="button" onclick="copyPublicLink()" id="copyBtn"
                class="px-3.5 py-1.5 rounded-lg bg-[#16a34a] text-white font-semibold hover:bg-[#15803d] transition shadow-sm">
                <span id="copyText">📋 Salin Link</span>
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="font-semibold flex items-center gap-2 mb-1.5">
                <span>⚠️ Mohon periksa kembali formulir profil:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card_Form7 Figcraft Screen 7 -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl shadow-sm p-6 sm:p-8">
        <form action="{{ route('umkm.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Kolom Kiri -->
                <div class="space-y-5">
                    <!-- Nama UMKM -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Nama UMKM <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $organization->name) }}" required
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('name') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug Alamat Website -->
                    <div>
                        <label for="slug" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Slug Alamat Website *
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3.5 rounded-l-lg border border-r-0 border-[#e1e8e3] bg-[#f5f8fa] text-[#61716a] text-xs select-none">
                                {{ url('/u') }}/
                            </span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $organization->slug) }}" required
                                class="flex-1 min-w-0 block w-full px-3.5 py-2.5 rounded-none rounded-r-lg border @error('slug') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#16a34a] font-medium text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        </div>
                        <p class="text-xs text-[#b45309] mt-1.5 flex items-center gap-1">
                            <span>⚠️ Mengubah slug akan mengubah link halaman publik tokomu.</span>
                        </p>
                        @error('slug')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi Singkat Usaha -->
                    <div>
                        <label for="description" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Deskripsi Singkat Usaha
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('description') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">{{ old('description', $organization->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bidang Usaha -->
                    <div>
                        <label for="category" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Bidang Usaha <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" id="category" required
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('category') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                            @php
                                $categories = ['Kuliner', 'Fashion', 'Kerajinan', 'Jasa', 'Pertanian', 'Toko Kelontong', 'Lainnya'];
                            @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $organization->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-5">
                    <!-- Alamat Fisik Toko -->
                    <div>
                        <label for="address" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Alamat Fisik Toko
                        </label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('address') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">{{ old('address', $organization->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Nomor WhatsApp (Untuk Tombol Chat) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $organization->phone) }}" required
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('phone') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('phone')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Operasional -->
                    <div>
                        <label for="opening_hours" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Jam Operasional (Teks)
                        </label>
                        <input type="text" name="opening_hours" id="opening_hours" value="{{ old('opening_hours', $organization->opening_hours) }}"
                            placeholder="Senin - Sabtu: 08.00 - 21.00 WIB"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('opening_hours') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('opening_hours')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Kontak Admin -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Email Kontak Admin <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('email') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Sampul / Banner URL -->
                    <div>
                        <label for="cover_url" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Foto Sampul / Banner (URL Gambar)
                        </label>
                        <input type="url" name="cover_url" id="cover_url" value="{{ old('cover_url') }}"
                            placeholder="https://images.unsplash.com/photo-..."
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('cover_url') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Simpan & Batal Figcraft Screen 7 -->
            <div class="pt-6 border-t border-[#e1e8e3] flex items-center gap-3">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('dashboard') }}"
                    class="px-5 py-2.5 rounded-xl border border-[#e1e8e3] bg-white text-[#141d1a] hover:bg-[#f5f8fa] text-xs font-semibold transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const baseUrl = "{{ url('/u') }}";
    const slugInput = document.getElementById('slug');
    const publicUrlText = document.getElementById('publicUrlText');
    const viewPageBtn = document.getElementById('viewPageBtn');

    slugInput.addEventListener('input', function() {
        const cleanSlug = slugInput.value.trim().toLowerCase();
        const newUrl = baseUrl + '/' + cleanSlug;
        publicUrlText.textContent = newUrl;
        viewPageBtn.setAttribute('href', newUrl);
    });

    function copyPublicLink() {
        const urlToCopy = publicUrlText.textContent.trim();
        navigator.clipboard.writeText(urlToCopy).then(function() {
            const copyText = document.getElementById('copyText');
            copyText.textContent = '✓ Tersalin!';
            setTimeout(function() {
                copyText.textContent = '📋 Salin Link';
            }, 2000);
        });
    }
</script>
@endsection

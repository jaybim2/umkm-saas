@extends('layouts.app', ['title' => 'Pendaftaran Akun UMKM Baru - 🌿 UMKM Kita'])

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <!-- Card_Reg (w: 960px, rounded-2xl, border #e1e8e3, bg white) -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl shadow-sm p-8 sm:p-10">
        <!-- Title & Subtitle Figcraft Screen 3 -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-[#141d1a] tracking-tight">Pendaftaran Akun UMKM Baru</h1>
            <p class="text-sm text-[#61716a] mt-1.5">Mulai buat halaman etalase digital tokomu hanya dalam beberapa langkah mudah.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <div class="font-semibold flex items-center gap-2 mb-1.5">
                    <span>⚠️ Mohon periksa kembali formulir pendaftaran:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/register-umkm') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Kolom 1: Data Profil UMKM -->
                <div class="space-y-5">
                    <h2 class="text-base font-bold text-[#16a34a] border-b border-[#e1e8e3] pb-2 flex items-center gap-2">
                        <span>1. Data Profil UMKM</span>
                    </h2>

                    <!-- Nama UMKM -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Nama UMKM <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Toko Kopi ABC"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('name') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('name')
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
                            <option value="">-- Pilih Bidang Usaha --</option>
                            @php
                                $categories = ['Kuliner', 'Fashion', 'Kerajinan', 'Jasa', 'Pertanian', 'Toko Kelontong', 'Lainnya'];
                            @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug Alamat Website -->
                    <div>
                        <label for="slug" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Slug Alamat Website (Unik) <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3.5 rounded-l-lg border border-r-0 border-[#e1e8e3] bg-[#f5f8fa] text-[#61716a] text-xs select-none">
                                {{ url('/u') }}/
                            </span>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                placeholder="toko-kopi-abc"
                                class="flex-1 min-w-0 block w-full px-3.5 py-2.5 rounded-none rounded-r-lg border @error('slug') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#16a34a] font-medium text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        </div>
                        <p class="text-[11px] text-[#61716a] mt-1.5">Link publik: {{ url('/u') }}/<span id="slug-preview" class="text-[#16a34a] font-mono">toko-kopi-abc</span></p>
                        @error('slug')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp / Kontak -->
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Nomor WhatsApp / Kontak <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            placeholder="081234567890"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('phone') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('phone')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Usaha -->
                    <div>
                        <label for="address" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Alamat Usaha / Lokasi Toko
                        </label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                            placeholder="Jl. Malioboro No. 45, Danurejan, Kota Yogyakarta"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('address') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    </div>
                </div>

                <!-- Kolom 2: Data Akun Admin UMKM -->
                <div class="space-y-5 lg:border-l lg:border-[#e1e8e3] lg:pl-12">
                    <h2 class="text-base font-bold text-[#16a34a] border-b border-[#e1e8e3] pb-2 flex items-center gap-2">
                        <span>2. Data Akun Admin UMKM</span>
                    </h2>

                    <!-- Nama Lengkap Pengelola -->
                    <div>
                        <label for="admin_name" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Nama Lengkap Pengelola <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" required
                            placeholder="Budi Santoso"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('admin_name') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('admin_name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Login -->
                    <div>
                        <label for="admin_email" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Email Login <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required
                            placeholder="budi@tokokopiabc.com"
                            class="w-full px-3.5 py-2.5 rounded-lg border @error('admin_email') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                        @error('admin_email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-[#141d1a] uppercase tracking-wider mb-1.5">
                            Kata Sandi (Min. 8 Karakter) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                placeholder="••••••••••••"
                                class="w-full px-3.5 py-2.5 pr-10 rounded-lg border @error('password') border-rose-300 bg-rose-50/50 @else border-[#e1e8e3] bg-[#fcfdfc] @enderror text-[#141d1a] text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                            <button type="button" id="toggleRegPasswordBtn" onclick="toggleRegPassword()"
                                class="absolute right-2.5 top-2.5 p-1 rounded-md text-slate-400 hover:text-[#16a34a] hover:bg-slate-100 transition focus:outline-none cursor-pointer"
                                title="Tampilkan / Sembunyikan Kata Sandi" aria-label="Tampilkan atau sembunyikan kata sandi">
                                <svg id="regEyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="regEyeOffIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-[11px] text-[#61716a] mt-1">Gunakan kombinasi huruf dan angka.</p>
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Checkbox Pernyataan Sah -->
                    <div class="pt-2">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" name="agree" required checked
                                class="mt-0.5 w-4 h-4 rounded border-[#e1e8e3] text-[#16a34a] focus:ring-[#16a34a]">
                            <span class="text-xs text-[#141d1a] leading-tight">
                                Saya menyatakan bahwa data yang diisi benar dan sah.
                            </span>
                        </label>
                    </div>

                    <!-- Tombol Daftar UMKM -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3 px-6 rounded-xl bg-[#16a34a] text-white font-semibold text-sm hover:bg-[#15803d] shadow-sm hover:shadow transition focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:ring-offset-2 text-center">
                            Daftar UMKM
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom: Sudah punya akun? Masuk ke Portal -->
            <div class="mt-10 pt-6 border-t border-[#e1e8e3] text-center">
                <p class="text-xs text-[#61716a]">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-[#16a34a] hover:underline ml-1">
                        Masuk ke Portal
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const slugPreview = document.getElementById('slug-preview');
    let userEditedSlug = false;

    slugInput.addEventListener('input', function() {
        userEditedSlug = true;
        slugPreview.textContent = this.value || 'toko-kopi-abc';
    });

    nameInput.addEventListener('input', function() {
        if (!userEditedSlug) {
            const slug = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            slugPreview.textContent = slug || 'toko-kopi-abc';
        }
    });

    function toggleRegPassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('regEyeIcon');
        const eyeOffIcon = document.getElementById('regEyeOffIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
</script>
@endsection

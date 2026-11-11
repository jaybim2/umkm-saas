<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth bg-[#f9fbf7]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌿 UMKM Kita - Website UMKM Rapi, Tanpa Bangun dari Nol</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            color: #141d1a;
            background-color: #f9fbf7;
        }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased selection:bg-[#16a34a] selection:text-white">

    <!-- Header Pill Melayang (Header_Pill Figcraft Layar 1) -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-6">
        <header class="bg-white border border-[#e1e8e3] rounded-full px-6 sm:px-8 py-3.5 flex items-center justify-between shadow-xs">
            <!-- Logo_Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span class="text-xl font-bold tracking-tight text-[#16a34a] flex items-center gap-1.5">
                    <span>🌿</span> UMKM Kita
                </span>
            </a>

            <!-- Menu_Navigasi -->
            <nav class="hidden md:flex items-center gap-8 text-sm">
                <a href="#" class="font-semibold text-[#141d1a] hover:text-[#16a34a] transition">Beranda</a>
                <a href="#cara-kerja" class="text-[#61716a] hover:text-[#141d1a] transition">Cara Kerja</a>
                <a href="#fitur" class="text-[#61716a] hover:text-[#141d1a] transition">Fitur</a>
                <a href="#contoh" class="text-[#61716a] hover:text-[#141d1a] transition">Contoh UMKM</a>
            </nav>

            <!-- Tombol Aksi Nav (Btn_Login_Nav & Btn_Reg_Nav) -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('login') }}"
                    class="px-5 py-2 rounded-full border border-[#e1e8e3] text-[#141d1a] hover:bg-[#f9fbf7] text-sm font-medium transition">
                    Masuk
                </a>
                <a href="{{ route('register-umkm') }}"
                    class="px-5 py-2 rounded-full bg-[#16a34a] hover:bg-[#15803d] text-white text-sm font-medium transition shadow-xs">
                    Daftar UMKM
                </a>
            </div>
        </header>
    </div>

    <!-- Main Content -->
    <main class="flex-1 space-y-16 sm:space-y-20 py-12">
        <!-- Hero Section (Hero_Tag_Bg, Hero_Title, Hero_Sub, Btn_Hero_Daftar, Btn_Hero_Sample) -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <!-- Hero_Tag -->
            <div class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#dcfce7] text-[#15803d] text-xs font-semibold">
                <span>✨</span>
                <span>Solusi Digitalisasi Usaha Lokal</span>
            </div>

            <!-- Hero_Title -->
            <h1 class="text-3xl sm:text-5xl font-bold text-[#141d1a] tracking-tight leading-tight max-w-4xl mx-auto">
                Website UMKM Rapi, Tanpa Bangun dari Nol
            </h1>

            <!-- Hero_Sub -->
            <p class="text-base sm:text-lg text-[#61716a] max-w-2xl mx-auto leading-relaxed">
                Daftar sebagai UMKM, lengkapi profil dan produk, bagikan link halamanmu kepada pelanggan dengan mudah.
            </p>

            <!-- Hero Buttons -->
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                <a href="{{ route('register-umkm') }}"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-[#16a34a] hover:bg-[#15803d] text-white font-semibold text-sm shadow-sm transition active:scale-95">
                    Daftar UMKM Sekarang
                </a>
                <a href="#contoh"
                    class="w-full sm:w-auto px-7 py-3.5 rounded-full bg-white hover:bg-slate-50 text-[#141d1a] font-medium text-sm border border-[#e1e8e3] transition">
                    Lihat Contoh Halaman ↗
                </a>
            </div>
        </section>

        <!-- Step Box (Step_Box Figcraft Layar 1: 01, 02, 03) -->
        <section id="cara-kerja" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 sm:p-8 shadow-xs">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-[#e1e8e3]">
                    <!-- Step 01 -->
                    <div class="flex items-start gap-4 pt-4 md:pt-0">
                        <span class="text-2xl font-bold text-[#16a34a] shrink-0 font-mono">01</span>
                        <div>
                            <h3 class="font-semibold text-[#141d1a] text-base">Daftar Akun UMKM</h3>
                            <p class="text-xs text-[#61716a] mt-1 leading-relaxed">
                                Isi data usaha dan dapatkan slug unik website Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Step 02 -->
                    <div class="flex items-start gap-4 pt-4 md:pt-0 md:pl-6">
                        <span class="text-2xl font-bold text-[#16a34a] shrink-0 font-mono">02</span>
                        <div>
                            <h3 class="font-semibold text-[#141d1a] text-base">Lengkapi Profil &amp; Produk</h3>
                            <p class="text-xs text-[#61716a] mt-1 leading-relaxed">
                                Unggah foto produk dan atur harga Rp di etalase Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Step 03 -->
                    <div class="flex items-start gap-4 pt-4 md:pt-0 md:pl-6">
                        <span class="text-2xl font-bold text-[#16a34a] shrink-0 font-mono">03</span>
                        <div>
                            <h3 class="font-semibold text-[#141d1a] text-base">Bagikan Link Halaman</h3>
                            <p class="text-xs text-[#61716a] mt-1 leading-relaxed">
                                Terima pesan dan pertanyaan pengunjung langsung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fitur & Showcase Cards (Card_F1, Card_F2, Card_F3, Card_F4 Figcraft Layar 1) -->
        <section id="fitur" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card_F1 -->
                <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="text-2xl">🎨</div>
                        <h3 class="font-semibold text-[#141d1a] text-base">Website 1 Template Siap Pakai</h3>
                        <p class="text-xs text-[#61716a] leading-relaxed">
                            Desain responsif, elegan, dan otomatis menyesuaikan data tokomu.
                        </p>
                    </div>
                </div>

                <!-- Card_F2 -->
                <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="text-2xl">📦</div>
                        <h3 class="font-semibold text-[#141d1a] text-base">Kelola Produk Mandiri</h3>
                        <p class="text-xs text-[#61716a] leading-relaxed">
                            Atur status tampil/sembunyi dan kategorisasi produk sesukamu.
                        </p>
                    </div>
                </div>

                <!-- Card_F3 -->
                <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="text-2xl">💬</div>
                        <h3 class="font-semibold text-[#141d1a] text-base">Pertanyaan Terpantau</h3>
                        <p class="text-xs text-[#61716a] leading-relaxed">
                            Form pertanyaan pengunjung langsung masuk ke dashboard tokomu.
                        </p>
                    </div>
                </div>

                <!-- Card_F4: Showcase Contoh Toko ABC (Sesuai Desain Figcraft) -->
                <div id="contoh" class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="inline-block px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-[11px] font-medium">
                            Contoh UMKM
                        </div>
                        <h3 class="font-bold text-[#141d1a] text-base">Toko Kopi ABC (Kuliner)</h3>
                        <p class="text-xs font-mono text-[#16a34a] font-medium">
                            platformkamu.com/u/toko-abc
                        </p>
                    </div>

                    <div class="pt-2">
                        <a href="{{ url('/u/toko-abc') }}" target="_blank"
                            class="inline-flex items-center justify-center w-full px-3.5 py-2 rounded-lg bg-[#dcfce7] hover:bg-[#bbf7d0] text-[#15803d] text-xs font-semibold transition">
                            <span>Lihat Halaman Contoh ↗</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Garis Rapi (Ftr_Line, Ftr_Copy, Ftr_Links Figcraft Layar 1) -->
    <footer class="mt-auto border-t border-[#e1e8e3] py-8 text-xs text-[#61716a]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p>&copy; {{ date('Y') }} Platform UMKM Kita &bull; Solusi Katalog &amp; Profil Usaha Mandiri</p>
                <p class="text-[11px] text-[#61716a]/70 mt-1">Kontak Vendor: vendor@platformkamu.test</p>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-xs">
                <a href="#" class="hover:text-[#16a34a] transition">Beranda</a>
                <span>&bull;</span>
                <a href="{{ route('login') }}" class="hover:text-[#16a34a] transition">Masuk</a>
                <span>&bull;</span>
                <a href="{{ route('register-umkm') }}" class="hover:text-[#16a34a] transition">Daftar UMKM</a>
                <span>&bull;</span>
                <a href="{{ url('/u/toko-abc') }}" class="hover:text-[#16a34a] transition">Contoh Halaman</a>
            </div>
        </div>
    </footer>

</body>
</html>

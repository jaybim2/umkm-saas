<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth bg-[#f9fbf7]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $organization->name }} — Profil &amp; Katalog Resmi</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-full flex flex-col text-[#141d1a] antialiased bg-[#f9fbf7]">
    @php
        $cleanPhone = preg_replace('/[^0-9]/', '', $organization->phone ?? '');
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $waGeneralUrl = $cleanPhone ? 'https://wa.me/' . $cleanPhone . '?text=' . urlencode('Halo ' . $organization->name . ', saya ingin bertanya mengenai produk dan layanan Anda.') : '#';
    @endphp

    <!-- Header H11 Figcraft (Floating Container) -->
    <header class="max-w-6xl mx-auto w-full px-4 pt-4 sm:pt-6 sticky top-0 z-40">
        <div class="bg-white border border-[#e1e8e3] rounded-2xl h-16 px-6 flex items-center justify-between shadow-sm">
            <!-- Brand Logo & Nama -->
            <a href="{{ url('/u/' . $organization->slug) }}" class="flex items-center gap-2.5">
                <span class="text-xl">☕</span>
                <span class="font-bold text-base sm:text-lg text-[#141d1a] tracking-tight">
                    {{ $organization->name }}
                </span>
            </a>

            <!-- Menu Navigasi Layar 11 Figcraft -->
            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-[#61716a]">
                <a href="#profil" class="hover:text-[#16a34a] transition">Profil</a>
                <a href="#produk" class="hover:text-[#16a34a] transition">Produk</a>
                <a href="#kontak" class="hover:text-[#16a34a] transition">Kontak &amp; Lokasi</a>
            </nav>

            <!-- CTA Chat WhatsApp Figcraft (H11_WA) -->
            <div>
                @if($cleanPhone)
                    <a href="{{ $waGeneralUrl }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                        <span>💬 Chat WhatsApp</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    @if(!$organization->is_active)
        <!-- Tampilan Jika UMKM Nonaktif -->
        <main class="flex-1 flex items-center justify-center py-16 px-4">
            <div class="max-w-md w-full bg-white border border-[#e1e8e3] rounded-3xl p-8 text-center shadow-sm space-y-4">
                <span class="text-4xl">⚠️</span>
                <h2 class="text-xl font-bold text-[#141d1a]">Halaman UMKM Ini Sedang Tidak Aktif</h2>
                <p class="text-xs text-[#61716a] leading-relaxed">
                    Halaman profil dan katalog untuk <strong>{{ $organization->name }}</strong> sedang dinonaktifkan sementara oleh pengelola.
                </p>
                <div class="pt-2">
                    <a href="{{ url('/') }}" class="text-xs font-semibold text-[#16a34a] hover:underline">
                        &larr; Kembali ke Beranda Platform
                    </a>
                </div>
            </div>
        </main>
    @else
        <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6 space-y-6">
            <!-- Hero Banner Layar 11 Figcraft (Hero11_Box) -->
            <section class="bg-white border border-[#e1e8e3] rounded-2xl p-6 sm:p-10 shadow-sm relative overflow-hidden">
                <div class="max-w-3xl space-y-3 relative z-10">
                    <!-- Badge Kategori Usaha -->
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-[#dcfce7] text-[#15803d] text-xs font-bold">
                        <span>{{ $organization->category ?? 'Kuliner Pilihan' }}</span>
                    </div>

                    <!-- Title Hero -->
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-[#141d1a] tracking-tight">
                        {{ $organization->name }} @if($organization->address) — {{ Str::limit($organization->address, 30) }} @endif
                    </h1>

                    <!-- Deskripsi Hero -->
                    <p class="text-xs sm:text-sm text-[#61716a] leading-relaxed">
                        {{ $organization->description ?? 'Menyajikan produk dan layanan terbaik langsung kepada Anda. Pesan langsung atau hubungi kontak kami.' }}
                    </p>

                    <!-- Tombol Lihat Menu Produk -->
                    <div class="pt-2">
                        <a href="#produk"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold shadow-sm transition">
                            <span>Lihat Menu Produk ↓</span>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Notifikasi Pengiriman Pertanyaan -->
            @if(session('inquiry_success'))
                <div class="p-4 rounded-xl bg-[#dcfce7] border border-[#bbf7d0] text-xs text-[#15803d] flex items-center gap-2">
                    <span class="text-base">✅</span>
                    <span><strong>Pesan Terkirim:</strong> {{ session('inquiry_success') }}</span>
                </div>
            @endif

            @if(session('inquiry_error'))
                <div class="p-4 rounded-xl bg-[#fee2e2] border border-[#fca5a5] text-xs text-[#b91c1c] flex items-center gap-2">
                    <span class="text-base">⚠️</span>
                    <span><strong>Pengiriman Ditolak:</strong> {{ session('inquiry_error') }}</span>
                </div>
            @endif

            <!-- 2 Kolom Layout Figcraft Layar 11 (Kiri: Profil & Tanya, Kanan: Katalog) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Kolom Kiri (380px proporsi / col-span-5): Profil Kedai & Tanya Langsung -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Box Profil & Informasi Kedai (Prof11_Box) -->
                    <div id="profil" class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-sm space-y-5">
                        <h2 class="text-base font-bold text-[#141d1a] pb-2 border-b border-[#e1e8e3] flex items-center gap-2">
                            <span>🏬</span>
                            <span>Profil &amp; Informasi Usaha</span>
                        </h2>

                        <!-- Alamat Lengkap -->
                        <div>
                            <span class="block text-xs font-bold text-[#16a34a] mb-1">📍 Alamat Lengkap</span>
                            <p class="text-xs text-[#141d1a] leading-relaxed">
                                {{ $organization->address ?? 'Alamat belum dicantumkan' }}
                            </p>
                        </div>

                        <!-- Jam Operasional -->
                        <div>
                            <span class="block text-xs font-bold text-[#16a34a] mb-1">🕒 Jam Operasional</span>
                            <p class="text-xs text-[#141d1a] leading-relaxed">
                                {{ $organization->opening_hours ?? 'Senin - Sabtu: 08.00 - 21.00 WIB' }}
                            </p>
                        </div>

                        <!-- Kontak WhatsApp -->
                        <div id="kontak">
                            <span class="block text-xs font-bold text-[#16a34a] mb-1">📞 Kontak WhatsApp</span>
                            @if($organization->phone)
                                <p class="text-xs text-[#141d1a] font-mono">
                                    {{ $organization->phone }} (Respon Cepat)
                                </p>
                            @else
                                <p class="text-xs text-[#61716a]">Belum dicantumkan</p>
                            @endif
                        </div>

                        <!-- Email Usaha -->
                        <div>
                            <span class="block text-xs font-bold text-[#16a34a] mb-1">✉️ Email Usaha</span>
                            <p class="text-xs text-[#141d1a] font-mono">
                                {{ $organization->email ?? 'kontak@' . $organization->slug . '.com' }}
                            </p>
                        </div>
                    </div>

                    <!-- Form Tanya Langsung ke Penjual (Ask11) -->
                    <div id="pertanyaan" class="bg-white border border-[#e1e8e3] rounded-2xl p-6 shadow-sm space-y-4">
                        <div class="pb-2 border-b border-[#e1e8e3]">
                            <h2 class="text-base font-bold text-[#141d1a] flex items-center gap-2">
                                <span>💬</span>
                                <span>Tanya Langsung ke Penjual</span>
                            </h2>
                            <p class="text-[11px] text-[#61716a] mt-0.5">Punya pertanyaan seputar ketersediaan stok atau pesanan?</p>
                        </div>

                        <form method="POST" action="{{ route('public.umkm.inquiry', $organization->slug) }}" class="space-y-3">
                            @csrf

                            <div>
                                <input type="text" name="visitor_name" required maxlength="150"
                                    value="{{ old('visitor_name') }}"
                                    placeholder="Nama Anda *"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                                @error('visitor_name')
                                    <p class="text-[#b91c1c] text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <input type="text" name="visitor_contact" maxlength="100"
                                    value="{{ old('visitor_contact') }}"
                                    placeholder="Nomor WA / Email Anda (Opsional)"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                            </div>

                            <div>
                                <textarea name="message" rows="3" required maxlength="2000"
                                    placeholder="Pesan pertanyaan... *"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-[#b91c1c] text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold transition shadow-sm">
                                Kirim Pertanyaan 🚀
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Kolom Kanan (790px proporsi / col-span-7): Katalog Produk & Menu (Prod11_Box) -->
                <div id="produk" class="lg:col-span-7 bg-white border border-[#e1e8e3] rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
                    <!-- Header Box Produk & Chips Kategori Figcraft Layar 11 -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-[#e1e8e3]">
                        <h2 class="text-base font-bold text-[#141d1a] flex items-center gap-2">
                            <span>📦</span>
                            <span>Katalog Produk &amp; Menu</span>
                        </h2>

                        <!-- Chip Filters Figcraft -->
                        @if($categories->count() > 0)
                            <div class="flex flex-wrap items-center gap-1.5">
                                <button type="button" onclick="filterCategory('all', this)"
                                    class="category-chip active px-3 py-1 rounded-lg text-xs font-semibold bg-[#16a34a] text-white transition shadow-sm">
                                    Semua
                                </button>
                                @foreach($categories as $cat)
                                    <button type="button" onclick="filterCategory('{{ $cat }}', this)"
                                        class="category-chip px-3 py-1 rounded-lg text-xs font-semibold bg-[#f9fbf7] border border-[#e1e8e3] text-[#141d1a] hover:bg-[#e1e8e3] transition">
                                        {{ $cat }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Grid Kartu Produk Figcraft Layar 11 (PubCard) -->
                    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($products as $prod)
                            <div class="product-card bg-[#f9fbf7] border border-[#e1e8e3] rounded-2xl p-4 flex flex-col justify-between hover:border-[#16a34a] transition"
                                 data-category="{{ $prod->category }}">
                                <div>
                                    <!-- Header Kartu: Thumb & Badge -->
                                    <div class="flex items-start gap-3">
                                        @if($prod->photo_url)
                                            <img src="{{ $prod->photo_url }}" alt="{{ $prod->name }}"
                                                class="w-14 h-14 rounded-xl object-cover border border-[#e1e8e3] bg-white shrink-0"
                                                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-14 h-14 rounded-xl bg-[#dcfce7] text-[#15803d] flex items-center justify-center font-bold text-xl shrink-0\'>☕</div>';">
                                        @else
                                            <div class="w-14 h-14 rounded-xl bg-[#dcfce7] text-[#15803d] flex items-center justify-center font-bold text-xl shrink-0">
                                                ☕
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#dcfce7] text-[#15803d] mb-1">
                                                {{ $prod->category }}
                                            </span>
                                            <h3 class="font-bold text-xs sm:text-sm text-[#141d1a] leading-tight">
                                                {{ $prod->name }}
                                            </h3>
                                        </div>
                                    </div>

                                    @if($prod->description)
                                        <p class="text-[11px] text-[#61716a] mt-2 line-clamp-2">
                                            {{ $prod->description }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Bottom: Harga Green & Tombol Pesan WA Figcraft -->
                                <div class="mt-4 pt-3 border-t border-[#e1e8e3] flex items-center justify-between gap-2">
                                    <span class="text-sm font-bold text-[#16a34a] font-mono">
                                        Rp{{ number_format($prod->price, 0, ',', '.') }}
                                    </span>

                                    @if($cleanPhone)
                                        @php
                                            $waProductUrl = 'https://wa.me/' . $cleanPhone . '?text=' . urlencode('Halo ' . $organization->name . ', saya ingin memesan produk "' . $prod->name . '" (Rp ' . number_format($prod->price, 0, ',', '.') . '). Apakah stok tersedia?');
                                        @endphp
                                        <a href="{{ $waProductUrl }}" target="_blank"
                                            class="px-3 py-1.5 rounded-lg border border-[#e1e8e3] bg-white hover:bg-[#16a34a] hover:text-white text-[#141d1a] text-xs font-semibold transition">
                                            Pesan WA
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-xs text-[#61716a]">
                                <span class="text-2xl block mb-2">📦</span>
                                <p class="font-bold text-[#141d1a]">Katalog produk sedang disiapkan</p>
                                <p class="mt-0.5">Silakan hubungi kami via WhatsApp untuk daftar produk terbaru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    @endif

    <!-- Footer Publik Figcraft -->
    <footer class="mt-12 py-6 border-t border-[#e1e8e3] text-center text-xs text-[#61716a]">
        <p>🌿 Ditenagai oleh platform multi-tenant <strong>UMKM Kita</strong> &bull; Privasi &amp; Keamanan Data Terjamin</p>
    </footer>

    <!-- Filter JS for Category Chips -->
    <script>
        function filterCategory(category, button) {
            document.querySelectorAll('.category-chip').forEach(btn => {
                btn.classList.remove('bg-[#16a34a]', 'text-white', 'shadow-sm');
                btn.classList.add('bg-[#f9fbf7]', 'border', 'border-[#e1e8e3]', 'text-[#141d1a]');
            });
            button.classList.remove('bg-[#f9fbf7]', 'border', 'border-[#e1e8e3]', 'text-[#141d1a]');
            button.classList.add('bg-[#16a34a]', 'text-white', 'shadow-sm');

            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>

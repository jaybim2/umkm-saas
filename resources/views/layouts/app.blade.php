<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? '🌿 UMKM Kita' }} - Platform Profil &amp; Katalog UMKM</title>
    
    <!-- Tailwind CSS CDN dengan konfigurasi warna tema Figcraft asli -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#22c55e',
                            600: '#16a34a', // Warna brand utama Figcraft
                            700: '#15803d',
                            800: '#166534',
                        },
                        surface: {
                            public: '#f9fbf7',   // Background publik Figcraft
                            app: '#f5f8fa',      // Background dashboard Figcraft
                            sidebar: '#14211d',  // Background sidebar gelap Figcraft
                            sidebarActive: '#203830', // Nav aktif sidebar
                            sidebarBorder: '#293833', // Border divider sidebar
                            cardBorder: '#e1e8e3',    // Border kartu Figcraft
                        },
                        ink: {
                            primary: '#141d1a',   // Teks judul & isi utama Figcraft
                            muted: '#61716a',     // Teks deskripsi / muted Figcraft
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
            color: #141d1a;
        }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased selection:bg-brand-600 selection:text-white {{ Auth::check() ? 'bg-surface-app' : 'bg-surface-public' }}">

    @auth
        <!-- ============================================================== -->
        <!-- LAYOUT DASHBOARD DENGAN SIDEBAR ELEGAN (FIGCRAFT LAYAR 4-10)   -->
        <!-- ============================================================== -->
        <div class="flex min-h-screen">
            <!-- Sidebar Kiri Permanen (240px) -->
            <aside class="w-60 bg-surface-sidebar text-white flex-shrink-0 flex flex-col justify-between hidden md:flex border-r border-surface-sidebarBorder">
                <div>
                    <!-- Brand Header Sidebar -->
                    <div class="px-6 py-6 border-b border-surface-sidebarBorder">
                        <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                            <span class="text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                                <span class="text-brand-500">🌿</span> UMKM Kita
                            </span>
                        </a>
                        <span class="inline-block mt-1 text-[11px] font-medium text-ink-muted uppercase tracking-wider">
                            {{ Auth::user()->isVendor() ? 'Portal VENDOR' : 'Portal UMKM' }}
                        </span>
                    </div>

                    <!-- Navigasi Menu Sidebar -->
                    <nav class="px-3 py-5 space-y-1 text-sm font-medium">
                        @if(Auth::user()->isVendor())
                            <!-- Menu Navigasi Vendor (Layar 4 & 5 Figcraft) -->
                            <a href="{{ route('vendor.dashboard') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition relative {{ request()->routeIs('vendor.dashboard') ? 'bg-surface-sidebarActive text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                @if(request()->routeIs('vendor.dashboard'))
                                    <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-600 rounded-r"></span>
                                @endif
                                <span class="text-base">📊</span>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('vendor.umkm.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition relative {{ request()->routeIs('vendor.umkm.*') ? 'bg-surface-sidebarActive text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                @if(request()->routeIs('vendor.umkm.*'))
                                    <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-600 rounded-r"></span>
                                @endif
                                <span class="text-base">🏪</span>
                                <span>Kelola UMKM</span>
                            </a>
                        @else
                            <!-- Menu Navigasi Admin UMKM (Layar 6, 7, 8, 9, 10) -->
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition relative {{ request()->is('dashboard') ? 'bg-surface-sidebarActive text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                @if(request()->is('dashboard'))
                                    <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-600 rounded-r"></span>
                                @endif
                                <span class="text-base">📊</span>
                                <span>Dashboard</span>
                            </a>

                            <a href="{{ route('umkm.profile.edit') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition relative {{ request()->is('dashboard/profil*') ? 'bg-surface-sidebarActive text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                @if(request()->is('dashboard/profil*'))
                                    <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-600 rounded-r"></span>
                                @endif
                                <span class="text-base">🏬</span>
                                <span>Profil UMKM</span>
                            </a>

                            <a href="{{ route('products.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition relative {{ request()->is('dashboard/produk*') ? 'bg-surface-sidebarActive text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                @if(request()->is('dashboard/produk*'))
                                    <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-600 rounded-r"></span>
                                @endif
                                <span class="text-base">📦</span>
                                <span>Produk</span>
                            </a>

                            <a href="{{ route('inquiries.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition relative {{ request()->is('dashboard/pertanyaan*') ? 'bg-surface-sidebarActive text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                @if(request()->is('dashboard/pertanyaan*'))
                                    <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-600 rounded-r"></span>
                                @endif
                                <span class="text-base">💬</span>
                                <span>Pertanyaan</span>
                            </a>

                            <a href="{{ route('reports.index') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition relative {{ request()->is('dashboard/laporan*') ? 'bg-surface-sidebarActive text-white font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                @if(request()->is('dashboard/laporan*'))
                                    <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-600 rounded-r"></span>
                                @endif
                                <span class="text-base">📈</span>
                                <span>Laporan</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Bagian Bawah Sidebar: User Info & Tombol Logout -->
                <div class="p-4 border-t border-surface-sidebarBorder space-y-3">
                    <div class="px-2">
                        <div class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-ink-muted truncate">
                            {{ Auth::user()->isVendor() ? 'Vendor Super Admin' : (Auth::user()->organization->name ?? 'Admin UMKM') }}
                        </div>
                    </div>

                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-[#24332e] hover:bg-rose-900/40 text-rose-400 hover:text-rose-300 text-xs font-medium transition">
                            <span>🚪</span>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Area Konten Utama Kanan -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Topbar Sederhana untuk Mobile & Quick Info -->
                <header class="bg-white border-b border-surface-cardBorder h-16 flex items-center justify-between px-4 sm:px-8">
                    <!-- Title atau Breadcrumb Ringkas -->
                    <div class="flex items-center gap-3">
                        <div class="md:hidden flex items-center gap-2">
                            <span class="text-lg">🌿</span>
                            <span class="font-bold text-sm text-ink-primary">UMKM Kita</span>
                        </div>
                        <div class="hidden md:block text-xs text-ink-muted font-medium">
                            @if(Auth::user()->isVendor())
                                Area Vendor Platform &bull; Mode Pemantauan Agregat
                            @else
                                {{ Auth::user()->organization->name ?? 'UMKM' }} &bull; Mode Pengelola
                            @endif
                        </div>
                    </div>

                    <!-- Shortcut Halaman Publik & Mobile Menu -->
                    <div class="flex items-center gap-3 text-xs">
                        @if(!Auth::user()->isVendor() && isset(Auth::user()->organization->slug))
                            <a href="{{ url('/u/' . Auth::user()->organization->slug) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-brand-50 text-brand-700 hover:bg-brand-100 border border-brand-200 font-semibold transition">
                                <span>Lihat Web Publik ↗</span>
                            </a>
                        @endif

                        <!-- Mobile Logout Form -->
                        <form action="{{ url('/logout') }}" method="POST" class="inline md:hidden">
                            @csrf
                            <button type="submit" class="px-2.5 py-1 rounded-md text-rose-600 text-xs font-semibold hover:bg-rose-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Flash Message Sukses & Error -->
                <div class="px-4 sm:px-8 pt-4">
                    @if(session('success'))
                        <div class="p-3.5 rounded-xl bg-brand-50 border border-brand-200 text-brand-800 text-xs font-medium flex items-center gap-2.5 shadow-xs">
                            <span class="text-base">✅</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center gap-2.5 shadow-xs">
                            <span class="text-base">⚠️</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Konten Halaman Dashboard -->
                <main class="flex-1 p-4 sm:p-8">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <!-- ============================================================== -->
        <!-- LAYOUT PUBLIK & OTENTIKASI (FIGCRAFT LAYAR 1, 2, 3)             -->
        <!-- ============================================================== -->
        <!-- Notifikasi Flash Message Publik -->
        @if(session('success'))
            <div class="max-w-4xl mx-auto px-4 mt-4 w-full">
                <div class="p-3.5 rounded-2xl bg-brand-50 border border-brand-200 text-brand-800 text-xs font-medium flex items-center gap-2.5 shadow-xs">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-4xl mx-auto px-4 mt-4 w-full">
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center gap-2.5 shadow-xs">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Konten Halaman Publik / Auth -->
        <main class="flex-1 flex flex-col justify-center">
            @yield('content')
        </main>
    @endauth

</body>
</html>

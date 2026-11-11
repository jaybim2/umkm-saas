@extends('layouts.app', ['title' => 'Masuk - 🌿 UMKM Kita'])

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center bg-[#f9fbf7]">
    <!-- Card_Login (Layar 2 Figcraft) -->
    <div class="max-w-[440px] w-full bg-white border border-[#e1e8e3] rounded-[20px] p-8 sm:p-10 shadow-xs space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xl font-bold text-[#16a34a]">
                <span>🌿</span>
                <span>UMKM Kita</span>
            </a>
            <h1 class="text-2xl font-bold text-[#141d1a] tracking-tight">
                Masuk ke Akun
            </h1>
            <p class="text-sm text-[#61716a]">
                Selamat datang kembali! Silakan isi kredensial Anda.
            </p>
        </div>

        <!-- Alert Rate Limiting & Error Validasi -->
        @if ($errors->has('email') && str_contains(strtolower($errors->first('email')), 'terlalu banyak'))
            <div class="p-3.5 rounded-[8px] bg-[#fee2e2] border border-[#f5b2b2] text-[#b91c1c] text-xs space-y-1">
                <div class="font-medium flex items-center gap-1.5">
                    <span>⚠️</span>
                    <span>Percobaan login gagal 5x. Akses dibatasi sementara.</span>
                </div>
                <div class="text-[11px] text-[#b91c1c]/90">
                    {{ $errors->first('email') }}
                </div>
            </div>
        @elseif ($errors->any())
            <div class="p-3 rounded-[8px] bg-[#fee2e2] border border-[#f5b2b2] text-[#b91c1c] text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Field Email -->
            <div>
                <label for="email" class="block text-xs font-medium text-[#141d1a] mb-1.5">
                    Alamat Email
                </label>
                <div class="relative">
                    <input type="email" name="email" id="email" required autocomplete="email"
                        value="{{ old('email') }}"
                        placeholder="admin@tokoabc.com"
                        class="w-full px-3.5 py-2.5 pr-10 rounded-[10px] bg-[#fcfdfc] border border-[#e1e8e3] text-sm text-[#141d1a] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <!-- Field Kata Sandi -->
            <div>
                <label for="password" class="block text-xs font-medium text-[#141d1a] mb-1.5">
                    Kata Sandi
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        placeholder="••••••••••••"
                        class="w-full px-3.5 py-2.5 pr-10 rounded-[10px] bg-[#fcfdfc] border border-[#e1e8e3] text-sm text-[#141d1a] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
                    <button type="button" id="togglePasswordBtn" onclick="togglePasswordVisibility()"
                        class="absolute right-2.5 top-2.5 p-1 rounded-md text-slate-400 hover:text-[#16a34a] hover:bg-slate-100 transition focus:outline-none cursor-pointer"
                        title="Tampilkan / Sembunyikan Kata Sandi" aria-label="Tampilkan atau sembunyikan kata sandi">
                        <!-- Eye Icon (Visible when password is hidden) -->
                        <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Off Icon (Visible when password is shown) -->
                        <svg id="eyeOffIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tombol Masuk (Btn_Submit Figcraft: fill #16a34a) -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3 rounded-[10px] bg-[#16a34a] hover:bg-[#15803d] text-white font-semibold text-sm shadow-xs transition active:scale-95">
                    Masuk
                </button>
            </div>
        </form>

        <!-- Divider & Link Daftar -->
        <div class="pt-2 border-t border-[#e1e8e3] text-center space-y-3">
            <p class="text-xs text-[#61716a]">
                Belum punya akun UMKM? 
                <a href="{{ route('register-umkm') }}" class="font-semibold text-[#16a34a] hover:underline">
                    Daftar Baru
                </a>
            </p>

            <!-- Note Peran Pengalihan -->
            <p class="text-[11px] text-[#61716a]/80">
                Vendor &rarr; Dashboard Vendor &bull; Admin &rarr; Dashboard UMKM
            </p>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');
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

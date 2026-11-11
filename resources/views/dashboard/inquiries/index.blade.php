@extends('layouts.app', ['title' => 'Kotak Masuk Pertanyaan Pengunjung - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Figcraft Screen 9 -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Portal UMKM</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Kotak Masuk Pertanyaan Pengunjung</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Tanggapi pesan pertanyaan calon pelanggan dari website etalasemu.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(isset($organization->slug))
                <a href="{{ url('/u/' . $organization->slug . '#pertanyaan') }}" target="_blank"
                    class="px-4 py-2 rounded-xl border border-[#e1e8e3] bg-white text-[#141d1a] hover:bg-[#f5f8fa] text-xs font-semibold shadow-sm transition">
                    Lihat Form Publik ↗
                </a>
            @endif
        </div>
    </div>

    <!-- Filter Pills Tab Figcraft Screen 9 -->
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('inquiries.index', array_filter(['q' => $filters['q'] ?? null])) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ empty($filters['status']) ? 'bg-[#16a34a] text-white shadow-sm' : 'bg-white border border-[#e1e8e3] text-[#61716a] hover:bg-[#f5f8fa]' }}">
            Semua ({{ $counts['all'] }})
        </a>
        <a href="{{ route('inquiries.index', array_filter(['status' => 'Baru', 'q' => $filters['q'] ?? null])) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ ($filters['status'] ?? '') === 'Baru' ? 'bg-[#fee2e2] text-[#b91c1c] border border-[#fca5a5] font-bold' : 'bg-white border border-[#e1e8e3] text-[#b91c1c] hover:bg-[#fee2e2]/40' }}">
            <span>🔴 Baru ({{ $counts['baru'] }})</span>
        </a>
        <a href="{{ route('inquiries.index', array_filter(['status' => 'Dibalas', 'q' => $filters['q'] ?? null])) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ ($filters['status'] ?? '') === 'Dibalas' ? 'bg-[#fef3c7] text-[#b45309] border border-[#fde68a] font-bold' : 'bg-white border border-[#e1e8e3] text-[#b45309] hover:bg-[#fef3c7]/40' }}">
            <span>🟡 Dibalas ({{ $counts['dibalas'] }})</span>
        </a>
        <a href="{{ route('inquiries.index', array_filter(['status' => 'Selesai', 'q' => $filters['q'] ?? null])) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 {{ ($filters['status'] ?? '') === 'Selesai' ? 'bg-[#dcfce7] text-[#15803d] border border-[#86efac] font-bold' : 'bg-white border border-[#e1e8e3] text-[#15803d] hover:bg-[#dcfce7]/40' }}">
            <span>🟢 Selesai ({{ $counts['selesai'] }})</span>
        </a>
    </div>

    <!-- Search Box Bar -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('inquiries.index') }}" class="flex items-center gap-3">
            @if(!empty($filters['status']))
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
            @endif
            <div class="flex-1">
                <input type="text" name="q" id="q" value="{{ $filters['q'] ?? '' }}"
                    placeholder="🔍 Ketik nama pengirim, kontak, atau isi pesan pertanyaan..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[#e1e8e3] bg-[#fcfdfc] text-xs text-[#141d1a] focus:outline-none focus:ring-2 focus:ring-[#16a34a] focus:border-[#16a34a] transition">
            </div>
            <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-semibold transition">
                Cari
            </button>
            @if(!empty($filters['q']) || !empty($filters['status']))
                <a href="{{ route('inquiries.index') }}" class="text-xs text-[#b91c1c] font-semibold hover:underline px-2">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Daftar Kartu Pertanyaan Figcraft Screen 9 (QCard) -->
    <div class="space-y-3">
        @forelse($inquiries as $inquiry)
            <a href="{{ route('inquiries.show', $inquiry) }}"
                class="block bg-white border border-[#e1e8e3] rounded-2xl p-5 shadow-sm hover:border-[#16a34a] hover:shadow transition group">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <h3 class="font-bold text-sm text-[#141d1a] group-hover:text-[#16a34a] transition">
                                {{ $inquiry->visitor_name }}
                            </h3>
                            <span class="text-xs text-[#61716a]">
                                &bull; {{ $inquiry->created_at->format('d M, H:i') }}
                            </span>
                        </div>
                        <p class="text-xs text-[#61716a] mt-1 line-clamp-2">
                            {{ $inquiry->message }}
                        </p>
                        @if($inquiry->visitor_contact)
                            <p class="text-[11px] text-[#16a34a] font-medium mt-1">
                                Kontak: {{ $inquiry->visitor_contact }}
                            </p>
                        @endif
                    </div>
                    <div class="shrink-0 flex items-center gap-3">
                        @if($inquiry->status === 'Baru')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#fee2e2] text-[#b91c1c]">
                                Baru
                            </span>
                        @elseif($inquiry->status === 'Dibalas')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#fef3c7] text-[#b45309]">
                                Dibalas
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#dcfce7] text-[#15803d]">
                                Selesai
                            </span>
                        @endif
                        <span class="text-xs text-[#16a34a] font-bold group-hover:translate-x-1 transition-transform">
                            Detail &rarr;
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white border border-[#e1e8e3] rounded-2xl p-12 text-center text-xs text-[#61716a]">
                <p class="font-semibold text-[#141d1a]">Tidak ada pertanyaan ditemukan.</p>
                <p class="mt-1">Pesan dari formulir etalase publik tokomu akan otomatis masuk ke sini.</p>
            </div>
        @endforelse

        @if($inquiries->hasPages())
            <div class="p-4 bg-white border border-[#e1e8e3] rounded-2xl">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

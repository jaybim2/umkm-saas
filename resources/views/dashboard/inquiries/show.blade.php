@extends('layouts.app', ['title' => 'Detail Pertanyaan: ' . $inquiry->visitor_name . ' - 🌿 UMKM Kita'])

@section('content')
<div class="space-y-6">
    <!-- Topbar Figcraft Screen 9 -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#dcfce7] text-[#15803d] text-xs font-semibold mb-2">
                <span>Kotak Masuk &bull; ID #{{ $inquiry->id }}</span>
            </div>
            <h1 class="text-2xl font-bold text-[#141d1a]">Detail Pesan Pengunjung</h1>
            <p class="text-xs text-[#61716a] mt-0.5">Pertanyaan dari {{ $inquiry->visitor_name }} melalui formulir kontak tokomu.</p>
        </div>
        <div>
            <a href="{{ route('inquiries.index') }}"
                class="px-4 py-2 rounded-xl border border-[#e1e8e3] bg-white text-[#141d1a] hover:bg-[#f5f8fa] text-xs font-semibold shadow-sm transition">
                &larr; Kembali ke Kotak Masuk
            </a>
        </div>
    </div>

    <!-- Main Card Detail Pesan Figcraft Screen 9 (Det_Box9) -->
    <div class="bg-white border border-[#e1e8e3] rounded-2xl shadow-sm p-6 sm:p-8 max-w-3xl space-y-6">
        <!-- Status Badge -->
        <div class="flex items-center justify-between pb-4 border-b border-[#e1e8e3]">
            <span class="text-xs font-semibold text-[#61716a]">Status Pertanyaan:</span>
            @if($inquiry->status === 'Baru')
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#fee2e2] text-[#b91c1c]">
                    Status: Baru
                </span>
            @elseif($inquiry->status === 'Dibalas')
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#fef3c7] text-[#b45309]">
                    Status: Dibalas
                </span>
            @else
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#dcfce7] text-[#15803d]">
                    Status: Selesai
                </span>
            @endif
        </div>

        <!-- Info Pengirim -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <span class="block text-xs font-semibold text-[#61716a] uppercase tracking-wider mb-1">Nama Pengunjung:</span>
                <p class="text-base font-bold text-[#141d1a]">{{ $inquiry->visitor_name }}</p>
            </div>

            <div>
                <span class="block text-xs font-semibold text-[#61716a] uppercase tracking-wider mb-1">Waktu Masuk:</span>
                <p class="text-xs font-medium text-[#141d1a]">{{ $inquiry->created_at->isoFormat('dddd, DD MMMM Y • HH:mm') }} WIB</p>
            </div>

            <div class="sm:col-span-2">
                <span class="block text-xs font-semibold text-[#61716a] uppercase tracking-wider mb-1">Kontak / WhatsApp Pengunjung:</span>
                @if($inquiry->visitor_contact)
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold font-mono text-[#16a34a]">{{ $inquiry->visitor_contact }}</span>
                        @php
                            $cleanContact = preg_replace('/[^0-9]/', '', $inquiry->visitor_contact);
                            if (str_starts_with($cleanContact, '0')) {
                                $cleanContact = '62' . substr($cleanContact, 1);
                            }
                        @endphp
                        @if(strlen($cleanContact) >= 8)
                            <a href="https://wa.me/{{ $cleanContact }}?text={{ urlencode('Halo ' . $inquiry->visitor_name . ', kami dari ' . ($organization->name ?? 'UMKM') . ' menindaklanjuti pesan Anda: ' . \Illuminate\Support\Str::limit($inquiry->message, 50)) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#dcfce7] text-[#15803d] hover:bg-[#bbf7d0] text-xs font-bold transition">
                                <span>💬 Balas via WhatsApp</span>
                            </a>
                        @endif
                    </div>
                @else
                    <span class="text-xs text-[#61716a] italic">Pengunjung tidak mencantumkan nomor kontak.</span>
                @endif
            </div>
        </div>

        <!-- Box Pesan (Det_Box_Msg) -->
        <div>
            <span class="block text-xs font-semibold text-[#61716a] uppercase tracking-wider mb-2">Isi Pesan Pertanyaan:</span>
            <div class="bg-[#f5f8fa] border border-[#e1e8e3] rounded-xl p-4 text-sm text-[#141d1a] leading-relaxed whitespace-pre-wrap">
{{ $inquiry->message }}
            </div>
        </div>

        <!-- Tombol Aksi Status & Hapus Figcraft Screen 9 -->
        <div class="pt-4 border-t border-[#e1e8e3] space-y-3">
            <span class="block text-xs font-semibold text-[#61716a] uppercase tracking-wider">Ubah Status Pertanyaan:</span>
            <div class="flex flex-wrap items-center gap-3">
                @if($inquiry->status !== 'Dibalas')
                    <form method="POST" action="{{ route('inquiries.update-status', $inquiry) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Dibalas">
                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-[#fef3c7] hover:bg-[#fde68a] text-[#b45309] text-xs font-bold transition">
                            ✓ Tandai Dibalas
                        </button>
                    </form>
                @endif

                @if($inquiry->status !== 'Selesai')
                    <form method="POST" action="{{ route('inquiries.update-status', $inquiry) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Selesai">
                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-bold transition shadow-sm">
                            ✓ Tandai Selesai
                        </button>
                    </form>
                @endif

                @if($inquiry->status !== 'Baru')
                    <form method="POST" action="{{ route('inquiries.update-status', $inquiry) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Baru">
                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl border border-[#e1e8e3] bg-white hover:bg-[#f5f8fa] text-[#61716a] text-xs font-semibold transition">
                            Buka Lagi (Status Baru)
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan pertanyaan ini?');" class="ml-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2.5 rounded-xl border border-[#fee2e2] text-[#b91c1c] hover:bg-[#fee2e2] text-xs font-semibold transition">
                        🗑️ Hapus Pesan (Spam)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Review Bimbingan Mahasiswa')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Daftar Bimbingan Mahasiswa</h2>
            <p class="text-xs text-slate-500 mt-1">Berikan catatan revisi, koreksi berkas, atau persetujuan (ACC) pada draft mahasiswa.</p>
        </div>
        <form action="{{ route('bimbingan.index') }}" method="GET" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">-- Semua Status --</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>⏳ Menunggu Review</option>
                <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>⚠️ Perlu Revisi</option>
                <option value="acc" {{ request('status') === 'acc' ? 'selected' : '' }}>✅ Sudah Di-ACC</option>
            </select>
        </form>
    </div>

    <!-- Bimbingan Cards -->
    <div class="space-y-4">
        @forelse($bimbingans as $b)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4" x-data="{ openReview: false }">
            
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-base text-slate-900">{{ $b->tugasAkhir->mahasiswa->nama_lengkap }}</h3>
                        <span class="text-xs text-slate-400 font-mono">({{ $b->tugasAkhir->mahasiswa->nim }})</span>
                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">{{ $b->bab }}</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-700">{{ $b->topik_bimbingan }}</p>
                    <p class="text-[11px] text-slate-400">Tanggal: {{ $b->tgl_bimbingan->format('d M Y') }} &bull; Judul TA: {{ $b->tugasAkhir->judul }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wide
                        {{ $b->status === 'acc' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $b->status === 'revisi' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $b->status === 'menunggu' ? 'bg-rose-100 text-rose-700' : '' }}
                    ">
                        {{ $b->status }}
                    </span>
                    <button @click="openReview = !openReview" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        <span x-show="!openReview"><i class="fa-solid fa-pen-to-square mr-1"></i> Beri Feedback</span>
                        <span x-show="openReview"><i class="fa-solid fa-xmark mr-1"></i> Tutup Form</span>
                    </button>
                </div>
            </div>

            <!-- Uraian & Draft Mahasiswa -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs space-y-2">
                <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">Uraian Mahasiswa:</span>
                <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $b->uraian_mahasiswa }}</p>
                @if($b->file_draft)
                <div class="pt-2">
                    <a href="{{ asset('storage/' . $b->file_draft) }}" target="_blank" class="inline-flex items-center text-blue-600 font-bold hover:underline">
                        <i class="fa-solid fa-download mr-1.5"></i> Unduh File Draft Mahasiswa
                    </a>
                </div>
                @endif
            </div>

            <!-- Current Feedback if exists -->
            @if($b->catatan_dosen && !$errors->any())
            <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 text-xs space-y-1">
                <span class="font-bold text-emerald-800 uppercase tracking-wider block text-[10px]">Feedback Anda Sebelumnya:</span>
                <p class="text-slate-800 whitespace-pre-line">{{ $b->catatan_dosen }}</p>
            </div>
            @endif

            <!-- Expandable Review Form -->
            <div x-show="openReview" x-cloak class="mt-4 pt-4 border-t border-slate-100">
                <form action="{{ route('bimbingan.feedback', $b->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Status Penilaian <span class="text-rose-500">*</span></label>
                        <div class="flex flex-wrap items-center gap-4 text-xs font-bold">
                            <label class="flex items-center gap-2 cursor-pointer p-2.5 rounded-xl bg-white border border-slate-200 text-emerald-700">
                                <input type="radio" name="status" value="acc" {{ $b->status === 'acc' ? 'checked' : '' }} required class="text-emerald-600">
                                <span>✅ Disetujui (ACC)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-2.5 rounded-xl bg-white border border-slate-200 text-amber-700">
                                <input type="radio" name="status" value="revisi" {{ $b->status === 'revisi' ? 'checked' : '' }} required class="text-amber-600">
                                <span>⚠️ Perlu Revisi</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700">
                                <input type="radio" name="status" value="menunggu" {{ $b->status === 'menunggu' ? 'checked' : '' }} required class="text-slate-600">
                                <span>⏳ Masih Ditinjau</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan & Koreksi untuk Mahasiswa <span class="text-rose-500">*</span></label>
                        <textarea name="catatan_dosen" rows="3" required placeholder="Tuliskan arahan perbaikan, bab selanjutnya, atau instruksi sidang..."
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">{{ old('catatan_dosen', $b->catatan_dosen) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload File Koreksi / Berkas Bertanda (Opsional)</label>
                        <input type="file" name="file_revisi_dosen" class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700">
                    </div>

                    <div class="text-right pt-2">
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
                            Simpan Feedback
                        </button>
                    </div>
                </form>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm text-slate-400 text-xs">
            <i class="fa-solid fa-folder-open text-4xl mb-2 text-slate-300"></i>
            <p>Tidak ada data bimbingan mahasiswa yang sesuai filter.</p>
        </div>
        @endforelse
    </div>

    @if($bimbingans->hasPages())
    <div class="px-6 py-4">
        {{ $bimbingans->links() }}
    </div>
    @endif

</div>
@endsection

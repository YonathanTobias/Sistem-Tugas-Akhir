@extends('layouts.app')

@section('title', 'Logbook Bimbingan Skripsi')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Buku Kendali Bimbingan (Logbook)</h2>
            <p class="text-xs text-slate-500 mt-1">Catat setiap riwayat bimbingan, revisi, dan persetujuan (ACC) dari dosen pembimbing.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('bimbingan.cetak-kartu', $ta->id) }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                <i class="fa-solid fa-print mr-1.5"></i> Cetak Kartu Bimbingan
            </a>
            <a href="{{ route('bimbingan.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/30 transition">
                <i class="fa-solid fa-plus-circle mr-1.5"></i> Catat Bimbingan Baru
            </a>
        </div>
    </div>

    <!-- Summary Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Total Sesi Bimbingan</span>
                <p class="text-xl font-extrabold text-slate-800 mt-0.5">{{ $bimbingans->count() }} Pertemuan</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Status ACC Dosen</span>
                <p class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ $totalAcc }} Sesi Di-ACC</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Perlu Revisi</span>
                <p class="text-xl font-extrabold text-amber-600 mt-0.5">{{ $bimbingans->where('status', 'revisi')->count() }} Catatan</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-pen-nib"></i>
            </div>
        </div>
    </div>

    <!-- Logbook Timeline / List -->
    <div class="space-y-4">
        @forelse($bimbingans as $b)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-4">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-700">
                        {{ $b->bab }}
                    </span>
                    <div>
                        <h3 class="font-extrabold text-sm text-slate-800">{{ $b->topik_bimbingan }}</h3>
                        <p class="text-xs text-slate-400">Pembimbing: <strong>{{ $b->dosen->nama_lengkap }}</strong> &bull; Tanggal: {{ $b->tgl_bimbingan->format('d F Y') }}</p>
                    </div>
                </div>

                <div>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wide
                        {{ $b->status === 'acc' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $b->status === 'revisi' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $b->status === 'menunggu' ? 'bg-slate-100 text-slate-700' : '' }}
                    ">
                        {{ $b->status === 'acc' ? '✅ DISETUJUI (ACC)' : ($b->status === 'revisi' ? '⚠️ PERLU REVISI' : '⏳ MENUNGGU RESPON') }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <!-- Uraian Mahasiswa -->
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100 space-y-2">
                    <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">Uraian Materi / Hasil Diskusi Mahasiswa:</span>
                    <p class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $b->uraian_mahasiswa }}</p>
                    @if($b->file_draft)
                    <div class="pt-2">
                        <a href="{{ asset('storage/' . $b->file_draft) }}" target="_blank" class="inline-flex items-center text-emerald-600 font-bold hover:underline">
                            <i class="fa-solid fa-paperclip mr-1"></i> File Draft Yang Diunggah
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Feedback Dosen -->
                <div class="p-4 rounded-2xl border {{ $b->status === 'acc' ? 'bg-emerald-50/40 border-emerald-100' : 'bg-amber-50/40 border-amber-100' }} space-y-2">
                    <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">Catatan & Arahan Dosen:</span>
                    @if($b->catatan_dosen)
                    <p class="text-slate-800 whitespace-pre-line leading-relaxed">{{ $b->catatan_dosen }}</p>
                    @if($b->file_revisi_dosen)
                    <div class="pt-2">
                        <a href="{{ asset('storage/' . $b->file_revisi_dosen) }}" target="_blank" class="inline-flex items-center text-sky-600 font-bold hover:underline">
                            <i class="fa-solid fa-file-arrow-down mr-1"></i> File Koreksi Dosen
                        </a>
                    </div>
                    @endif
                    @else
                    <p class="text-slate-400 italic">Dosen pembimbing belum memberikan catatan untuk sesi ini.</p>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm">
            <i class="fa-solid fa-book-open-reader text-4xl text-slate-300 mb-3"></i>
            <h3 class="font-bold text-slate-800 text-sm">Belum Ada Riwayat Bimbingan</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">Setiap kali Anda selesai konsultasi dengan pembimbing, catat uraian pembahasannya di sini.</p>
            <a href="{{ route('bimbingan.create') }}" class="px-5 py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-xs">
                Tambah Catatan Bimbingan
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection

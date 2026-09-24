@extends('layouts.app')

@section('title', 'Tugas Akhir Saya')

@section('content')
<div class="space-y-6">

    @if(!$ta)
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 text-center shadow-sm max-w-2xl mx-auto space-y-4">
        <div class="w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mx-auto">
            <i class="fa-solid fa-file-signature"></i>
        </div>
        <h2 class="text-xl font-black text-slate-800">Anda Belum Mengajukan Judul Tugas Akhir</h2>
        <p class="text-sm text-slate-500 leading-relaxed">
            Mulai langkah tugas akhir Anda dengan mengajukan judul, abstrak rencana penelitian, dan usulan dosen pembimbing.
        </p>
        <a href="{{ route('tugas-akhir.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl text-sm shadow-lg shadow-emerald-600/30 transition">
            <i class="fa-solid fa-plus-circle mr-2"></i> Ajukan Judul Sekarang
        </a>
    </div>
    @else

    <!-- Status Overview Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Pengajuan</span>
                <div class="mt-1 flex items-center gap-3">
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wide
                        {{ $ta->status === 'disetujui' || $ta->status === 'lulus_sidang' || $ta->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $ta->status === 'pengajuan' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $ta->status === 'revisi_judul' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $ta->status === 'bimbingan_skripsi' || $ta->status === 'bimbingan_proposal' ? 'bg-sky-100 text-sky-700' : '' }}
                        {{ $ta->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                    ">
                        <i class="fa-solid fa-circle text-[8px] mr-1.5"></i> {{ str_replace('_', ' ', $ta->status) }}
                    </span>
                    <span class="text-xs text-slate-400">Diajukan pada: {{ $ta->tgl_pengajuan ? $ta->tgl_pengajuan->format('d M Y') : '-' }}</span>
                </div>
            </div>
            @if(in_array($ta->status, ['revisi_judul', 'ditolak']))
            <a href="{{ route('tugas-akhir.create') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl text-xs shadow-md transition">
                <i class="fa-solid fa-rotate-left mr-1.5"></i> Perbaiki / Ajukan Ulang
            </a>
            @endif
        </div>

        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Judul Tugas Akhir</span>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1 leading-snug">{{ $ta->judul }}</h2>
            <div class="mt-2 text-xs text-emerald-700 font-semibold bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">
                Bidang Kajian: {{ $ta->bidang_kajian }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-bold text-slate-400 uppercase">Dosen Pembimbing Utama (1)</span>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $ta->pembimbing1 ? $ta->pembimbing1->nama_lengkap : 'Menunggu Plotting Prodi' }}</p>
                @if($ta->pembimbing1)
                <p class="text-xs text-slate-500">NIDN: {{ $ta->pembimbing1->nidn }} | {{ $ta->pembimbing1->no_hp ?? '-' }}</p>
                @endif
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-bold text-slate-400 uppercase">Dosen Pembimbing Pendamping (2)</span>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $ta->pembimbing2 ? $ta->pembimbing2->nama_lengkap : '-' }}</p>
                @if($ta->pembimbing2)
                <p class="text-xs text-slate-500">NIDN: {{ $ta->pembimbing2->nidn }} | {{ $ta->pembimbing2->no_hp ?? '-' }}</p>
                @endif
            </div>
        </div>

        <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Abstrak / Rencana Penelitian</h4>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $ta->abstrak }}
            </div>
        </div>

        @if($ta->catatan_prodi)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
            <strong class="font-bold block mb-1"><i class="fa-solid fa-message mr-1"></i> Catatan dari Koordinator Prodi:</strong>
            {{ $ta->catatan_prodi }}
        </div>
        @endif

        @if($ta->file_proposal)
        <div>
            <a href="{{ asset('storage/' . $ta->file_proposal) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition">
                <i class="fa-solid fa-file-pdf mr-2 text-rose-600"></i> Unduh Berkas Proposal Awal
            </a>
        </div>
        @endif
    </div>

    @endif

</div>
@endsection

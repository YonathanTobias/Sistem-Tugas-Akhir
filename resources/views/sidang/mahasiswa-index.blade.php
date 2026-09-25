@extends('layouts.app')

@section('title', 'Seminar Proposal & Sidang Skripsi')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Seminar & Sidang Tugas Akhir</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar seminar proposal (Sempro) atau sidang akhir setelah disetujui dosen pembimbing.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sidang.daftar', ['jenis' => 'sempro']) }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/20 transition">
                <i class="fa-solid fa-file-lines mr-1.5"></i> Daftar Sempro
            </a>
            <a href="{{ route('sidang.daftar', ['jenis' => 'sidang_akhir']) }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-600/30 transition">
                <i class="fa-solid fa-graduation-cap mr-1.5"></i> Daftar Sidang Akhir
            </a>
        </div>
    </div>

    <!-- Sidang History Cards -->
    <div class="space-y-4">
        @forelse($sidangs as $s)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-lg {{ $s->jenis === 'sempro' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                        {{ $s->jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Akhir Skripsi' }}
                    </span>
                    <h3 class="text-base font-extrabold text-slate-800 pt-1.5">{{ $s->tugasAkhir->judul }}</h3>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wide
                        {{ $s->status === 'lulus' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $s->status === 'dijadwalkan' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $s->status === 'menunggu_jadwal' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $s->status === 'revisi' ? 'bg-orange-100 text-orange-700' : '' }}
                    ">
                        {{ str_replace('_', ' ', $s->status) }}
                    </span>
                </div>
            </div>

            <!-- Schedule & Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Jadwal Pelaksanaan</span>
                    <p class="font-extrabold text-slate-800 mt-1">
                        {{ $s->tgl_sidang ? $s->tgl_sidang->format('l, d F Y') : 'Menunggu Penetapan Jadwal' }}
                    </p>
                    @if($s->jam_mulai)
                    <p class="text-slate-500 text-[11px] mt-0.5">{{ substr($s->jam_mulai, 0, 5) }} - {{ substr($s->jam_selesai, 0, 5) }} WIB</p>
                    @endif
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Ruangan Ujian</span>
                    <p class="font-extrabold text-slate-800 mt-1">{{ $s->ruangan ?? 'Akan ditentukan prodi' }}</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Dewan Penguji</span>
                    <p class="font-bold text-slate-800 mt-1 truncate">1. {{ $s->penguji1->nama_lengkap ?? 'Menunggu plotting' }}</p>
                    <p class="text-slate-600 mt-0.5 truncate">2. {{ $s->penguji2->nama_lengkap ?? '-' }}</p>
                </div>
            </div>

            @if($s->status === 'lulus')
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-xs flex items-center justify-between">
                <div>
                    <span class="font-bold text-emerald-800 block">Hasil Ujian: LULUS SIDANG 🎉</span>
                    <p class="text-slate-600 mt-0.5">{{ $s->berita_acara ?? 'Selamat atas kelulusan sidang Anda!' }}</p>
                </div>
                @if($s->nilai_akhir)
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Nilai Akhir</span>
                    <div class="text-lg font-black text-emerald-700">{{ number_format($s->nilai_akhir, 2) }} ({{ $s->grade_huruf }})</div>
                </div>
                @endif
            </div>
            @endif

        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm text-xs text-slate-400">
            <i class="fa-solid fa-calendar-xmark text-4xl mb-3 text-slate-300"></i>
            <h3 class="font-bold text-slate-800 text-sm">Belum Ada Riwayat Sidang</h3>
            <p class="text-slate-500 max-w-sm mx-auto mt-1">Setelah bimbingan Anda disetujui dosen, klik tombol daftar sempro atau sidang akhir di atas.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection

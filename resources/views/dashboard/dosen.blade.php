@extends('layouts.app')

@section('title', 'Dashboard Dosen Pembimbing')

@section('content')
<div class="space-y-6">

    <!-- Header Welcome -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 border border-blue-900/40">
        <div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-chalkboard-user text-xs text-blue-400"></i> Portal Dosen Pembimbing & Penguji
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-3 tracking-tight">{{ $dosen->nama_gelar }}</h2>
            <p class="text-slate-300 text-sm mt-1">NIDN: {{ $dosen->nidn ?? '-' }} | Bidang: {{ $dosen->bidang_keahlian ?? 'Umum' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('bimbingan.index') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition">
                <i class="fa-solid fa-comments mr-1.5"></i> Review Logbook ({{ $pendingLogbook->count() }})
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Bimbingan Aktif</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalBimbingan }} <span class="text-xs text-slate-400 font-normal">/ Kuota {{ $dosen->kuota_bimbingan }}</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Menunggu Review</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $pendingLogbook->count() }} Draft</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Agenda Ujian Sidang</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $jadwalSidang->count() }} Sidang</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
        </div>
    </div>

    <!-- Logbook Review Queue & Mahasiswa Bimbingan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Draft Bimbingan Perlu Respon Segera -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-base text-slate-800">Draft Bimbingan Menunggu Feedback</h3>
                    <p class="text-xs text-slate-400">Daftar mahasiswa yang baru saja mengunggah materi bimbingan</p>
                </div>
                <a href="{{ route('bimbingan.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Lihat Semua</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($pendingLogbook as $log)
                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 rounded-xl px-2 transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-slate-800">{{ $log->tugasAkhir->mahasiswa->nama_lengkap }}</span>
                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">{{ $log->bab }}</span>
                        </div>
                        <p class="text-xs text-slate-600 font-medium">{{ $log->topik_bimbingan }}</p>
                        <p class="text-[11px] text-slate-400">Diunggah: {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <a href="{{ route('bimbingan.index') }}" class="inline-flex items-center px-3.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-xl text-xs font-bold transition shadow-sm">
                            <i class="fa-solid fa-pen-to-square mr-1.5"></i> Beri Catatan / ACC
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-3xl mb-2"></i>
                    <p>Semua draft bimbingan mahasiswa telah direspon!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Jadwal Sidang Terdekat Dosen -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
            <h3 class="font-bold text-base text-slate-800 mb-4">Jadwal Sidang & Ujian</h3>

            <div class="space-y-3">
                @forelse($jadwalSidang as $s)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1.5 hover:border-blue-200 transition">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-blue-700 uppercase">{{ $s->jenis }}</span>
                        <span class="text-slate-500 font-semibold">{{ $s->tgl_sidang->format('d M Y') }}</span>
                    </div>
                    <p class="font-bold text-slate-800">{{ $s->tugasAkhir->mahasiswa->nama_lengkap }}</p>
                    <p class="text-slate-500 text-[11px] line-clamp-1">{{ $s->tugasAkhir->judul }}</p>
                    <div class="pt-1 flex items-center justify-between text-[11px]">
                        <span class="text-slate-500"><i class="fa-solid fa-location-dot mr-1"></i> {{ $s->ruangan }}</span>
                        <a href="{{ route('sidang.show', $s->id) }}" class="font-bold text-blue-600 hover:underline">Form Nilai &rarr;</a>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-6 text-center">Tidak ada jadwal sidang terdekat.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

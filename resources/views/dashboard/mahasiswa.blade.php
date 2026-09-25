@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="space-y-6">

    <!-- Profile & Status Card -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                Portal Akademik Mahasiswa
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $mahasiswa->nama_lengkap }}</h2>
            <p class="text-slate-300 text-sm">
                NIM: <span class="font-mono text-emerald-400 font-bold">{{ $mahasiswa->nim }}</span> | 
                Prodi: {{ $mahasiswa->prodi->nama_prodi ?? 'Teknik Informatika' }} | 
                IPK: <span class="text-emerald-300 font-bold">{{ number_format($mahasiswa->ipk, 2) }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if(!$ta)
            <a href="{{ route('tugas-akhir.create') }}" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl text-xs shadow-lg transition">
                <i class="fa-solid fa-plus-circle mr-1.5"></i> Ajukan Judul TA
            </a>
            @else
            <a href="{{ route('bimbingan.index') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl text-xs backdrop-blur-sm border border-white/10 transition">
                <i class="fa-solid fa-comments mr-1.5"></i> Logbook Bimbingan
            </a>
            @endif
        </div>
    </div>

    <!-- Stepper Tracker Progress Skripsi & Yudisium -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-6">Tahapan Perjalanan Tugas Akhir & Kelulusan</h3>
        
        @php
            $currentStep = 1;
            if ($ta) {
                if ($ta->status === 'pengajuan') $currentStep = 1;
                elseif (in_array($ta->status, ['disetujui', 'bimbingan_proposal'])) $currentStep = 2;
                elseif (in_array($ta->status, ['siap_sempro', 'lulus_sempro', 'bimbingan_skripsi'])) $currentStep = 3;
                elseif (in_array($ta->status, ['siap_sidang', 'lulus_sidang'])) $currentStep = 4;
                elseif ($ta->status === 'selesai' || ($pendaftaranYudisium && $pendaftaranYudisium->status === 'lulus')) $currentStep = 5;
            }
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
            
            <!-- Step 1 -->
            <div class="p-3.5 rounded-2xl border {{ $currentStep >= 1 ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 1 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">1</span>
                    <span class="text-xs font-bold">Pengajuan Judul</span>
                </div>
                <p class="text-[11px] {{ $currentStep >= 1 ? 'text-emerald-700' : 'text-slate-400' }}">Proposal & Review Pembimbing</p>
            </div>

            <!-- Step 2 -->
            <div class="p-3.5 rounded-2xl border {{ $currentStep >= 2 ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 2 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">2</span>
                    <span class="text-xs font-bold">Bimbingan Bab 1-3</span>
                </div>
                <p class="text-[11px] {{ $currentStep >= 2 ? 'text-emerald-700' : 'text-slate-400' }}">Logbook draft proposal</p>
            </div>

            <!-- Step 3 -->
            <div class="p-3.5 rounded-2xl border {{ $currentStep >= 3 ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 3 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">3</span>
                    <span class="text-xs font-bold">Seminar Proposal</span>
                </div>
                <p class="text-[11px] {{ $currentStep >= 3 ? 'text-emerald-700' : 'text-slate-400' }}">Ujian Sempro & Revisi</p>
            </div>

            <!-- Step 4 -->
            <div class="p-3.5 rounded-2xl border {{ $currentStep >= 4 ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">4</span>
                    <span class="text-xs font-bold">Sidang Skripsi</span>
                </div>
                <p class="text-[11px] {{ $currentStep >= 4 ? 'text-emerald-700' : 'text-slate-400' }}">Ujian Akhir & Kelulusan</p>
            </div>

            <!-- Step 5 -->
            <div class="p-3.5 rounded-2xl border {{ $currentStep >= 5 ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-400' }}">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 5 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">5</span>
                    <span class="text-xs font-bold">Yudisium</span>
                </div>
                <p class="text-[11px] {{ $currentStep >= 5 ? 'text-emerald-700' : 'text-slate-400' }}">Bebas Tanggungan & Kelulusan</p>
            </div>

        </div>
    </div>

    <!-- Active TA Status & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Status Detail TA -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="font-bold text-base text-slate-800">Status Tugas Akhir Anda</h3>
                @if($ta)
                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase
                    {{ $ta->status === 'disetujui' || $ta->status === 'lulus_sidang' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700' }}">
                    {{ str_replace('_', ' ', $ta->status) }}
                </span>
                @endif
            </div>

            @if($ta)
            <div class="space-y-3">
                <div>
                    <span class="text-[11px] uppercase tracking-wider font-bold text-slate-400">Judul Penelitian</span>
                    <h4 class="text-base font-extrabold text-slate-800 mt-0.5 leading-snug">{{ $ta->judul }}</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] font-bold text-slate-400 uppercase">Dosen Pembimbing 1</span>
                        <p class="text-xs font-bold text-slate-800 mt-1">{{ $ta->pembimbing1->nama_lengkap ?? 'Belum Ditentukan' }}</p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] font-bold text-slate-400 uppercase">Dosen Pembimbing 2</span>
                        <p class="text-xs font-bold text-slate-800 mt-1">{{ $ta->pembimbing2->nama_lengkap ?? '-' }}</p>
                    </div>
                </div>

                <!-- Progress Bimbingan Bar -->
                <div class="pt-3">
                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                        <span class="text-slate-600">Progres ACC Bimbingan (Minimal {{ $minBimbingan }}x):</span>
                        <span class="text-emerald-600">{{ $totalBimbinganAcc }} / {{ $minBimbingan }} ACC</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ min(100, ($totalBimbinganAcc / $minBimbingan) * 100) }}%"></div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-10 space-y-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400 text-2xl">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Belum Mengajukan Judul Tugas Akhir</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Silakan ajukan judul proposal tugas akhir Anda untuk mendapatkan persetujuan dan plotting dosen pembimbing.</p>
                <a href="{{ route('tugas-akhir.create') }}" class="inline-block px-5 py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-xs shadow-md">
                    Ajukan Sekarang
                </a>
            </div>
            @endif
        </div>

        <!-- Right: Status Yudisium Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-base text-slate-800">Status Kelulusan Yudisium</h3>

            @if($pendaftaranYudisium)
            <div class="space-y-3 text-xs">
                <div class="p-4 rounded-2xl {{ $pendaftaranYudisium->status === 'lulus' ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-amber-50 border border-amber-200 text-amber-900' }}">
                    <div class="font-bold text-sm flex items-center justify-between">
                        <span>Hasil Yudisium:</span>
                        <span class="uppercase px-2 py-0.5 rounded-md font-extrabold {{ $pendaftaranYudisium->status === 'lulus' ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                            {{ $pendaftaranYudisium->status === 'lulus' ? 'Dinyatakan LULUS' : $pendaftaranYudisium->status }}
                        </span>
                    </div>
                    <p class="mt-2 text-[11px] font-semibold">{{ $pendaftaranYudisium->periodeYudisium->nama_periode }}</p>
                </div>

                @if($pendaftaranYudisium->status === 'lulus')
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Predikat:</span>
                        <strong class="text-emerald-700 font-bold uppercase">{{ $pendaftaranYudisium->predikat }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">IPK Kelulusan:</span>
                        <strong class="text-slate-800 font-bold">{{ number_format($pendaftaranYudisium->ipk_final, 2) }}</strong>
                    </div>
                    @if($pendaftaranYudisium->tgl_lulus)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tgl Kelulusan:</span>
                        <strong class="text-slate-800 font-bold">{{ $pendaftaranYudisium->tgl_lulus->translatedFormat('d F Y') }}</strong>
                    </div>
                    @endif
                </div>

                <a href="{{ route('yudisium.index') }}" class="block w-full text-center py-2.5 px-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow transition">
                    Lihat Rincian Yudisium &rarr;
                </a>
                @else
                <a href="{{ route('yudisium.index') }}" class="block w-full text-center py-2.5 px-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-xs transition">
                    Kelola Berkas Bebas Tanggungan &rarr;
                </a>
                @endif
            </div>
            @else
            <div class="text-center py-6 text-xs text-slate-400 space-y-2">
                <i class="fa-solid fa-award text-3xl text-slate-300"></i>
                <p>Pendaftaran yudisium dapat dilakukan setelah Anda menyelesaikan sidang akhir skripsi.</p>
            </div>
            @endif
        </div>

    </div>

</div>
@endsection

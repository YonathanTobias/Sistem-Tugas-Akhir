@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
@php
    // ==========================================
    // LOGIKA PERHITUNGAN PROGRESS & STEP TRACKER
    // ==========================================
    $stepStatus = [
        1 => 'upcoming',
        2 => 'upcoming',
        3 => 'upcoming',
        4 => 'upcoming',
        5 => 'upcoming',
    ];
    
    $overallProgress = 0;
    $nextActionTitle = "Ajukan Judul Tugas Akhir";
    $nextActionDesc = "Anda belum mengajukan proposal tugas akhir. Ajukan judul dan topik penelitian Anda untuk mendapatkan persetujuan dan alokasi Dosen Pembimbing.";
    $nextActionUrl = route('tugas-akhir.create');
    $nextActionButtonText = "Ajukan Proposal Sekarang";
    $nextActionIcon = "fa-solid fa-file-circle-plus";
    $nextActionTheme = "blue"; // blue, amber, emerald, indigo

    if (!$ta) {
        $stepStatus[1] = 'current';
        $overallProgress = 5;
    } elseif ($ta->status === 'pengajuan') {
        $stepStatus[1] = 'current';
        $overallProgress = 15;
        $nextActionTitle = "Proposal Sedang Ditinjau Prodi";
        $nextActionDesc = "Pengajuan judul Anda sedang menunggu verifikasi dari Koordinator Program Studi untuk penentuan Dosen Pembimbing 1 & 2.";
        $nextActionUrl = route('tugas-akhir.index');
        $nextActionButtonText = "Lihat Detail Pengajuan";
        $nextActionIcon = "fa-solid fa-clock-rotate-left";
        $nextActionTheme = "amber";
    } elseif ($ta->status === 'revisi_judul') {
        $stepStatus[1] = 'current';
        $overallProgress = 15;
        $nextActionTitle = "Revisi Judul / Proposal Diperlukan";
        $nextActionDesc = "Prodi meminta perbaikan pada proposal Anda: " . ($ta->catatan_prodi ?? 'Silakan sesuaikan isi judul.');
        $nextActionUrl = route('tugas-akhir.index');
        $nextActionButtonText = "Perbaiki Pengajuan";
        $nextActionIcon = "fa-solid fa-triangle-exclamation";
        $nextActionTheme = "amber";
    } elseif ($ta->status === 'disetujui' || $ta->status === 'bimbingan_proposal') {
        $stepStatus[1] = 'completed';
        $stepStatus[2] = 'current';
        $progressBimbingan = min(100, round(($totalBimbinganAcc / max(1, $minBimbingan)) * 100));
        $overallProgress = 20 + round($progressBimbingan * 0.18); // 20% to 38%
        
        if ($totalBimbinganAcc >= $minBimbingan) {
            $nextActionTitle = "Syarat Bimbingan Proposal Terpenuhi!";
            $nextActionDesc = "Anda telah mencapai $totalBimbinganAcc dari minimal $minBimbingan kali ACC bimbingan. Anda sudah memenuhi syarat untuk mendaftar Seminar Proposal.";
            $nextActionUrl = route('sidang.daftar');
            $nextActionButtonText = "Daftar Seminar Proposal (Sempro)";
            $nextActionIcon = "fa-solid fa-circle-check";
            $nextActionTheme = "emerald";
        } else {
            $sisaAcc = max(1, $minBimbingan - $totalBimbinganAcc);
            $nextActionTitle = "Bimbingan Proposal (Bab 1 - 3)";
            $nextActionDesc = "Lakukan sesi konsultasi dengan Dosen Pembimbing dan catat progress pada Logbook Bimbingan. Anda membutuhkan $sisaAcc kali ACC lagi untuk dapat mendaftar Sempro.";
            $nextActionUrl = route('bimbingan.index');
            $nextActionButtonText = "Buka Logbook Bimbingan";
            $nextActionIcon = "fa-solid fa-comments";
            $nextActionTheme = "blue";
        }
    } elseif ($ta->status === 'siap_sempro') {
        $stepStatus[1] = 'completed';
        $stepStatus[2] = 'completed';
        $stepStatus[3] = 'current';
        $overallProgress = 40;
        $nextActionTitle = "Pendaftaran Seminar Proposal Siap";
        $nextActionDesc = "Proposal telah disetujui untuk diuji. Daftarkan berkas proposal Anda agar Admin Prodi dapat menjadwalkan tanggal dan dosen penguji.";
        $nextActionUrl = route('sidang.daftar');
        $nextActionButtonText = "Daftar Sempro Sekarang";
        $nextActionIcon = "fa-solid fa-users-rectangle";
        $nextActionTheme = "indigo";
    } elseif ($sempro && $sempro->status === 'dijadwalkan') {
        $stepStatus[1] = 'completed';
        $stepStatus[2] = 'completed';
        $stepStatus[3] = 'current';
        $overallProgress = 48;
        $nextActionTitle = "Jadwal Seminar Proposal Telah Ditetapkan";
        $nextActionDesc = "Ujian Sempro Anda dijadwalkan pada " . ($sempro->tgl_sidang ? $sempro->tgl_sidang->translatedFormat('d F Y') : '-') . " pukul " . substr($sempro->jam_mulai ?? '', 0, 5) . " di Ruang " . ($sempro->ruangan ?? 'Ruang Sidang') . ". Persiapkan materi presentasi Anda.";
        $nextActionUrl = route('sidang.show', $sempro->id);
        $nextActionButtonText = "Lihat Rincian Jadwal & Penguji";
        $nextActionIcon = "fa-solid fa-calendar-check";
        $nextActionTheme = "blue";
    } elseif ($ta->status === 'lulus_sempro' || $ta->status === 'bimbingan_skripsi') {
        $stepStatus[1] = 'completed';
        $stepStatus[2] = 'completed';
        $stepStatus[3] = 'completed';
        $stepStatus[4] = 'current';
        $overallProgress = 60;
        $nextActionTitle = "Bimbingan Penulisan Skripsi (Bab 4 & 5)";
        $nextActionDesc = "Lulus Sempro! Lanjutkan pengambilan data penelitian dan bimbingan penulisan hasil pembahasan skripsi dengan Dosen Pembimbing.";
        $nextActionUrl = route('bimbingan.index');
        $nextActionButtonText = "Catat Logbook Skripsi";
        $nextActionIcon = "fa-solid fa-book-open";
        $nextActionTheme = "blue";
    } elseif ($ta->status === 'siap_sidang') {
        $stepStatus[1] = 'completed';
        $stepStatus[2] = 'completed';
        $stepStatus[3] = 'completed';
        $stepStatus[4] = 'current';
        $overallProgress = 72;
        $nextActionTitle = "Daftar Sidang Akhir Skripsi";
        $nextActionDesc = "Naskah skripsi lengkap telah di-ACC seluruh pembimbing. Segera daftarkan diri Anda untuk Sidang Akhir Skripsi.";
        $nextActionUrl = route('sidang.daftar');
        $nextActionButtonText = "Daftar Sidang Akhir";
        $nextActionIcon = "fa-solid fa-graduation-cap";
        $nextActionTheme = "indigo";
    } elseif ($sidangAkhir && $sidangAkhir->status === 'dijadwalkan') {
        $stepStatus[1] = 'completed';
        $stepStatus[2] = 'completed';
        $stepStatus[3] = 'completed';
        $stepStatus[4] = 'current';
        $overallProgress = 78;
        $nextActionTitle = "Jadwal Sidang Akhir Ditetapkan";
        $nextActionDesc = "Sidang Akhir Skripsi Anda dijadwalkan pada " . ($sidangAkhir->tgl_sidang ? $sidangAkhir->tgl_sidang->translatedFormat('d F Y') : '-') . " di Ruang " . ($sidangAkhir->ruangan ?? '-') . ".";
        $nextActionUrl = route('sidang.show', $sidangAkhir->id);
        $nextActionButtonText = "Lihat Detail Sidang Akhir";
        $nextActionIcon = "fa-solid fa-chalkboard-user";
        $nextActionTheme = "blue";
    } elseif ($ta->status === 'lulus_sidang' || $ta->status === 'selesai') {
        $stepStatus[1] = 'completed';
        $stepStatus[2] = 'completed';
        $stepStatus[3] = 'completed';
        $stepStatus[4] = 'completed';
        
        if (!$pendaftaranYudisium) {
            $stepStatus[5] = 'current';
            $overallProgress = 85;
            $nextActionTitle = "Lulus Sidang Akhir! Daftarkan Yudisium";
            $nextActionDesc = "Selamat Anda telah lulus Sidang Akhir Skripsi! Segera pilih periode yudisium aktif dan unggah berkas Bebas Tanggungan.";
            $nextActionUrl = route('yudisium.daftar');
            $nextActionButtonText = "Daftar Yudisium Sekarang";
            $nextActionIcon = "fa-solid fa-award";
            $nextActionTheme = "emerald";
        } elseif ($pendaftaranYudisium->status === 'lulus') {
            $stepStatus[5] = 'completed';
            $overallProgress = 100;
            $nextActionTitle = "🎉 Selamat! Anda Resmi Dinyatakan Lulus Yudisium";
            $nextActionDesc = "Anda telah menyelesaikan seluruh rangkaian Tugas Akhir & Yudisium STIKes Panti Waluya Malang dengan predikat " . ($pendaftaranYudisium->predikat ?? 'Sangat Memuaskan') . " (IPK: " . number_format($pendaftaranYudisium->ipk_final, 2) . ").";
            $nextActionUrl = route('yudisium.cetak-skl', $pendaftaranYudisium->id);
            $nextActionButtonText = "Unduh Surat Keterangan Lulus (SKL)";
            $nextActionIcon = "fa-solid fa-certificate";
            $nextActionTheme = "emerald";
        } else {
            $stepStatus[5] = 'current';
            $overallProgress = 90;
            $nextActionTitle = "Verifikasi Berkas Bebas Tanggungan";
            $nextActionDesc = "Unggah & pastikan seluruh berkas bebas tanggungan (Perpus, Lab, Keuangan) telah divalidasi oleh validator ($validBerkasCount dari $totalSyaratYudisium berkas valid).";
            $nextActionUrl = route('yudisium.index');
            $nextActionButtonText = "Kelola Berkas Bebas Tanggungan";
            $nextActionIcon = "fa-solid fa-file-circle-check";
            $nextActionTheme = "blue";
        }
    }
@endphp

<div class="space-y-6">

    <!-- Profile & Status Card -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 border border-blue-900/40 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="space-y-2 relative z-10">
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-graduation-cap text-xs text-blue-400"></i> Portal Akademik Mahasiswa
            </span>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">{{ $mahasiswa->nama_lengkap }}</h2>
            <p class="text-slate-300 text-sm flex flex-wrap items-center gap-2">
                <span>NIM: <strong class="font-mono text-cyan-300">{{ $mahasiswa->nim }}</strong></span>
                <span class="text-slate-600">•</span>
                <span>Prodi: <strong class="text-white">{{ $mahasiswa->prodi->nama_prodi ?? 'Program Studi' }}</strong></span>
                <span class="text-slate-600">•</span>
                <span>IPK: <strong class="text-cyan-300">{{ number_format($mahasiswa->ipk, 2) }}</strong></span>
            </p>
        </div>

        <div class="flex items-center gap-3 relative z-10 shrink-0">
            <!-- Overall Progress Circular / Pill Badge -->
            <div class="p-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 text-right flex items-center gap-3">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-300 block">Total Progres TA</span>
                    <span class="text-xl font-black text-cyan-400 font-mono">{{ $overallProgress }}%</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-cyan-500/20 border-2 border-cyan-400 flex items-center justify-center text-cyan-300 font-bold text-xs">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- VISUAL TIMELINE & PROGRESS TRACKER -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-route text-blue-600"></i> Roadmap & Timeline Tugas Akhir
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Pantau perjalanan 5 tahapan kelulusan skripsi & yudisium Anda secara realtime.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $overallProgress == 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                    <i class="fa-solid {{ $overallProgress == 100 ? 'fa-check-double text-emerald-600' : 'fa-circle-notch fa-spin text-blue-600' }} mr-1"></i>
                    Status: {{ $overallProgress == 100 ? 'Lulus Selesai' : 'Tahap ' . (array_search('current', $stepStatus) ?: '1') . ' Berjalan' }}
                </span>
            </div>
        </div>

        <!-- Horizontal Stepper (Desktop & Mobile Reflow) -->
        <div class="relative">
            <!-- Background Connecting Line (Desktop) -->
            <div class="hidden lg:block absolute top-1/2 left-8 right-8 -translate-y-7 h-1.5 bg-slate-100 rounded-full z-0">
                <div class="h-full bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500 rounded-full transition-all duration-700" style="width: {{ max(5, min(100, ($overallProgress - 5) * 1.05)) }}%"></div>
            </div>

            <!-- 5 Steps Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 relative z-10">

                <!-- STEP 1: Pengajuan Judul -->
                <div class="p-4 rounded-2xl border transition-all
                    {{ $stepStatus[1] === 'completed' ? 'bg-emerald-50/60 border-emerald-200 shadow-sm' : '' }}
                    {{ $stepStatus[1] === 'current' ? 'bg-blue-50 border-2 border-blue-600 shadow-md ring-4 ring-blue-50' : '' }}
                    {{ $stepStatus[1] === 'upcoming' ? 'bg-slate-50/80 border-slate-200 opacity-60' : '' }}">
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm
                            {{ $stepStatus[1] === 'completed' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : '' }}
                            {{ $stepStatus[1] === 'current' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 ring-2 ring-blue-300 animate-pulse' : '' }}
                            {{ $stepStatus[1] === 'upcoming' ? 'bg-slate-200 text-slate-500' : '' }}">
                            @if($stepStatus[1] === 'completed')
                                <i class="fa-solid fa-check"></i>
                            @else
                                1
                            @endif
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md
                            {{ $stepStatus[1] === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $stepStatus[1] === 'current' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $stepStatus[1] === 'upcoming' ? 'bg-slate-200 text-slate-600' : '' }}">
                            {{ $stepStatus[1] === 'completed' ? 'Selesai' : ($stepStatus[1] === 'current' ? 'Aktif' : 'Nanti') }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-xs text-slate-900 leading-snug">1. Pengajuan Judul</h4>
                    <p class="text-[11px] text-slate-500 mt-1">Proposal & Plotting Pembimbing</p>

                    <div class="mt-3 pt-2.5 border-t border-slate-200/60 text-[11px]">
                        @if($ta)
                            <span class="font-bold text-slate-700 block truncate" title="{{ $ta->judul }}">
                                <i class="fa-solid fa-file-lines text-blue-500 mr-1"></i> {{ Str::limit($ta->judul, 22) }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">Belum diajukan</span>
                        @endif
                    </div>
                </div>

                <!-- STEP 2: Bimbingan Proposal -->
                <div class="p-4 rounded-2xl border transition-all
                    {{ $stepStatus[2] === 'completed' ? 'bg-emerald-50/60 border-emerald-200 shadow-sm' : '' }}
                    {{ $stepStatus[2] === 'current' ? 'bg-blue-50 border-2 border-blue-600 shadow-md ring-4 ring-blue-50' : '' }}
                    {{ $stepStatus[2] === 'upcoming' ? 'bg-slate-50/80 border-slate-200 opacity-60' : '' }}">
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm
                            {{ $stepStatus[2] === 'completed' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : '' }}
                            {{ $stepStatus[2] === 'current' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 ring-2 ring-blue-300 animate-pulse' : '' }}
                            {{ $stepStatus[2] === 'upcoming' ? 'bg-slate-200 text-slate-500' : '' }}">
                            @if($stepStatus[2] === 'completed')
                                <i class="fa-solid fa-check"></i>
                            @elseif($stepStatus[2] === 'upcoming')
                                <i class="fa-solid fa-lock text-xs"></i>
                            @else
                                2
                            @endif
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md
                            {{ $stepStatus[2] === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $stepStatus[2] === 'current' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $stepStatus[2] === 'upcoming' ? 'bg-slate-200 text-slate-600' : '' }}">
                            {{ $stepStatus[2] === 'completed' ? 'ACC Lulus' : ($stepStatus[2] === 'current' ? 'Bimbingan' : 'Terkunci') }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-xs text-slate-900 leading-snug">2. Bimbingan Proposal</h4>
                    <p class="text-[11px] text-slate-500 mt-1">Logbook Konsultasi Bab 1-3</p>

                    <div class="mt-3 pt-2.5 border-t border-slate-200/60 text-[11px] flex items-center justify-between">
                        <span class="text-slate-500">ACC Terkumpul:</span>
                        <strong class="{{ $totalBimbinganAcc >= $minBimbingan ? 'text-emerald-700' : 'text-blue-700' }} font-bold">
                            {{ $totalBimbinganAcc }} / {{ $minBimbingan }}x
                        </strong>
                    </div>
                </div>

                <!-- STEP 3: Seminar Proposal (Sempro) -->
                <div class="p-4 rounded-2xl border transition-all
                    {{ $stepStatus[3] === 'completed' ? 'bg-emerald-50/60 border-emerald-200 shadow-sm' : '' }}
                    {{ $stepStatus[3] === 'current' ? 'bg-blue-50 border-2 border-blue-600 shadow-md ring-4 ring-blue-50' : '' }}
                    {{ $stepStatus[3] === 'upcoming' ? 'bg-slate-50/80 border-slate-200 opacity-60' : '' }}">
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm
                            {{ $stepStatus[3] === 'completed' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : '' }}
                            {{ $stepStatus[3] === 'current' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 ring-2 ring-blue-300 animate-pulse' : '' }}
                            {{ $stepStatus[3] === 'upcoming' ? 'bg-slate-200 text-slate-500' : '' }}">
                            @if($stepStatus[3] === 'completed')
                                <i class="fa-solid fa-check"></i>
                            @elseif($stepStatus[3] === 'upcoming')
                                <i class="fa-solid fa-lock text-xs"></i>
                            @else
                                3
                            @endif
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md
                            {{ $stepStatus[3] === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $stepStatus[3] === 'current' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $stepStatus[3] === 'upcoming' ? 'bg-slate-200 text-slate-600' : '' }}">
                            {{ $stepStatus[3] === 'completed' ? 'Lulus' : ($stepStatus[3] === 'current' ? 'Ujian Sempro' : 'Terkunci') }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-xs text-slate-900 leading-snug">3. Seminar Proposal</h4>
                    <p class="text-[11px] text-slate-500 mt-1">Ujian Sempro & Masukan Penguji</p>

                    <div class="mt-3 pt-2.5 border-t border-slate-200/60 text-[11px]">
                        @if($sempro && $sempro->nilai_akhir)
                            <span class="text-emerald-700 font-bold">Nilai: {{ number_format($sempro->nilai_akhir, 1) }} ({{ $sempro->grade_huruf }})</span>
                        @elseif($sempro && $sempro->status === 'dijadwalkan')
                            <span class="text-blue-700 font-bold truncate block">Jadwal: {{ $sempro->tgl_sidang ? $sempro->tgl_sidang->format('d/m/Y') : 'Ditetapkan' }}</span>
                        @else
                            <span class="text-slate-400 italic">Belum terjadwal</span>
                        @endif
                    </div>
                </div>

                <!-- STEP 4: Bimbingan Skripsi & Sidang Akhir -->
                <div class="p-4 rounded-2xl border transition-all
                    {{ $stepStatus[4] === 'completed' ? 'bg-emerald-50/60 border-emerald-200 shadow-sm' : '' }}
                    {{ $stepStatus[4] === 'current' ? 'bg-blue-50 border-2 border-blue-600 shadow-md ring-4 ring-blue-50' : '' }}
                    {{ $stepStatus[4] === 'upcoming' ? 'bg-slate-50/80 border-slate-200 opacity-60' : '' }}">
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm
                            {{ $stepStatus[4] === 'completed' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : '' }}
                            {{ $stepStatus[4] === 'current' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 ring-2 ring-blue-300 animate-pulse' : '' }}
                            {{ $stepStatus[4] === 'upcoming' ? 'bg-slate-200 text-slate-500' : '' }}">
                            @if($stepStatus[4] === 'completed')
                                <i class="fa-solid fa-check"></i>
                            @elseif($stepStatus[4] === 'upcoming')
                                <i class="fa-solid fa-lock text-xs"></i>
                            @else
                                4
                            @endif
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md
                            {{ $stepStatus[4] === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $stepStatus[4] === 'current' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $stepStatus[4] === 'upcoming' ? 'bg-slate-200 text-slate-600' : '' }}">
                            {{ $stepStatus[4] === 'completed' ? 'Lulus Sidang' : ($stepStatus[4] === 'current' ? 'Skripsi & Sidang' : 'Terkunci') }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-xs text-slate-900 leading-snug">4. Sidang Skripsi</h4>
                    <p class="text-[11px] text-slate-500 mt-1">Bab 4-5 & Ujian Akhir Skripsi</p>

                    <div class="mt-3 pt-2.5 border-t border-slate-200/60 text-[11px]">
                        @if($sidangAkhir && $sidangAkhir->nilai_akhir)
                            <span class="text-emerald-700 font-bold">Nilai: {{ number_format($sidangAkhir->nilai_akhir, 1) }} ({{ $sidangAkhir->grade_huruf }})</span>
                        @elseif($sidangAkhir && $sidangAkhir->status === 'dijadwalkan')
                            <span class="text-blue-700 font-bold truncate block">Jadwal: {{ $sidangAkhir->tgl_sidang ? $sidangAkhir->tgl_sidang->format('d/m/Y') : 'Ditetapkan' }}</span>
                        @else
                            <span class="text-slate-400 italic">Bab 4-5 Penelitian</span>
                        @endif
                    </div>
                </div>

                <!-- STEP 5: Bebas Tanggungan & Yudisium -->
                <div class="p-4 rounded-2xl border transition-all
                    {{ $stepStatus[5] === 'completed' ? 'bg-emerald-50/60 border-emerald-200 shadow-sm' : '' }}
                    {{ $stepStatus[5] === 'current' ? 'bg-blue-50 border-2 border-blue-600 shadow-md ring-4 ring-blue-50' : '' }}
                    {{ $stepStatus[5] === 'upcoming' ? 'bg-slate-50/80 border-slate-200 opacity-60' : '' }}">
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm
                            {{ $stepStatus[5] === 'completed' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : '' }}
                            {{ $stepStatus[5] === 'current' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 ring-2 ring-blue-300 animate-pulse' : '' }}
                            {{ $stepStatus[5] === 'upcoming' ? 'bg-slate-200 text-slate-500' : '' }}">
                            @if($stepStatus[5] === 'completed')
                                <i class="fa-solid fa-graduation-cap"></i>
                            @elseif($stepStatus[5] === 'upcoming')
                                <i class="fa-solid fa-lock text-xs"></i>
                            @else
                                5
                            @endif
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md
                            {{ $stepStatus[5] === 'completed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $stepStatus[5] === 'current' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $stepStatus[5] === 'upcoming' ? 'bg-slate-200 text-slate-600' : '' }}">
                            {{ $stepStatus[5] === 'completed' ? 'RESMI LULUS' : ($stepStatus[5] === 'current' ? 'Yudisium' : 'Terkunci') }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-xs text-slate-900 leading-snug">5. Yudisium</h4>
                    <p class="text-[11px] text-slate-500 mt-1">Bebas Tanggungan & SKL</p>

                    <div class="mt-3 pt-2.5 border-t border-slate-200/60 text-[11px]">
                        @if($pendaftaranYudisium && $pendaftaranYudisium->status === 'lulus')
                            <span class="text-emerald-700 font-bold">IPK: {{ number_format($pendaftaranYudisium->ipk_final, 2) }}</span>
                        @elseif($pendaftaranYudisium)
                            <span class="text-blue-700 font-bold">{{ $validBerkasCount }}/{{ $totalSyaratYudisium }} Berkas Valid</span>
                        @else
                            <span class="text-slate-400 italic">Tahap akhir kelulusan</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Next Action Callout (Panduan Langkah Selanjutnya) -->
        <div class="p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 border
            {{ $nextActionTheme === 'emerald' ? 'bg-emerald-50/90 border-emerald-200 text-emerald-950' : '' }}
            {{ $nextActionTheme === 'amber' ? 'bg-amber-50/90 border-amber-200 text-amber-950' : '' }}
            {{ $nextActionTheme === 'indigo' ? 'bg-indigo-50/90 border-indigo-200 text-indigo-950' : '' }}
            {{ $nextActionTheme === 'blue' ? 'bg-blue-50/90 border-blue-200 text-blue-950' : '' }}">
            
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-md
                    {{ $nextActionTheme === 'emerald' ? 'bg-emerald-600 shadow-emerald-600/30' : '' }}
                    {{ $nextActionTheme === 'amber' ? 'bg-amber-500 shadow-amber-500/30' : '' }}
                    {{ $nextActionTheme === 'indigo' ? 'bg-indigo-600 shadow-indigo-600/30' : '' }}
                    {{ $nextActionTheme === 'blue' ? 'bg-blue-600 shadow-blue-600/30' : '' }}">
                    <i class="{{ $nextActionIcon }} text-xl"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider block opacity-75">
                        <i class="fa-solid fa-compass mr-1"></i> Langkah Anda Selanjutnya:
                    </span>
                    <h4 class="font-extrabold text-sm text-slate-900 mt-0.5">{{ $nextActionTitle }}</h4>
                    <p class="text-xs text-slate-600 mt-1 max-w-2xl leading-relaxed">{{ $nextActionDesc }}</p>
                </div>
            </div>

            <a href="{{ $nextActionUrl }}" class="px-5 py-3 text-white font-bold rounded-xl text-xs shrink-0 shadow-md transition flex items-center justify-center gap-2
                {{ $nextActionTheme === 'emerald' ? 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/30' : '' }}
                {{ $nextActionTheme === 'amber' ? 'bg-amber-600 hover:bg-amber-500 shadow-amber-600/30' : '' }}
                {{ $nextActionTheme === 'indigo' ? 'bg-indigo-600 hover:bg-indigo-500 shadow-indigo-600/30' : '' }}
                {{ $nextActionTheme === 'blue' ? 'bg-blue-600 hover:bg-blue-500 shadow-blue-600/30' : '' }}">
                <span>{{ $nextActionButtonText }}</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

    </div>

    <!-- DETAIL STATUS TUGAS AKHIR & YUDISIUM -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Status Detail TA -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-800">Detail Naskah Tugas Akhir</h3>
                </div>
                @if($ta)
                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase
                    {{ $ta->status === 'disetujui' || $ta->status === 'lulus_sidang' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ str_replace('_', ' ', $ta->status) }}
                </span>
                @endif
            </div>

            @if($ta)
            <div class="space-y-4">
                <div>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 block">Judul Penelitian</span>
                    <h4 class="text-base font-extrabold text-slate-900 mt-1 leading-snug">{{ $ta->judul }}</h4>
                    @if($ta->bidang_kajian)
                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-medium mt-1.5">
                        Bidang: {{ $ta->bidang_kajian }}
                    </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                            P1
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Pembimbing 1</span>
                            <p class="text-xs font-bold text-slate-800">{{ $ta->pembimbing1->nama_lengkap ?? 'Belum Ditentukan' }}</p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm shrink-0">
                            P2
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Pembimbing 2</span>
                            <p class="text-xs font-bold text-slate-800">{{ $ta->pembimbing2->nama_lengkap ?? 'Belum Ditentukan' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Bimbingan Bar -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700">Progres ACC Konsultasi Dosen (Min. {{ $minBimbingan }}x ACC):</span>
                        <span class="{{ $totalBimbinganAcc >= $minBimbingan ? 'text-emerald-600' : 'text-blue-600' }} font-bold">
                            {{ $totalBimbinganAcc }} / {{ $minBimbingan }} Pertemuan ACC
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ min(100, ($totalBimbinganAcc / max(1, $minBimbingan)) * 100) }}%"></div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-10 space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto text-blue-600 text-2xl">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm">Belum Mengajukan Judul Tugas Akhir</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Silakan ajukan judul proposal tugas akhir Anda untuk mendapatkan persetujuan dan plotting dosen pembimbing dari Program Studi.</p>
                <a href="{{ route('tugas-akhir.create') }}" class="inline-block px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
                    Ajukan Sekarang
                </a>
            </div>
            @endif
        </div>

        <!-- Right: Status Yudisium Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <h3 class="font-bold text-base text-slate-800">Status Yudisium</h3>
                    </div>
                </div>

                @if($pendaftaranYudisium)
                <div class="space-y-3 text-xs">
                    <div class="p-4 rounded-2xl {{ $pendaftaranYudisium->status === 'lulus' ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-blue-50 border border-blue-200 text-blue-900' }}">
                        <div class="font-bold text-xs flex items-center justify-between">
                            <span>Status Kelulusan:</span>
                            <span class="uppercase px-2.5 py-0.5 rounded-md font-extrabold text-[10px] {{ $pendaftaranYudisium->status === 'lulus' ? 'bg-emerald-600 text-white' : 'bg-blue-600 text-white' }}">
                                {{ $pendaftaranYudisium->status === 'lulus' ? 'Dinyatakan LULUS' : $pendaftaranYudisium->status }}
                            </span>
                        </div>
                        <p class="mt-2 text-[11px] font-semibold">{{ $pendaftaranYudisium->periodeYudisium->nama_periode ?? 'Periode Yudisium' }}</p>
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

                    <a href="{{ route('yudisium.cetak-skl', $pendaftaranYudisium->id) }}" target="_blank" class="block w-full text-center py-2.5 px-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/30 transition">
                        <i class="fa-solid fa-print mr-1"></i> Cetak SKL Resmi
                    </a>
                    @else
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[11px] space-y-1">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Validasi Berkas:</span>
                            <strong class="text-blue-700 font-bold">{{ $validBerkasCount }} / {{ $totalSyaratYudisium }} Berkas</strong>
                        </div>
                    </div>

                    <a href="{{ route('yudisium.index') }}" class="block w-full text-center py-2.5 px-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-xs transition">
                        Kelola Berkas Bebas Tanggungan &rarr;
                    </a>
                    @endif
                </div>
                @else
                <div class="text-center py-8 text-xs text-slate-400 space-y-2">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <p class="text-slate-500 font-medium">Pendaftaran yudisium dapat dilakukan setelah Anda menyelesaikan sidang akhir skripsi.</p>
                </div>
                @endif
            </div>

            <div class="pt-3 border-t border-slate-100">
                <a href="{{ route('pengumuman.index') }}" class="text-[11px] text-blue-600 hover:underline font-bold flex items-center justify-between">
                    <span><i class="fa-solid fa-bullhorn mr-1"></i> Pengumuman Kampus</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection


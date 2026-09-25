@extends('layouts.app')

@section('title', 'Dashboard Koordinator & Admin')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-blue-900/40">
        <div class="relative z-10 max-w-2xl">
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-shield-halved text-xs text-blue-400"></i> Pusat Kendali Akademik STIKes Panti Waluya
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-3 tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-slate-300 text-sm mt-2 leading-relaxed">
                Kelola pendaftaran judul tugas akhir, plotting dosen pembimbing, jadwal sidang sempro/skripsi, hingga verifikasi berkas yudisium dalam satu sistem terintegrasi.
            </p>
        </div>
        <div class="absolute right-4 bottom-0 opacity-10 text-9xl text-blue-300">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Mahasiswa</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_mahasiswa'] }}</h3>
                <span class="text-xs text-blue-600 font-semibold"><i class="fa-solid fa-circle-check"></i> Terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Dosen Pembimbing</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_dosen'] }}</h3>
                <span class="text-xs text-cyan-600 font-semibold"><i class="fa-solid fa-user-tie"></i> Aktif Menguji</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tugas Akhir Berjalan</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_ta_aktif'] }}</h3>
                <span class="text-xs text-amber-600 font-semibold"><i class="fa-solid fa-spinner"></i> Tahap Bimbingan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Peserta Yudisium</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_pendaftar_yudisium'] }}</h3>
                <span class="text-xs text-emerald-600 font-semibold"><i class="fa-solid fa-circle-check"></i> {{ $stats['total_yudisium_lulus'] }} Lulus</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-award"></i>
            </div>
        </div>
    </div>

    <!-- Main Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Pengajuan TA Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-base text-slate-800">Pengajuan & Progres TA Terbaru</h3>
                    <p class="text-xs text-slate-400">Daftar judul tugas akhir mahasiswa semester ini</p>
                </div>
                <a href="{{ route('tugas-akhir.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                    Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentTAs as $ta)
                <div class="py-3.5 flex items-start justify-between gap-4 hover:bg-slate-50/60 rounded-xl px-2 transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs text-slate-900">{{ $ta->mahasiswa->nama_lengkap }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">({{ $ta->mahasiswa->nim }})</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-700">{{ $ta->mahasiswa->prodi->nama_prodi ?? 'Prodi' }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-slate-800 leading-snug line-clamp-1 hover:line-clamp-none transition">{{ $ta->judul }}</h4>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span><i class="fa-solid fa-user-tie mr-1 text-slate-400"></i> {{ $ta->pembimbing1 ? $ta->pembimbing1->nama_lengkap : 'Belum diplot' }}</span>
                            <span>&bull;</span>
                            <span>{{ $ta->bimbingans_count ?? $ta->bimbingans->count() }}x Bimbingan</span>
                        </div>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="inline-block px-2.5 py-1 text-[11px] font-bold rounded-lg uppercase
                            {{ $ta->status === 'disetujui' || $ta->status === 'lulus_sidang' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $ta->status === 'pengajuan' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $ta->status === 'bimbingan_skripsi' || $ta->status === 'bimbingan_proposal' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $ta->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                        ">
                            {{ str_replace('_', ' ', $ta->status) }}
                        </span>
                        <div class="mt-1">
                            <a href="{{ route('tugas-akhir.show', $ta->id) }}" class="text-[11px] font-semibold text-blue-600 hover:underline">Detail &rarr;</a>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada data tugas akhir.</p>
                @endforelse
            </div>
        </div>

        <!-- Right Col: Pengumuman & Jadwal Sidang -->
        <div class="space-y-6">
            
            <!-- Jadwal Sidang Mendatang -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-sm text-slate-800">Jadwal Sidang Terdekat</h3>
                    <a href="{{ route('sidang.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Kelola</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentSidangs as $sidang)
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs hover:border-blue-200 transition">
                        <div class="flex items-center justify-between font-bold text-slate-800 mb-1">
                            <span class="uppercase text-blue-700 font-extrabold">{{ $sidang->jenis }}</span>
                            <span class="text-slate-500">{{ $sidang->tgl_sidang ? $sidang->tgl_sidang->format('d M Y') : 'Belum dijadwalkan' }}</span>
                        </div>
                        <p class="font-semibold text-slate-700 truncate">{{ $sidang->tugasAkhir->mahasiswa->nama_lengkap }}</p>
                        <p class="text-[11px] text-slate-500 mt-1"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $sidang->ruangan ?? '-' }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Tidak ada jadwal sidang terdekat.</p>
                    @endforelse
                </div>
            </div>

            <!-- Papan Pengumuman -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-sm text-slate-800">Pengumuman Kampus</h3>
                    <a href="{{ route('pengumuman.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Buat Baru</a>
                </div>

                <div class="space-y-3">
                    @forelse($pengumumans as $p)
                    <div class="p-3 rounded-2xl bg-blue-50/50 border border-blue-100/80 text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-blue-900">{{ $p->judul }}</span>
                            @if($p->is_pinned)
                            <i class="fa-solid fa-thumbtack text-amber-500 text-[10px]"></i>
                            @endif
                        </div>
                        <p class="text-slate-600 line-clamp-2">{{ $p->konten }}</p>
                        <span class="text-[10px] text-slate-400 block pt-1">{{ $p->created_at->diffForHumans() }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-3">Belum ada pengumuman.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Antrean Verifikasi Berkas Bebas Tanggungan Yudisium -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-base text-slate-800">Antrean Verifikasi Berkas Bebas Tanggungan Yudisium</h3>
                <p class="text-xs text-slate-400">Verifikasi dokumen perpustakaan, keuangan, laboratorium, dan TOEFL mahasiswa</p>
            </div>
            <a href="{{ route('yudisium.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                Kelola Semua Yudisium &rarr;
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($pendingBerkas as $b)
            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 rounded-xl px-2 transition">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-slate-800">{{ $b->pendaftaranYudisium->mahasiswa->nama_lengkap }}</span>
                        <span class="text-xs text-slate-400 font-mono">({{ $b->pendaftaranYudisium->mahasiswa->nim }})</span>
                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase">{{ $b->syaratYudisium->kategori }}</span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium">{{ $b->syaratYudisium->nama_syarat }}</p>
                </div>
                <div>
                    <a href="{{ route('yudisium.show', $b->pendaftaran_yudisium_id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-xl text-xs font-bold transition shadow-sm">
                        <i class="fa-solid fa-clipboard-check mr-1.5"></i> Periksa Berkas
                    </a>
                </div>
            </div>
            @empty
            <p class="text-xs text-slate-400 py-4 text-center">Semua berkas bebas tanggungan telah diverifikasi.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

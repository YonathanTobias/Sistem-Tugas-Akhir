@extends('layouts.app')

@section('title', 'Dashboard Validator Bebas Tanggungan')

@section('content')
<div class="space-y-6">

    <!-- Header Welcome -->
    <div class="bg-gradient-to-r from-slate-900 via-amber-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">
                Pusat Verifikasi Bebas Tanggungan
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-3 tracking-tight">{{ auth()->user()->name }}</h2>
            <p class="text-slate-300 text-sm mt-1">Layanan verifikasi berkas perpustakaan, keuangan, lab, dan kelayakan yudisium mahasiswa.</p>
        </div>
        <div>
            <a href="{{ route('yudisium.index') }}" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl text-xs shadow-lg transition">
                <i class="fa-solid fa-clipboard-check mr-1.5"></i> Periksa Semua Berkas ({{ $stats['total_berkas_pending'] }})
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Berkas Pending</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $stats['total_berkas_pending'] }}</h3>
                <span class="text-xs text-amber-700 font-semibold">Perlu validasi</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pendaftaran Masuk</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_pendaftaran_masuk'] }}</h3>
                <span class="text-xs text-sky-600 font-semibold">Mahasiswa terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Lolos Verifikasi</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['total_pendaftaran_terverifikasi'] }}</h3>
                <span class="text-xs text-emerald-600 font-semibold">Siap ditetapkan SK</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- Berkas Menunggu Verifikasi -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-base text-slate-800">Antrean Verifikasi Berkas Bebas Tanggungan</h3>
                <p class="text-xs text-slate-400">Periksa keaslian dokumen perpustakaan, keuangan, dan sertifikat</p>
            </div>
            <a href="{{ route('yudisium.index') }}" class="text-xs font-bold text-amber-600">Buka Menu Yudisium &rarr;</a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($pendingBerkas as $b)
            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-slate-800">{{ $b->pendaftaranYudisium->mahasiswa->nama_lengkap }}</span>
                        <span class="text-xs text-slate-400">({{ $b->pendaftaranYudisium->mahasiswa->nim }})</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold uppercase">{{ $b->syaratYudisium->kategori }}</span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium">{{ $b->syaratYudisium->nama_syarat }}</p>
                </div>
                <div>
                    <a href="{{ route('yudisium.show', $b->pendaftaran_yudisium_id) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white rounded-xl text-xs font-bold transition">
                        <i class="fa-solid fa-magnifying-glass mr-1.5"></i> Periksa Berkas
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400 text-xs">
                <i class="fa-solid fa-circle-check text-emerald-500 text-3xl mb-2"></i>
                <p>Tidak ada berkas yang menunggu verifikasi saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

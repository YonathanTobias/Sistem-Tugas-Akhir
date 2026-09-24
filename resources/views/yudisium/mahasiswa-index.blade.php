@extends('layouts.app')

@section('title', 'Pendaftaran & Berkas Bebas Tanggungan Yudisium')

@section('content')
<div class="space-y-6">

    @if(!$pendaftaran)
    <!-- Banner Belum Mendaftar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 text-center shadow-sm max-w-2xl mx-auto space-y-4">
        <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-600 flex items-center justify-center text-3xl mx-auto">
            <i class="fa-solid fa-award"></i>
        </div>
        <h2 class="text-xl font-black text-slate-800">Pendaftaran Yudisium Kelulusan</h2>
        <p class="text-xs text-slate-500 leading-relaxed">
            @if($periodeAktif)
                Periode pendaftaran aktif: <strong>{{ $periodeAktif->nama_periode }}</strong> (Batas akhir: {{ $periodeAktif->tgl_tutup->format('d F Y') }}).
            @else
                Saat ini belum ada periode yudisium yang dibuka oleh fakultas.
            @endif
        </p>

        @if($ta && $ta->status === 'lulus_sidang' && $periodeAktif)
        <a href="{{ route('yudisium.daftar') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl text-xs shadow-lg shadow-emerald-600/30 transition">
            <i class="fa-solid fa-file-signature mr-2"></i> Daftar Yudisium Sekarang
        </a>
        @elseif(!$ta || $ta->status !== 'lulus_sidang')
        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 font-medium">
            <i class="fa-solid fa-circle-info mr-1"></i> Anda baru dapat mendaftar yudisium setelah dinyatakan <strong>Lulus Sidang Skripsi</strong>.
        </div>
        @endif
    </div>

    @else

    <!-- Status Yudisium Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Periode Yudisium</span>
                <h3 class="text-lg font-black text-slate-800">{{ $pendaftaran->periodeYudisium->nama_periode }}</h3>
                <p class="text-xs text-slate-500">Pelaksanaan: {{ $pendaftaran->periodeYudisium->tgl_pelaksanaan->format('d F Y') }} &bull; IPK Final: <strong>{{ number_format($pendaftaran->ipk_final, 2) }}</strong></p>
            </div>
            <div>
                <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wide
                    {{ $pendaftaran->status === 'lulus' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $pendaftaran->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : '' }}
                    {{ $pendaftaran->status === 'diajukan' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $pendaftaran->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                ">
                    Status: {{ strtoupper($pendaftaran->status) }}
                </span>
            </div>
        </div>

        @if($pendaftaran->status === 'lulus')
        <!-- Congratulation Banner for Graduated Students -->
        <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg space-y-3">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-graduation-cap text-3xl"></i>
                <div>
                    <h3 class="font-extrabold text-base">Selamat! Anda Dinyatakan LULUS YUDISIUM</h3>
                    <p class="text-xs text-emerald-100">Predikat: <strong>{{ $pendaftaran->predikat }}</strong> &bull; No. SK: <span class="font-mono">{{ $pendaftaran->nomor_sk }}</span></p>
                </div>
            </div>
            <div class="pt-2">
                <a href="{{ route('yudisium.cetak-skl', $pendaftaran->id) }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-white text-emerald-800 hover:bg-emerald-50 font-bold rounded-xl text-xs shadow transition">
                    <i class="fa-solid fa-print mr-2"></i> Cetak / Unduh Surat Keterangan Lulus (SKL) Digital
                </a>
            </div>
        </div>
        @endif

        <!-- Clearance Checklist (Upload Berkas Bebas Tanggungan) -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Berkas Bebas Tanggungan Kelulusan</h4>
                    <p class="text-xs text-slate-500">Unggah berkas bukti bebas perpus, keuangan, lab, dan sertifikat pendukung.</p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($syarats as $syarat)
                @php
                    $berkas = $pendaftaran->berkasYudisiums->where('syarat_yudisium_id', $syarat->id)->first();
                @endphp
                <div class="p-4 rounded-2xl border {{ $berkas && $berkas->status === 'valid' ? 'bg-emerald-50/40 border-emerald-200' : ($berkas && $berkas->status === 'ditolak' ? 'bg-rose-50/40 border-rose-200' : 'bg-slate-50 border-slate-200') }} flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-700 text-[10px] font-bold uppercase">{{ $syarat->kategori }}</span>
                            <h5 class="font-bold text-xs text-slate-800">{{ $syarat->nama_syarat }}</h5>
                            @if($syarat->is_wajib)
                            <span class="text-[10px] text-rose-600 font-bold">*Wajib</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500">{{ $syarat->deskripsi }}</p>
                        
                        @if($berkas && $berkas->catatan_validator)
                        <p class="text-[11px] text-rose-600 font-medium">Catatan Petugas: {{ $berkas->catatan_validator }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        @if($berkas)
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase
                                {{ $berkas->status === 'valid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $berkas->status === 'menunggu' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $berkas->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                            ">
                                {{ $berkas->status === 'valid' ? '✅ VALID' : ($berkas->status === 'ditolak' ? '❌ DITOLAK' : '⏳ MENUNGGU') }}
                            </span>

                            <a href="{{ asset('storage/' . $berkas->file_path) }}" target="_blank" class="p-2 rounded-xl bg-slate-200 text-slate-700 hover:bg-slate-300 text-xs font-bold" title="Lihat Berkas">
                                <i class="fa-solid fa-file"></i>
                            </a>
                        @endif

                        @if($pendaftaran->status !== 'lulus')
                        <!-- Upload Button / Form -->
                        <form action="{{ route('yudisium.upload-berkas', $pendaftaran->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="syarat_yudisium_id" value="{{ $syarat->id }}">
                            <label class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold cursor-pointer transition">
                                <i class="fa-solid fa-cloud-arrow-up mr-1"></i> {{ $berkas ? 'Ganti File' : 'Upload File' }}
                                <input type="file" name="file_berkas" accept=".pdf,.jpg,.jpeg,.png" onchange="this.form.submit()" class="hidden">
                            </label>
                        </form>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>

        </div>

    </div>
    @endif

</div>
@endsection

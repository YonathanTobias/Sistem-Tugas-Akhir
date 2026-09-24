@extends('layouts.app')

@section('title', 'Pendaftaran Periode Yudisium')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase bg-emerald-100 text-emerald-700">
                Pendaftaran Yudisium
            </span>
            <h2 class="text-xl font-black text-slate-800 mt-2">Daftar Keikutsertaan Yudisium</h2>
            <p class="text-xs text-slate-500 mt-1">Konfirmasi data kelulusan dan keikutsertaan yudisium pada periode aktif.</p>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="font-bold text-slate-400 uppercase text-[10px]">Nama Mahasiswa:</span>
                    <p class="font-bold text-slate-800 text-sm">{{ $mahasiswa->nama_lengkap }}</p>
                </div>
                <div>
                    <span class="font-bold text-slate-400 uppercase text-[10px]">NIM:</span>
                    <p class="font-mono font-bold text-slate-800 text-sm">{{ $mahasiswa->nim }}</p>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-200/60">
                <span class="font-bold text-slate-400 uppercase text-[10px]">Periode Yudisium Aktif:</span>
                <p class="font-extrabold text-emerald-800 text-sm mt-0.5">{{ $periodeAktif->nama_periode }}</p>
                <p class="text-slate-500 text-[11px]">Tanggal Pelaksanaan: {{ $periodeAktif->tgl_pelaksanaan->format('d F Y') }}</p>
            </div>
        </div>

        <form action="{{ route('yudisium.daftar.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1.5">
                <strong class="font-bold block"><i class="fa-solid fa-triangle-exclamation mr-1 text-amber-600"></i> Persyaratan Dokumen Bebas Tanggungan:</strong>
                <p>Setelah melakukan pendaftaran ini, Anda diwajibkan mengunggah seluruh dokumen bebas tanggungan (Perpustakaan, Keuangan, Laboratorium, dan Sertifikat Bahasa) pada dashboard yudisium.</p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('yudisium.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/30 transition">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Konfirmasi & Mulai Upload Berkas
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

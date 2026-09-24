@extends('layouts.app')

@section('title', 'Form Pendaftaran ' . ($jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Akhir Skripsi'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase {{ $jenis === 'sempro' ? 'bg-sky-100 text-sky-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Akhir Skripsi' }}
            </span>
            <h2 class="text-xl font-black text-slate-800 mt-2">Konfirmasi Pendaftaran Ujian</h2>
            <p class="text-xs text-slate-500 mt-1">Pastikan draft naskah skripsi Anda telah disetujui dosen pembimbing sebelum mendaftar.</p>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
            <div>
                <span class="font-bold text-slate-400 uppercase text-[10px]">Judul Skripsi:</span>
                <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $ta->judul }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-2">
                <div>
                    <span class="font-bold text-slate-400 uppercase text-[10px]">Pembimbing 1:</span>
                    <p class="font-semibold text-slate-800">{{ $ta->pembimbing1->nama_lengkap ?? '-' }}</p>
                </div>
                <div>
                    <span class="font-bold text-slate-400 uppercase text-[10px]">Pembimbing 2:</span>
                    <p class="font-semibold text-slate-800">{{ $ta->pembimbing2->nama_lengkap ?? '-' }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('sidang.daftar.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="jenis" value="{{ $jenis }}">

            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-xs text-emerald-900 flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5"></i>
                <div class="space-y-1">
                    <strong class="font-bold">Ketentuan Pendaftaran:</strong>
                    <p>Setelah mengirimkan permohonan pendaftaran, Koordinator Program Studi akan menjadwalkan tanggal, jam, ruangan ujian, dan menentukan dewan dosen penguji.</p>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('sidang.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/30 transition">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

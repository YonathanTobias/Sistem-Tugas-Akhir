@extends('layouts.app')

@section('title', 'Catat Sesi Bimbingan Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="text-xl font-black text-slate-800">Catat Logbook Bimbingan Skripsi</h2>
            <p class="text-xs text-slate-500 mt-1">Dokumentasikan materi diskusi dan arahan revisi bersama dosen pembimbing.</p>
        </div>

        <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Dosen Pembimbing <span class="text-rose-500">*</span></label>
                    <select name="dosen_id" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($pembimbings as $p)
                        <option value="{{ $p->id }}" {{ old('dosen_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_lengkap }} ({{ $p->nidn }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Bimbingan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tgl_bimbingan" value="{{ old('tgl_bimbingan', date('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bab / Bagian <span class="text-rose-500">*</span></label>
                    <input type="text" name="bab" value="{{ old('bab') }}" required placeholder="misal: Bab 1, Bab 2-3, Bab 4"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Topik / Bahasan Utama <span class="text-rose-500">*</span></label>
                    <input type="text" name="topik_bimbingan" value="{{ old('topik_bimbingan') }}" required placeholder="misal: Perbaikan Metodologi & Alur Algoritma"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Uraian / Ringkasan Diskusi & Progress Mahasiswa <span class="text-rose-500">*</span></label>
                <textarea name="uraian_mahasiswa" rows="4" required placeholder="Tuliskan poin-poin yang sudah dikerjakan atau pertanyaan/masalah yang dikonsultasikan kepada dosen..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500">{{ old('uraian_mahasiswa') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload File Draft (PDF / DOCX / ZIP, Maks 15MB)</label>
                <input type="file" name="file_draft" accept=".pdf,.doc,.docx,.zip"
                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('bimbingan.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/30 transition">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Catatan Bimbingan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

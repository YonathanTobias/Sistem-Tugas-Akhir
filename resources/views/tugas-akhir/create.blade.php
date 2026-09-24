@extends('layouts.app')

@section('title', 'Form Pengajuan Judul Tugas Akhir')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <h2 class="text-xl font-black text-slate-800">Form Pengajuan Judul & Proposal Skripsi</h2>
            <p class="text-xs text-slate-500 mt-1">Lengkapi informasi judul dan rencana penelitian tugas akhir Anda.</p>
        </div>

        <form action="{{ route('tugas-akhir.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Judul Tugas Akhir / Skripsi <span class="text-rose-500">*</span>
                </label>
                <textarea name="judul" rows="3" required placeholder="Tuliskan judul skripsi secara lengkap dan jelas..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500 transition">{{ old('judul', $existingTA->judul ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Bidang Kajian / Minat Riset <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="bidang_kajian" value="{{ old('bidang_kajian', $existingTA->bidang_kajian ?? '') }}" required
                    placeholder="misal: Artificial Intelligence, Web Engineering, Data Mining, IoT"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Abstrak / Rencana Penelitian (Latar Belakang & Metodologi) <span class="text-rose-500">*</span>
                </label>
                <textarea name="abstrak" rows="5" required placeholder="Jelaskan secara singkat latar belakang masalah, urgensi, metode yang digunakan, serta target luaran yang diharapkan..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500 transition">{{ old('abstrak', $existingTA->abstrak ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Usulan Dosen Pembimbing (Opsional)
                </label>
                <select name="usulan_pembimbing1_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-emerald-500 transition">
                    <option value="">-- Rekomendasikan Pembimbing (Akan diputuskan Prodi) --</option>
                    @foreach($dosens as $d)
                    <option value="{{ $d->id }}" {{ old('usulan_pembimbing1_id', $existingTA->pembimbing1_id ?? '') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_lengkap }} {{ $d->gelar ? ', ' . $d->gelar : '' }} (Keahlian: {{ $d->bidang_keahlian ?? '-' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Upload Berkas Proposal Awal / Kerangka Acuan (PDF / DOCX, Maks 10MB)
                </label>
                <input type="file" name="file_proposal" accept=".pdf,.doc,.docx"
                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('tugas-akhir.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/30 transition">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Ajukan Judul
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Tugas Akhir')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Detail Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        
        <!-- Top Status & Student Info -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi Mahasiswa</span>
                <h3 class="text-lg font-black text-slate-800">{{ $ta->mahasiswa->nama_lengkap }}</h3>
                <p class="text-xs text-slate-500">NIM: <span class="font-mono font-bold text-slate-700">{{ $ta->mahasiswa->nim }}</span> | Prodi: <span class="font-semibold text-blue-700">{{ $ta->mahasiswa->prodi->nama_prodi ?? 'Prodi' }}</span> | No. HP: {{ $ta->mahasiswa->no_hp ?? '-' }}</p>
            </div>
            <div>
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wide
                    {{ $ta->status === 'disetujui' || $ta->status === 'lulus_sidang' || $ta->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $ta->status === 'pengajuan' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $ta->status === 'bimbingan_skripsi' || $ta->status === 'bimbingan_proposal' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $ta->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                ">
                    {{ str_replace('_', ' ', $ta->status) }}
                </span>
            </div>
        </div>

        <!-- Judul & Bidang -->
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Judul Penelitian</span>
            <h2 class="text-xl font-extrabold text-slate-900 mt-1 leading-snug">{{ $ta->judul }}</h2>
            <div class="mt-2 text-xs text-blue-700 font-semibold bg-blue-50 px-3 py-1 rounded-lg inline-block border border-blue-100">
                Bidang Kajian: {{ $ta->bidang_kajian }}
            </div>
        </div>

        <!-- Abstrak -->
        <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Abstrak & Rencana Riset</h4>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $ta->abstrak }}
            </div>
        </div>

        @if($ta->file_proposal)
        <div>
            <a href="{{ asset('storage/' . $ta->file_proposal) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                <i class="fa-solid fa-file-pdf mr-2 text-rose-600"></i> Unduh File Proposal Awal
            </a>
        </div>
        @endif

        <!-- Plotting & Update Form (Khusus Admin / Koordinator TA) -->
        @if(auth()->user()->isAdmin())
        <div class="mt-8 pt-6 border-t border-slate-200 space-y-4">
            <h3 class="font-extrabold text-sm text-slate-900 uppercase tracking-wider">Panel Verifikasi & Plotting Dosen Pembimbing (Prodi)</h3>
            
            <form action="{{ route('tugas-akhir.update-status', $ta->id) }}" method="POST" class="space-y-4 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Dosen Pembimbing 1 (Utama)</label>
                        <select name="pembimbing1_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                            <option value="">-- Pilih Pembimbing 1 --</option>
                            @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ $ta->pembimbing1_id == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_lengkap }} {{ $d->gelar ? ', ' . $d->gelar : '' }} (Keahlian: {{ $d->bidang_keahlian ?? '-' }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Dosen Pembimbing 2 (Pendamping)</label>
                        <select name="pembimbing2_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                            <option value="">-- Pilih Pembimbing 2 (Opsional) --</option>
                            @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ $ta->pembimbing2_id == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_lengkap }} {{ $d->gelar ? ', ' . $d->gelar : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ubah Status Tugas Akhir</label>
                        <select name="status" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-blue-500">
                            <option value="pengajuan" {{ $ta->status === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                            <option value="disetujui" {{ $ta->status === 'disetujui' ? 'selected' : '' }}>Disetujui (ACC)</option>
                            <option value="bimbingan_proposal" {{ $ta->status === 'bimbingan_proposal' ? 'selected' : '' }}>Bimbingan Proposal</option>
                            <option value="siap_sempro" {{ $ta->status === 'siap_sempro' ? 'selected' : '' }}>Siap Sempro</option>
                            <option value="lulus_sempro" {{ $ta->status === 'lulus_sempro' ? 'selected' : '' }}>Lulus Sempro</option>
                            <option value="bimbingan_skripsi" {{ $ta->status === 'bimbingan_skripsi' ? 'selected' : '' }}>Bimbingan Skripsi</option>
                            <option value="siap_sidang" {{ $ta->status === 'siap_sidang' ? 'selected' : '' }}>Siap Sidang Akhir</option>
                            <option value="lulus_sidang" {{ $ta->status === 'lulus_sidang' ? 'selected' : '' }}>Lulus Sidang Akhir</option>
                            <option value="selesai" {{ $ta->status === 'selesai' ? 'selected' : '' }}>Selesai / Lulus</option>
                            <option value="revisi_judul" {{ $ta->status === 'revisi_judul' ? 'selected' : '' }}>Minta Revisi Judul</option>
                            <option value="ditolak" {{ $ta->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan / Arahan Prodi</label>
                        <input type="text" name="catatan_prodi" value="{{ $ta->catatan_prodi }}" placeholder="Catatan untuk mahasiswa atau dosen..."
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Plotting & Perubahan
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>

</div>
@endsection

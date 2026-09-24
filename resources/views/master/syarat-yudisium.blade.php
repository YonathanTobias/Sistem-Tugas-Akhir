@extends('layouts.app')

@section('title', 'Master Syarat Bebas Tanggungan')

@section('content')
<div class="space-y-6" x-data="{ modalAdd: false }">

    <!-- Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Master Syarat Bebas Tanggungan Yudisium</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola daftar dokumen wajib dan opsional yang harus diunggah mahasiswa.</p>
        </div>
        <button @click="modalAdd = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/30 transition">
            <i class="fa-solid fa-plus-circle mr-1.5"></i> Tambah Syarat Baru
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($syarats as $s)
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-extrabold uppercase">
                    {{ $s->kategori }}
                </span>
                <span class="text-xs font-bold font-mono text-slate-400">{{ $s->kode_syarat }}</span>
            </div>

            <div>
                <h3 class="font-extrabold text-sm text-slate-900">{{ $s->nama_syarat }}</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $s->deskripsi }}</p>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-bold">
                <span>Status Kewajiban:</span>
                @if($s->is_wajib)
                <span class="text-rose-600 font-extrabold"><i class="fa-solid fa-circle-exclamation mr-1"></i> Wajib Dilengkapi</span>
                @else
                <span class="text-slate-400 font-medium">Opsional</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal Add Syarat -->
    <div x-show="modalAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalAdd = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-base text-slate-800">Tambah Syarat Bebas Tanggungan</h3>
                <button @click="modalAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('master.syarat-yudisium.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Nama Persyaratan Dokumen *</label>
                    <input type="text" name="nama_syarat" required placeholder="misal: Surat Bebas Perpustakaan & Upload Repository" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Kode Unik *</label>
                        <input type="text" name="kode_syarat" required placeholder="misal: BEBAS_PERPUS" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-mono uppercase">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Kategori Bagian *</label>
                        <select name="kategori" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                            <option value="perpustakaan">Perpustakaan</option>
                            <option value="keuangan">Keuangan / BAAK</option>
                            <option value="laboratorium">Laboratorium</option>
                            <option value="akademik">Akademik</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Deskripsi / Petunjuk untuk Mahasiswa</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan detail berkas yang harus diunggah..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl"></textarea>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                        <input type="checkbox" name="is_wajib" value="1" checked class="w-4 h-4 text-emerald-600 rounded">
                        <span>Wajib diunggah sebelum yudisium disetujui</span>
                    </label>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="modalAdd = false" class="px-4 py-2 text-slate-500 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow">Simpan Syarat</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

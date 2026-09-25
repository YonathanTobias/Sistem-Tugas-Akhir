@extends('layouts.app')

@section('title', 'Pengaturan & Sistem Multi-Prodi')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 border border-blue-900/40">
        <div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-sliders text-xs text-blue-400"></i> Pusat Konfigurasi Program Studi
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-3 tracking-tight">Pengaturan Cepat 3 Program Studi STIKes</h2>
            <p class="text-slate-300 text-sm mt-1">Kelola koordinator/kaprodi, format penomoran SK, gelar lulusan, dan standar minimal bimbingan tiap program studi.</p>
        </div>
    </div>

    <!-- Active Prodi Scope Alert -->
    @if(session('active_prodi_id'))
    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-950 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                <i class="fa-solid fa-filter"></i>
            </div>
            <div>
                <strong class="font-bold text-sm block">Sistem Sedang Difokuskan pada Prodi: {{ session('active_prodi_nama') }}</strong>
                <p class="text-xs text-blue-700">Tampilan dashboard, daftar TA, jadwal sidang, dan yudisium disaring khusus prodi ini.</p>
            </div>
        </div>
        <form action="{{ route('master.prodi.switch') }}" method="POST">
            @csrf
            <input type="hidden" name="prodi_id" value="all">
            <button type="submit" class="px-4 py-2 bg-white text-blue-900 hover:bg-blue-100 font-bold text-xs rounded-xl border border-blue-300 shadow-sm transition">
                Tampilkan Semua Prodi
            </button>
        </form>
    </div>
    @endif

    <!-- 3 Prodi Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($prodis as $prodi)
        <div class="bg-white rounded-3xl border {{ session('active_prodi_id') == $prodi->id ? 'border-2 border-blue-600 shadow-xl ring-4 ring-blue-50' : 'border-slate-200/80 shadow-sm' }} p-6 space-y-5 flex flex-col justify-between" x-data="{ editMode: false }">
            
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-slate-100 text-slate-800 font-mono">
                        {{ $prodi->kode_prodi }} ({{ $prodi->jenjang }})
                    </span>
                    @if(session('active_prodi_id') == $prodi->id)
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 font-extrabold text-[10px] uppercase">
                        Active Scope
                    </span>
                    @endif
                </div>

                <div>
                    <h3 class="text-lg font-black text-slate-900 leading-snug">{{ $prodi->nama_prodi }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $prodi->fakultas }}</p>
                </div>

                <!-- Quick Stats Per Prodi -->
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                        <span class="text-slate-400 font-bold uppercase block text-[10px]">Mahasiswa</span>
                        <p class="font-extrabold text-slate-800 text-base mt-0.5">{{ $prodi->mahasiswas_count }} Org</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                        <span class="text-slate-400 font-bold uppercase block text-[10px]">Dosen</span>
                        <p class="font-extrabold text-slate-800 text-base mt-0.5">{{ $prodi->dosens_count }} Dosen</p>
                    </div>
                </div>

                <!-- Display Mode -->
                <div x-show="!editMode" class="space-y-2.5 text-xs text-slate-700 pt-2">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase block">Ketua Program Studi (Kaprodi):</span>
                        <p class="font-bold text-slate-900">{{ $prodi->kaprodi_nama ?? 'Belum Diatur' }}</p>
                        <p class="text-[10px] text-slate-500 font-mono">NIDN/NIP: {{ $prodi->kaprodi_nip ?? '-' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Gelar Lulusan:</span>
                            <span class="font-bold text-blue-700">{{ $prodi->gelar_lulusan }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Min. ACC Bimbingan:</span>
                            <span class="font-bold text-slate-800">{{ $prodi->min_bimbingan_acc }}x Pertemuan</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase block">Prefix SK Yudisium:</span>
                        <span class="font-mono text-slate-700 font-semibold bg-slate-100 px-2 py-0.5 rounded">{{ $prodi->format_sk_prefix ?? 'SK-YUD/' . $prodi->kode_prodi . '/' }}</span>
                    </div>
                </div>

                <!-- Edit Form Mode -->
                <div x-show="editMode" x-cloak class="pt-2">
                    <form action="{{ route('master.prodi.update-setting', $prodi->id) }}" method="POST" class="space-y-3 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Nama Kaprodi & Gelar</label>
                            <input type="text" name="kaprodi_nama" value="{{ $prodi->kaprodi_nama }}" required class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">NIDN / NIP Kaprodi</label>
                            <input type="text" name="kaprodi_nip" value="{{ $prodi->kaprodi_nip }}" placeholder="misal: 0712048001" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl font-mono focus:outline-none focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Gelar Lulusan</label>
                                <input type="text" name="gelar_lulusan" value="{{ $prodi->gelar_lulusan }}" required class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Min. ACC</label>
                                <input type="number" name="min_bimbingan_acc" value="{{ $prodi->min_bimbingan_acc }}" min="1" required class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Format Prefix SK Yudisium</label>
                            <input type="text" name="format_sk_prefix" value="{{ $prodi->format_sk_prefix }}" required class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl font-mono focus:outline-none focus:border-blue-500">
                        </div>

                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" @click="editMode = false" class="px-3 py-1.5 text-slate-500 font-bold">Batal</button>
                            <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-md shadow-blue-600/30 transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                <form action="{{ route('master.prodi.switch') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">
                    <button type="submit" class="w-full py-2 px-3 text-center rounded-xl font-bold text-xs {{ session('active_prodi_id') == $prodi->id ? 'bg-blue-600 text-white' : 'bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-700' }} transition">
                        <i class="fa-solid fa-eye mr-1"></i> {{ session('active_prodi_id') == $prodi->id ? 'Sedang Aktif' : 'Fokuskan Sistem' }}
                    </button>
                </form>

                <button @click="editMode = !editMode" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition shrink-0" title="Edit Pengaturan">
                    <i class="fa-solid fa-gear"></i>
                </button>
            </div>

        </div>
        @endforeach
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Master Data Dosen')

@section('content')
<div class="space-y-6" x-data="{ modalAdd: false }">

    <!-- Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Master Data Dosen Pembimbing</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola data dosen, keahlian, dan kuota bimbingan skripsi mahasiswa.</p>
        </div>
        <button @click="modalAdd = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/30 transition">
            <i class="fa-solid fa-plus-circle mr-1.5"></i> Tambah Dosen Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama & Gelar</th>
                        <th class="px-6 py-4">NIDN / NIP</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4">Bidang Keahlian</th>
                        <th class="px-6 py-4 text-center">Bimbingan Aktif / Kuota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dosens as $d)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $d->nama_lengkap }} {{ $d->gelar ? ', ' . $d->gelar : '' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $d->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-slate-800">
                            {{ $d->nidn }}
                            <div class="text-[10px] text-slate-400 font-normal">NIP: {{ $d->nip ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $d->prodi->nama_prodi ?? 'TI' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 font-semibold text-[11px]">
                                {{ $d->bidang_keahlian ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-800">
                            <span class="text-emerald-600">{{ $d->bimbinganTugasAkhir1->count() + $d->bimbinganTugasAkhir2->count() }}</span> / {{ $d->kuota_bimbingan }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada data dosen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dosens->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $dosens->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Add Dosen -->
    <div x-show="modalAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalAdd = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-base text-slate-800">Tambah Dosen Baru</h3>
                <button @click="modalAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('master.dosen.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required placeholder="misal: Dr. Budi Santoso" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Gelar Akademik</label>
                        <input type="text" name="gelar" placeholder="misal: M.Kom., Ph.D." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">NIDN (Username Login) *</label>
                        <input type="text" name="nidn" required placeholder="misal: 0012058001" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">NIP</label>
                        <input type="text" name="nip" placeholder="misal: 19800512..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Email Kampus / Akun *</label>
                    <input type="email" name="email" required placeholder="misal: dosen@kampus.ac.id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Program Studi *</label>
                        <select name="prodi_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                            @foreach($prodis as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Kuota Bimbingan *</label>
                        <input type="number" name="kuota_bimbingan" value="10" min="1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Bidang Keahlian / Riset</label>
                    <input type="text" name="bidang_keahlian" placeholder="misal: Machine Learning, Cloud Computing" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="modalAdd = false" class="px-4 py-2 text-slate-500 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow">Simpan Dosen</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

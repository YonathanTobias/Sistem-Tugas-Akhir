@extends('layouts.app')

@section('title', 'Master Periode Yudisium')

@section('content')
<div class="space-y-6" x-data="{ modalAdd: false }">

    <!-- Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Master Periode Pendaftaran Yudisium</h2>
            <p class="text-xs text-slate-500 mt-1">Buka gelombang dan jadwal pelaksanaan yudisium untuk mahasiswa.</p>
        </div>
        <button @click="modalAdd = true" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
            <i class="fa-solid fa-plus-circle mr-1.5"></i> Buka Periode Baru
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Periode</th>
                        <th class="px-6 py-4">Tahun Akademik</th>
                        <th class="px-6 py-4">Masa Pendaftaran</th>
                        <th class="px-6 py-4">Tanggal Pelaksanaan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($periodes as $p)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 text-sm">
                            {{ $p->nama_periode }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">
                            {{ $p->tahun_akademik }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $p->tgl_buka->format('d/m/Y') }} s.d. {{ $p->tgl_tutup->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $p->tgl_pelaksanaan->format('l, d F Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($p->is_aktif)
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 uppercase">
                                ✅ Periode Aktif
                            </span>
                            @else
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                Selesai
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada data periode yudisium.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add Periode -->
    <div x-show="modalAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalAdd = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-base text-slate-800">Buka Periode Yudisium Baru</h3>
                <button @click="modalAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('master.periode-yudisium.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Nama Periode *</label>
                    <input type="text" name="nama_periode" required placeholder="misal: Yudisium STIKes Periode Genap 2026/2027" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Tahun Akademik *</label>
                        <input type="text" name="tahun_akademik" value="2026/2027" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Kuota Peserta</label>
                        <input type="number" name="kuota" placeholder="misal: 150" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Mulai Buka *</label>
                        <input type="date" name="tgl_buka" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Batas Akhir Pendaftaran *</label>
                        <input type="date" name="tgl_tutup" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Pelaksanaan Yudisium *</label>
                    <input type="date" name="tgl_pelaksanaan" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                        <input type="checkbox" name="is_aktif" value="1" checked class="w-4 h-4 text-blue-600 rounded">
                        <span>Aktifkan periode ini sebagai periode berjalan</span>
                    </label>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="modalAdd = false" class="px-4 py-2 text-slate-500 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-md shadow-blue-600/30 transition">Buka Periode</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

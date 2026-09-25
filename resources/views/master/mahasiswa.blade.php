@extends('layouts.app')

@section('title', 'Master Data Mahasiswa')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
        <h2 class="text-xl font-black text-slate-800">Master Data Mahasiswa</h2>
        <p class="text-xs text-slate-500 mt-1">Daftar seluruh mahasiswa yang terdaftar dalam sistem SIMTA & Yudisium STIKes Panti Waluya.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">NIM & Nama Mahasiswa</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4">Angkatan / Semester</th>
                        <th class="px-6 py-4">IPK & SKS</th>
                        <th class="px-6 py-4">Status Tugas Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mahasiswas as $m)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $m->nama_lengkap }}</div>
                            <div class="font-mono text-blue-600 font-bold">{{ $m->nim }}</div>
                            <div class="text-[10px] text-slate-400">{{ $m->user->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $m->prodi->nama_prodi ?? 'Prodi' }} ({{ $m->prodi->jenjang ?? 'S1' }})
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">
                            Angkatan {{ $m->angkatan }} &bull; Semester {{ $m->semester }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-slate-800">IPK: {{ number_format($m->ipk, 2) }}</span>
                            <div class="text-[10px] text-slate-400">Total SKS: {{ $m->total_sks }} SKS</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($m->tugasAkhir)
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase
                                {{ $m->tugasAkhir->status === 'disetujui' || $m->tugasAkhir->status === 'lulus_sidang' || $m->tugasAkhir->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ str_replace('_', ' ', $m->tugasAkhir->status) }}
                            </span>
                            @else
                            <span class="text-slate-400 italic">Belum Mengajukan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada data mahasiswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mahasiswas->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $mahasiswas->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

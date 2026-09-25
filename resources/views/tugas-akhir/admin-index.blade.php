@extends('layouts.app')

@section('title', 'Manajemen Tugas Akhir & Plotting Pembimbing')

@section('content')
<div class="space-y-6">

    <!-- Filters & Search -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('tugas-akhir.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, nama, atau NIM..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
            </div>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                <option value="">-- Semua Status --</option>
                <option value="pengajuan" {{ request('status') === 'pengajuan' ? 'selected' : '' }}>Pengajuan Baru</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="bimbingan_skripsi" {{ request('status') === 'bimbingan_skripsi' ? 'selected' : '' }}>Bimbingan Skripsi</option>
                <option value="lulus_sidang" {{ request('status') === 'lulus_sidang' ? 'selected' : '' }}>Lulus Sidang</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition shadow-sm">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('tugas-akhir.index') }}" class="text-xs text-rose-500 font-semibold hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Judul & Bidang Kajian</th>
                        <th class="px-6 py-4">Dosen Pembimbing</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tugasAkhirs as $ta)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $ta->mahasiswa->nama_lengkap }}</div>
                            <div class="text-slate-400 font-mono">{{ $ta->mahasiswa->nim }}</div>
                            <div class="text-[11px] text-blue-600 font-semibold">{{ $ta->mahasiswa->prodi->nama_prodi ?? 'Prodi' }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-md">
                            <a href="{{ route('tugas-akhir.show', $ta->id) }}" class="font-bold text-slate-800 hover:text-blue-600 line-clamp-2 transition leading-snug">
                                {{ $ta->judul }}
                            </a>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-semibold">
                                {{ $ta->bidang_kajian }}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-y-1">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">P1:</span>
                                <span class="font-semibold text-slate-800">{{ $ta->pembimbing1->nama_lengkap ?? 'Belum Diplot' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">P2:</span>
                                <span class="text-slate-600">{{ $ta->pembimbing2->nama_lengkap ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 text-[11px] font-bold rounded-lg uppercase
                                {{ $ta->status === 'disetujui' || $ta->status === 'lulus_sidang' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $ta->status === 'pengajuan' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $ta->status === 'bimbingan_skripsi' || $ta->status === 'bimbingan_proposal' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $ta->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                            ">
                                {{ str_replace('_', ' ', $ta->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('tugas-akhir.show', $ta->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold rounded-xl text-xs transition shadow-sm">
                                <i class="fa-solid fa-eye mr-1.5"></i> Detail / Plot
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                            Tidak ada data tugas akhir ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tugasAkhirs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $tugasAkhirs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

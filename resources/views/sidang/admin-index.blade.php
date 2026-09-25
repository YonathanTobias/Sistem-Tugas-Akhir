@extends('layouts.app')

@section('title', 'Manajemen Penjadwalan & Sidang TA')

@section('content')
<div class="space-y-6">

    <!-- Filters -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('sidang.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="jenis" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">-- Semua Jenis Sidang --</option>
                <option value="sempro" {{ request('jenis') === 'sempro' ? 'selected' : '' }}>Seminar Proposal (Sempro)</option>
                <option value="sidang_akhir" {{ request('jenis') === 'sidang_akhir' ? 'selected' : '' }}>Sidang Akhir Skripsi</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">-- Semua Status --</option>
                <option value="menunggu_jadwal" {{ request('status') === 'menunggu_jadwal' ? 'selected' : '' }}>⏳ Menunggu Jadwal</option>
                <option value="dijadwalkan" {{ request('status') === 'dijadwalkan' ? 'selected' : '' }}>📅 Dijadwalkan</option>
                <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>✅ Lulus</option>
                <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>⚠️ Revisi</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition shadow-sm">
                Filter
            </button>
            @if(request()->hasAny(['jenis', 'status']))
            <a href="{{ route('sidang.index') }}" class="text-xs text-rose-500 font-semibold hover:underline">Reset</a>
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
                        <th class="px-6 py-4">Jenis & Judul Skripsi</th>
                        <th class="px-6 py-4">Jadwal & Ruangan</th>
                        <th class="px-6 py-4">Dewan Penguji</th>
                        <th class="px-6 py-4">Status & Nilai</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sidangs as $s)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $s->tugasAkhir->mahasiswa->nama_lengkap }}
                            <div class="text-[11px] text-slate-400 font-mono">{{ $s->tugasAkhir->mahasiswa->nim }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <span class="inline-block px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase {{ $s->jenis === 'sempro' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $s->jenis === 'sempro' ? 'Sempro' : 'Sidang Akhir' }}
                            </span>
                            <p class="font-bold text-slate-800 mt-1 line-clamp-1">{{ $s->tugasAkhir->judul }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($s->tgl_sidang)
                            <div class="font-bold text-slate-800">{{ $s->tgl_sidang->format('d/m/Y') }}</div>
                            <div class="text-[11px] text-slate-500">{{ substr($s->jam_mulai, 0, 5) }} WIB - {{ $s->ruangan }}</div>
                            @else
                            <span class="text-amber-600 font-semibold italic">Belum Dijadwalkan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 space-y-0.5 text-[11px]">
                            <p><strong>1:</strong> {{ $s->penguji1->nama_lengkap ?? '-' }}</p>
                            <p><strong>2:</strong> {{ $s->penguji2->nama_lengkap ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase
                                {{ $s->status === 'lulus' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $s->status === 'dijadwalkan' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $s->status === 'menunggu_jadwal' ? 'bg-amber-100 text-amber-700' : '' }}
                            ">
                                {{ str_replace('_', ' ', $s->status) }}
                            </span>
                            @if($s->nilai_akhir)
                            <div class="text-xs font-black text-emerald-700 mt-1">{{ number_format($s->nilai_akhir, 1) }} ({{ $s->grade_huruf }})</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('sidang.show', $s->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold rounded-xl text-xs transition shadow-sm">
                                <i class="fa-solid fa-calendar-check mr-1.5"></i> Detail / Nilai
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">Tidak ada jadwal sidang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sidangs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $sidangs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

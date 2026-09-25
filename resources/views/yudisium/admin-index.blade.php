@extends('layouts.app')

@section('title', 'Manajemen & Verifikasi Yudisium')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('yudisium.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="periode_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">-- Semua Periode Yudisium --</option>
                @foreach($periodes as $p)
                <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama_periode }} {{ $p->is_aktif ? '(Aktif)' : '' }}
                </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">-- Semua Status --</option>
                <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>⏳ Berkas Masuk</option>
                <option value="diverifikasi" {{ request('status') === 'diverifikasi' ? 'selected' : '' }}>📋 Terverifikasi Lengkap</option>
                <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>🎓 Lulus Yudisium</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition shadow-sm">
                Filter
            </button>
            @if(request()->hasAny(['periode_id', 'status']))
            <a href="{{ route('yudisium.index') }}" class="text-xs text-rose-500 font-semibold hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Pendaftar Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Periode Yudisium</th>
                        <th class="px-6 py-4">IPK & Predikat</th>
                        <th class="px-6 py-4">Status Berkas</th>
                        <th class="px-6 py-4">Keputusan Kelulusan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendaftarans as $p)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $p->mahasiswa->nama_lengkap }}
                            <div class="text-[11px] text-slate-400 font-mono">{{ $p->mahasiswa->nim }}</div>
                            <div class="text-[10px] text-blue-600 font-semibold">{{ $p->mahasiswa->prodi->nama_prodi ?? 'Prodi' }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $p->periodeYudisium->nama_periode }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-black text-slate-800 text-sm">{{ number_format($p->ipk_final, 2) }}</span>
                            @if($p->predikat)
                            <div class="text-[11px] text-emerald-700 font-bold">{{ $p->predikat }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $validCount = $p->berkasYudisiums->where('status', 'valid')->count();
                                $totalUploaded = $p->berkasYudisiums->count();
                            @endphp
                            <span class="font-bold text-slate-800">{{ $validCount }} Berkas Valid</span>
                            <div class="text-[10px] text-slate-400">Total Upload: {{ $totalUploaded }} berkas</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase
                                {{ $p->status === 'lulus' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $p->status === 'diverifikasi' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $p->status === 'diajukan' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $p->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                            ">
                                {{ strtoupper($p->status) }}
                            </span>
                            @if($p->nomor_sk)
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $p->nomor_sk }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('yudisium.show', $p->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 font-bold rounded-xl text-xs transition shadow-sm">
                                <i class="fa-solid fa-clipboard-check mr-1.5"></i> Verifikasi Berkas
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">Tidak ada data pendaftar yudisium.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendaftarans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $pendaftarans->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

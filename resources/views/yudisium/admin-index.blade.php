@extends('layouts.app')

@section('title', 'Manajemen & Verifikasi Yudisium')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('yudisium.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="periode_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                <option value="">-- Semua Periode Yudisium --</option>
                @foreach($periodes as $p)
                <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama_periode }} {{ $p->is_aktif ? '(Aktif)' : '' }}
                </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                <option value="">-- Semua Status --</option>
                <option value="diajukan" {{ request('status') === 'diajukan' ? 'selected' : '' }}>⏳ Berkas Masuk</option>
                <option value="diverifikasi" {{ request('status') === 'diverifikasi' ? 'selected' : '' }}>📋 Terverifikasi Lengkap</option>
                <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>🎓 Lulus & Ber-SKL</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition">
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
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $p->mahasiswa->nama_lengkap }}
                            <div class="text-[11px] text-slate-400 font-mono">{{ $p->mahasiswa->nim }}</div>
                            <div class="text-[10px] text-emerald-600 font-semibold">{{ $p->mahasiswa->prodi->nama_prodi ?? 'TI' }}</div>
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
                                {{ $p->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : '' }}
                                {{ $p->status === 'diajukan' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $p->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                            ">
                                {{ strtoupper($p->status) }}
                            </span>
                            @if($p->nomor_sk)
                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $p->nomor_sk }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('yudisium.show', $p->id) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 font-bold rounded-xl text-xs transition">
                                <i class="fa-solid fa-clipboard-check mr-1.5"></i> Periksa / SK
                            </a>
                            @if($p->status === 'lulus')
                            <a href="{{ route('yudisium.cetak-skl', $p->id) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-bold transition" title="Cetak SKL">
                                <i class="fa-solid fa-print"></i>
                            </a>
                            @endif
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

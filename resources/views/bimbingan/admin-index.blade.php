@extends('layouts.app')

@section('title', 'Monitoring Seluruh Logbook Bimbingan')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
        <h2 class="text-xl font-black text-slate-800">Monitoring Logbook Bimbingan Mahasiswa</h2>
        <p class="text-xs text-slate-500 mt-1">Pantau keaktifan bimbingan dan interaksi antara mahasiswa serta dosen pembimbing.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Dosen Pembimbing</th>
                        <th class="px-6 py-4">Bab & Bahasan</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bimbingans as $b)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $b->tugasAkhir->mahasiswa->nama_lengkap }}
                            <div class="text-[11px] text-slate-400 font-mono">{{ $b->tugasAkhir->mahasiswa->nim }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $b->dosen->nama_lengkap }}
                        </td>
                        <td class="px-6 py-4 max-w-sm">
                            <span class="font-bold text-slate-800 uppercase text-[10px] bg-slate-100 px-1.5 py-0.5 rounded">{{ $b->bab }}</span>
                            <p class="text-slate-700 font-medium mt-0.5">{{ $b->topik_bimbingan }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                            {{ $b->tgl_bimbingan->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase
                                {{ $b->status === 'acc' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $b->status === 'revisi' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $b->status === 'menunggu' ? 'bg-slate-100 text-slate-700' : '' }}
                            ">
                                {{ $b->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada riwayat bimbingan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bimbingans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $bimbingans->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

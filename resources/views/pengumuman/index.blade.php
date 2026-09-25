@extends('layouts.app')

@section('title', 'Pusat Pengumuman & Informasi Kampus')

@section('content')
<div class="space-y-6" x-data="{ modalAdd: false }">

    <!-- Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Pengumuman & Edaran Akademik</h2>
            <p class="text-xs text-slate-500 mt-1">Informasi penting mengenai jadwal tugas akhir, seminar, dan yudisium STIKes Panti Waluya.</p>
        </div>
        @if(auth()->user()->isAdmin())
        <button @click="modalAdd = true" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
            <i class="fa-solid fa-bullhorn mr-1.5"></i> Buat Pengumuman
        </button>
        @endif
    </div>

    <!-- Announcement List -->
    <div class="space-y-4">
        @forelse($pengumumans as $p)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-3 hover:shadow-md transition">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    @if($p->is_pinned)
                    <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 font-bold text-[10px] uppercase flex items-center gap-1">
                        <i class="fa-solid fa-thumbtack text-amber-600 text-[10px]"></i> Pinned
                    </span>
                    @endif
                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 font-bold text-[10px] uppercase">
                        {{ $p->kategori }}
                    </span>
                    <span class="text-xs text-slate-400">Target: <strong class="uppercase font-semibold text-slate-600">{{ $p->target_role }}</strong></span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-400">{{ $p->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-rose-500 p-1 transition" title="Hapus">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="font-extrabold text-base text-slate-900">{{ $p->judul }}</h3>
                <p class="text-xs text-slate-700 mt-2 leading-relaxed whitespace-pre-line">{{ $p->konten }}</p>
            </div>

            <div class="pt-2 text-[11px] text-slate-400 flex items-center gap-1">
                <i class="fa-solid fa-user-circle text-slate-400"></i>
                <span>Diterbitkan oleh: <strong class="text-slate-700">{{ $p->author->name ?? 'Admin STIKes' }}</strong></span>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center text-slate-400 text-xs shadow-sm">
            <i class="fa-solid fa-bullhorn text-4xl mb-3 text-slate-300"></i>
            <p>Belum ada pengumuman yang diterbitkan.</p>
        </div>
        @endforelse
    </div>

    @if($pengumumans->hasPages())
    <div class="px-6 py-4">
        {{ $pengumumans->links() }}
    </div>
    @endif

    <!-- Modal Add Pengumuman -->
    @if(auth()->user()->isAdmin())
    <div x-show="modalAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="modalAdd = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-base text-slate-800">Terbitkan Pengumuman Baru</h3>
                <button @click="modalAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('pengumuman.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Judul Pengumuman *</label>
                    <input type="text" name="judul" required placeholder="misal: Batas Akhir Upload Berkas Yudisium" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Kategori *</label>
                        <select name="kategori" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-blue-500">
                            <option value="Tugas Akhir">Tugas Akhir</option>
                            <option value="Seminar & Sidang">Seminar & Sidang</option>
                            <option value="Yudisium">Yudisium</option>
                            <option value="Akademik Umum">Akademik Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Target Sasaran *</label>
                        <select name="target_role" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:outline-none focus:border-blue-500">
                            <option value="all">Semua Pengguna</option>
                            <option value="mahasiswa">Khusus Mahasiswa</option>
                            <option value="dosen">Khusus Dosen</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Isi Pengumuman *</label>
                    <textarea name="konten" rows="5" required placeholder="Tuliskan isi pengumuman lengkap..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-700">
                        <input type="checkbox" name="is_pinned" value="1" class="w-4 h-4 text-blue-600 rounded">
                        <span>Sematkan / Pin Pengumuman di Atas (Prioritas)</span>
                    </label>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" @click="modalAdd = false" class="px-4 py-2 text-slate-500 font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-md shadow-blue-600/30 transition">Terbitkan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection

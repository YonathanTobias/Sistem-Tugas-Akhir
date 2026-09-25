@extends('layouts.app')

@section('title', 'Verifikasi Berkas & Penetapan Kelulusan Yudisium')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Mahasiswa & Status Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wide bg-blue-100 text-blue-700">
                    {{ $pendaftaran->periodeYudisium->nama_periode }}
                </span>
                <h2 class="text-xl font-black text-slate-800 mt-2">{{ $pendaftaran->mahasiswa->nama_lengkap }}</h2>
                <p class="text-xs text-slate-500">NIM: <span class="font-mono font-bold">{{ $pendaftaran->mahasiswa->nim }}</span> | Prodi: <span class="font-semibold text-blue-700">{{ $pendaftaran->mahasiswa->prodi->nama_prodi ?? 'Prodi' }}</span> | IPK: <strong>{{ number_format($pendaftaran->ipk_final, 2) }}</strong></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wide
                    {{ $pendaftaran->status === 'lulus' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $pendaftaran->status === 'diverifikasi' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $pendaftaran->status === 'diajukan' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $pendaftaran->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                ">
                    {{ $pendaftaran->status === 'lulus' ? 'LULUS YUDISIUM' : strtoupper($pendaftaran->status) }}
                </span>
            </div>
        </div>

        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Judul Tugas Akhir / Skripsi</span>
            <h3 class="text-base font-extrabold text-slate-800 mt-0.5 leading-snug">{{ $pendaftaran->tugasAkhir->judul ?? '-' }}</h3>
        </div>

        <!-- Berkas Bebas Tanggungan Verification Section -->
        <div class="border-t border-slate-100 pt-6 space-y-4">
            <h3 class="font-extrabold text-sm text-slate-900 uppercase tracking-wider">Verifikasi Berkas Bebas Tanggungan Mahasiswa</h3>
            
            <div class="space-y-4">
                @foreach($syarats as $syarat)
                @php
                    $berkas = $pendaftaran->berkasYudisiums->where('syarat_yudisium_id', $syarat->id)->first();
                @endphp
                <div class="p-4 rounded-2xl border {{ $berkas && $berkas->status === 'valid' ? 'bg-emerald-50/40 border-emerald-200' : ($berkas && $berkas->status === 'ditolak' ? 'bg-rose-50/40 border-rose-200' : 'bg-slate-50 border-slate-200') }} space-y-3">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase">{{ $syarat->kategori }}</span>
                                <h4 class="font-bold text-xs text-slate-900">{{ $syarat->nama_syarat }}</h4>
                                @if($syarat->is_wajib)
                                <span class="text-rose-600 font-bold text-[10px]">*Wajib</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $syarat->deskripsi }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($berkas)
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase
                                {{ $berkas->status === 'valid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $berkas->status === 'menunggu' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $berkas->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                            ">
                                {{ $berkas->status }}
                            </span>
                            <a href="{{ asset('storage/' . $berkas->file_path) }}" target="_blank" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl transition">
                                <i class="fa-solid fa-file-pdf mr-1"></i> Buka Dokumen
                            </a>
                            @else
                            <span class="text-xs text-slate-400 italic">Belum diunggah</span>
                            @endif
                        </div>
                    </div>

                    <!-- Validator Verification Form -->
                    @if($berkas && auth()->user()->isAdmin())
                    <form action="{{ route('yudisium.verify-berkas', $berkas->id) }}" method="POST" class="pt-2 border-t border-slate-200/60 flex flex-col sm:flex-row items-center gap-3">
                        @csrf
                        @method('PATCH')
                        
                        <div class="w-full sm:w-1/3">
                            <select name="status" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-blue-500">
                                <option value="valid" {{ $berkas->status === 'valid' ? 'selected' : '' }}>✅ Setujui (Valid)</option>
                                <option value="ditolak" {{ $berkas->status === 'ditolak' ? 'selected' : '' }}>❌ Tolak Berkas</option>
                                <option value="menunggu" {{ $berkas->status === 'menunggu' ? 'selected' : '' }}>⏳ Belum Ditinjau</option>
                            </select>
                        </div>

                        <div class="w-full sm:flex-1">
                            <input type="text" name="catatan_validator" value="{{ $berkas->catatan_validator }}" placeholder="Catatan untuk mahasiswa jika ditolak..."
                                class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                        </div>

                        <button type="submit" class="w-full sm:w-auto px-4 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs transition shadow-sm">
                            Simpan Status
                        </button>
                    </form>
                    @endif

                </div>
                @endforeach
            </div>
        </div>

        <!-- Penetapan Kelulusan Yudisium (Khusus Admin / BAAK / Prodi) -->
        @if(auth()->user()->isAdmin())
        <div class="border-t border-slate-200 pt-6 space-y-4">
            <h3 class="font-extrabold text-sm text-blue-950 uppercase tracking-wider">Penetapan Hasil Rapat Yudisium</h3>
            
            <form action="{{ route('yudisium.tetapkan-kelulusan', $pendaftaran->id) }}" method="POST" class="bg-blue-50/50 p-6 rounded-2xl border border-blue-200 space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Hasil Keputusan <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-3 py-2 bg-white border border-blue-300 rounded-xl text-xs font-bold text-blue-900 focus:outline-none focus:border-blue-500">
                            <option value="lulus" {{ $pendaftaran->status === 'lulus' ? 'selected' : '' }}>🎉 Dinyatakan LULUS</option>
                            <option value="ditolak" {{ $pendaftaran->status === 'ditolak' ? 'selected' : '' }}>❌ Ditolak / Ditunda</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor Berita Acara / SK (Opsional)</label>
                        <input type="text" name="nomor_sk" value="{{ old('nomor_sk', $pendaftaran->nomor_sk ?? 'SK-YUD/STIKES-PW/' . ($pendaftaran->mahasiswa->prodi->kode_prodi ?? 'PRODI') . '/' . date('Y/m/') . sprintf('%03d', $pendaftaran->id)) }}" placeholder="misal: BA-YUD/2026/01"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Predikat Kelulusan</label>
                        <select name="predikat" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-blue-500">
                            <option value="Dengan Pujian" {{ ($pendaftaran->predikat === 'Dengan Pujian' || ($pendaftaran->ipk_final >= 3.75)) ? 'selected' : '' }}>Dengan Pujian (Cum Laude)</option>
                            <option value="Sangat Memuaskan" {{ ($pendaftaran->predikat === 'Sangat Memuaskan' || ($pendaftaran->ipk_final >= 3.00 && $pendaftaran->ipk_final < 3.75)) ? 'selected' : '' }}>Sangat Memuaskan</option>
                            <option value="Memuaskan" {{ $pendaftaran->predikat === 'Memuaskan' ? 'selected' : '' }}>Memuaskan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Rapat Yudisium</label>
                        <input type="date" name="tanggal_sk" value="{{ old('tanggal_sk', $pendaftaran->tanggal_sk ? $pendaftaran->tanggal_sk->format('Y-m-d') : date('Y-m-d')) }}"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Kelulusan Resmi</label>
                        <input type="date" name="tgl_lulus" value="{{ old('tgl_lulus', $pendaftaran->tgl_lulus ? $pendaftaran->tgl_lulus->format('Y-m-d') : date('Y-m-d')) }}"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Hasil Yudisium</label>
                    <input type="text" name="catatan_kelulusan" value="{{ old('catatan_kelulusan', $pendaftaran->catatan_kelulusan) }}" placeholder="Catatan hasil rapat yudisium..."
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                </div>

                <div class="text-right pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
                        <i class="fa-solid fa-check-double mr-1.5"></i> Simpan Penetapan Yudisium
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Ujian & Penilaian Sidang')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Overview Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wide {{ $sidang->jenis === 'sempro' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                    {{ $sidang->jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Akhir Skripsi' }}
                </span>
                <h2 class="text-xl font-black text-slate-800 mt-2">{{ $sidang->tugasAkhir->mahasiswa->nama_lengkap }} ({{ $sidang->tugasAkhir->mahasiswa->nim }})</h2>
                <p class="text-xs text-slate-500">Prodi: <span class="font-semibold text-blue-700">{{ $sidang->tugasAkhir->mahasiswa->prodi->nama_prodi ?? 'Prodi' }}</span></p>
            </div>
            <div>
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wide
                    {{ $sidang->status === 'lulus' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $sidang->status === 'dijadwalkan' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $sidang->status === 'menunggu_jadwal' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $sidang->status === 'revisi' ? 'bg-orange-100 text-orange-700' : '' }}
                ">
                    {{ str_replace('_', ' ', $sidang->status) }}
                </span>
            </div>
        </div>

        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Judul Penelitian</span>
            <h3 class="text-base font-extrabold text-slate-800 mt-1 leading-snug">{{ $sidang->tugasAkhir->judul }}</h3>
        </div>

        <!-- Schedule & Room Info -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Waktu & Tanggal</span>
                <p class="font-extrabold text-slate-800 text-sm mt-1">{{ $sidang->tgl_sidang ? $sidang->tgl_sidang->format('d M Y') : 'Belum Ditentukan' }}</p>
                @if($sidang->jam_mulai)
                <p class="text-slate-500 mt-0.5">{{ substr($sidang->jam_mulai, 0, 5) }} - {{ substr($sidang->jam_selesai, 0, 5) }} WIB</p>
                @endif
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Ruangan Ujian</span>
                <p class="font-extrabold text-slate-800 text-sm mt-1">{{ $sidang->ruangan ?? 'Belum ditentukan' }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                <span class="text-slate-400 font-bold uppercase block text-[10px]">Nilai Akhir Sidang</span>
                @if($sidang->nilai_akhir)
                <p class="font-black text-emerald-700 text-lg mt-0.5">{{ number_format($sidang->nilai_akhir, 2) }} (Grade: {{ $sidang->grade_huruf }})</p>
                @else
                <p class="text-slate-400 italic mt-1">Menunggu input nilai penguji</p>
                @endif
            </div>
        </div>

        <!-- Nilai Rekapitulasi Table -->
        <div class="border-t border-slate-100 pt-6">
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-700 mb-3">Rekapitulasi Nilai Dewan Penguji:</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-2xl overflow-hidden">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="px-4 py-3">Nama Dosen</th>
                            <th class="px-4 py-3">Peran</th>
                            <th class="px-4 py-3 text-center">Presentasi (25%)</th>
                            <th class="px-4 py-3 text-center">Materi (35%)</th>
                            <th class="px-4 py-3 text-center">Tanya Jawab (40%)</th>
                            <th class="px-4 py-3 text-center">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sidang->nilaiSidangs as $ns)
                        <tr>
                            <td class="px-4 py-3 font-bold text-slate-800">{{ $ns->dosen->nama_lengkap }}</td>
                            <td class="px-4 py-3 uppercase text-[10px] font-semibold text-slate-500">{{ $ns->peran }}</td>
                            <td class="px-4 py-3 text-center">{{ number_format($ns->nilai_presentasi, 1) }}</td>
                            <td class="px-4 py-3 text-center">{{ number_format($ns->nilai_materi, 1) }}</td>
                            <td class="px-4 py-3 text-center">{{ number_format($ns->nilai_tanya_jawab, 1) }}</td>
                            <td class="px-4 py-3 text-center font-extrabold text-emerald-700">{{ number_format($ns->total_nilai, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada nilai yang diinput dosen.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Input Nilai Form (Khusus Dosen) -->
        @if(auth()->user()->isDosen())
        @php
            $currentDosen = auth()->user()->dosen;
            $existingScore = $sidang->nilaiSidangs->where('dosen_id', $currentDosen->id)->first();
        @endphp
        <div class="border-t border-slate-200 pt-6 space-y-4">
            <h3 class="font-extrabold text-sm text-slate-900 uppercase tracking-wider">Form Penilaian Ujian Dosen</h3>
            
            <form action="{{ route('sidang.nilai', $sidang->id) }}" method="POST" class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Peran Anda</label>
                        <select name="peran" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-blue-500">
                            <option value="penguji1" {{ ($existingScore && $existingScore->peran === 'penguji1') ? 'selected' : '' }}>Penguji 1</option>
                            <option value="penguji2" {{ ($existingScore && $existingScore->peran === 'penguji2') ? 'selected' : '' }}>Penguji 2</option>
                            <option value="pembimbing1" {{ ($existingScore && $existingScore->peran === 'pembimbing1') ? 'selected' : '' }}>Pembimbing 1</option>
                            <option value="pembimbing2" {{ ($existingScore && $existingScore->peran === 'pembimbing2') ? 'selected' : '' }}>Pembimbing 2</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Presentasi (0-100)</label>
                        <input type="number" step="0.1" name="nilai_presentasi" value="{{ old('nilai_presentasi', $existingScore->nilai_presentasi ?? '') }}" required placeholder="Bobot 25%"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Materi Skripsi (0-100)</label>
                        <input type="number" step="0.1" name="nilai_materi" value="{{ old('nilai_materi', $existingScore->nilai_materi ?? '') }}" required placeholder="Bobot 35%"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanya Jawab (0-100)</label>
                        <input type="number" step="0.1" name="nilai_tanya_jawab" value="{{ old('nilai_tanya_jawab', $existingScore->nilai_tanya_jawab ?? '') }}" required placeholder="Bobot 40%"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Koreksi / Evaluasi</label>
                    <input type="text" name="catatan" value="{{ old('catatan', $existingScore->catatan ?? '') }}" placeholder="Komentar atau saran perbaikan untuk mahasiswa..."
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                </div>

                <div class="text-right pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Nilai Sidang
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Admin Scheduling & Finalizing Forms -->
        @if(auth()->user()->isAdmin())
        <div class="border-t border-slate-200 pt-6 space-y-6">
            
            <!-- Form Jadwal -->
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-4">
                <h4 class="font-extrabold text-xs uppercase tracking-wider text-slate-900">1. Atur Jadwal & Dewan Penguji</h4>
                
                <form action="{{ route('sidang.update-jadwal', $sidang->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Sidang <span class="text-rose-500">*</span></label>
                            <input type="date" name="tgl_sidang" value="{{ old('tgl_sidang', $sidang->tgl_sidang ? $sidang->tgl_sidang->format('Y-m-d') : '') }}" required
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Mulai - Selesai <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $sidang->jam_mulai) }}" required class="w-1/2 px-2 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                                <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $sidang->jam_selesai) }}" required class="w-1/2 px-2 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ruangan <span class="text-rose-500">*</span></label>
                            <input type="text" name="ruangan" value="{{ old('ruangan', $sidang->ruangan) }}" required placeholder="misal: Ruang Sidang Lt. 2 STIKes"
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Dosen Penguji 1 <span class="text-rose-500">*</span></label>
                            <select name="penguji1_id" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                                <option value="">-- Pilih Penguji 1 --</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ $sidang->penguji1_id == $d->id ? 'selected' : '' }}>{{ $d->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Dosen Penguji 2</label>
                            <select name="penguji2_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                                <option value="">-- Pilih Penguji 2 (Opsional) --</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ $sidang->penguji2_id == $d->id ? 'selected' : '' }}>{{ $d->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Sidang</label>
                            <select name="status" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:border-blue-500">
                                <option value="menunggu_jadwal" {{ $sidang->status === 'menunggu_jadwal' ? 'selected' : '' }}>Menunggu Jadwal</option>
                                <option value="dijadwalkan" {{ $sidang->status === 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                                <option value="selesai" {{ $sidang->status === 'selesai' ? 'selected' : '' }}>Selesai Ujian</option>
                                <option value="lulus" {{ $sidang->status === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="revisi" {{ $sidang->status === 'revisi' ? 'selected' : '' }}>Revisi</option>
                                <option value="tidak_lulus" {{ $sidang->status === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
                            Simpan Perubahan Jadwal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form Berita Acara & Kelulusan -->
            <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-200 space-y-4">
                <h4 class="font-extrabold text-xs uppercase tracking-wider text-blue-950">2. Berita Acara & Finalisasi Hasil Sidang</h4>
                
                <form action="{{ route('sidang.finalize', $sidang->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keputusan Akhir Sidang <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-3 py-2 bg-white border border-blue-300 rounded-xl text-xs font-bold text-blue-900 focus:outline-none focus:border-blue-500">
                            <option value="lulus" {{ $sidang->status === 'lulus' ? 'selected' : '' }}>🎉 LULUS SIDANG</option>
                            <option value="revisi" {{ $sidang->status === 'revisi' ? 'selected' : '' }}>⚠️ LULUS DENGAN REVISI</option>
                            <option value="tidak_lulus" {{ $sidang->status === 'tidak_lulus' ? 'selected' : '' }}>❌ TIDAK LULUS (Ujian Ulang)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Berita Acara Sidang / Ringkasan Keputusan <span class="text-rose-500">*</span></label>
                        <textarea name="berita_acara" rows="3" required placeholder="Berdasarkan hasil ujian seminar/sidang, dewan penguji memutuskan bahwa..."
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">{{ old('berita_acara', $sidang->berita_acara) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Revisi untuk Mahasiswa</label>
                        <textarea name="catatan_revisi" rows="2" placeholder="Daftar revisi wajib sebelum cetak dokumen..."
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">{{ old('catatan_revisi', $sidang->catatan_revisi) }}</textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-600/30 transition">
                            <i class="fa-solid fa-stamp mr-1.5"></i> Finalisasi Hasil Sidang
                        </button>
                    </div>
                </form>
            </div>

        </div>
        @endif

    </div>

</div>
@endsection

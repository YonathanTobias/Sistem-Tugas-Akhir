<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Bimbingan - {{ $ta->mahasiswa->nama_lengkap }} ({{ $ta->mahasiswa->nim }})</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 font-serif text-slate-900">

    <div class="no-print max-w-4xl mx-auto mb-4 flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <span class="text-xs font-sans text-slate-600 font-semibold">Gunakan tombol print untuk mencetak atau menyimpan sebagai PDF.</span>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white font-sans text-xs font-bold rounded-xl shadow hover:bg-emerald-500">
                🖨️ Cetak Kartu Kendali
            </button>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-200 text-slate-700 font-sans text-xs font-bold rounded-xl hover:bg-slate-300">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Paper Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-10 border border-slate-300 shadow-lg min-h-[1000px] text-xs">
        
        <!-- Header Kampus -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-6">
            <div class="text-center w-full">
                <h1 class="font-bold text-xs uppercase tracking-wider text-slate-700">YAYASAN KERUKUNAN SANTO CAROLUS BORROMEUS</h1>
                <h2 class="font-extrabold text-lg uppercase tracking-wide text-slate-900">SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG</h2>
                <h3 class="font-bold text-xs uppercase text-emerald-800">PROGRAM STUDI {{ strtoupper($ta->mahasiswa->prodi->nama_prodi ?? 'KEPERAWATAN') }} ({{ $ta->mahasiswa->prodi->jenjang ?? 'S1' }})</h3>
                <p class="text-[10px] text-slate-600 mt-1 italic">Jl. Yulius Usman No. 62 Malang, Jawa Timur &bull; Telp. (0341) 369003 &bull; Website: www.stikespantiwaluya.ac.id</p>
            </div>
        </div>

        <div class="text-center mb-6">
            <h3 class="font-extrabold text-sm uppercase underline tracking-wider">KARTU KENDALI & LOGBOOK BIMBINGAN TUGAS AKHIR</h3>
        </div>

        <!-- Student Meta -->
        <table class="w-full mb-6 font-sans text-xs">
            <tr>
                <td class="w-36 py-1 font-semibold text-slate-600">Nama Mahasiswa</td>
                <td class="w-4">:</td>
                <td class="font-bold text-slate-900 uppercase">{{ $ta->mahasiswa->nama_lengkap }}</td>
                <td class="w-32 py-1 font-semibold text-slate-600">NIM</td>
                <td class="w-4">:</td>
                <td class="font-bold text-slate-900 font-mono">{{ $ta->mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-slate-600">Program Studi</td>
                <td>:</td>
                <td>{{ $ta->mahasiswa->prodi->nama_prodi ?? 'S1 Keperawatan' }} ({{ $ta->mahasiswa->prodi->jenjang ?? 'S1' }})</td>
                <td class="py-1 font-semibold text-slate-600">Tahun Akademik</td>
                <td>:</td>
                <td>{{ $ta->periodeAkademik->nama_periode ?? '2026/2027 Ganjil' }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-slate-600 align-top">Judul Skripsi/TA</td>
                <td class="align-top">:</td>
                <td colspan="4" class="font-semibold leading-relaxed">{{ $ta->judul }}</td>
            </tr>
            <tr>
                <td class="py-1 font-semibold text-slate-600">Pembimbing Utama</td>
                <td>:</td>
                <td colspan="4">{{ $ta->pembimbing1 ? $ta->pembimbing1->nama_lengkap . ' (' . $ta->pembimbing1->nidn . ')' : '-' }}</td>
            </tr>
            @if($ta->pembimbing2)
            <tr>
                <td class="py-1 font-semibold text-slate-600">Pembimbing Pendamping</td>
                <td>:</td>
                <td colspan="4">{{ $ta->pembimbing2->nama_lengkap }} ({{ $ta->pembimbing2->nidn }})</td>
            </tr>
            @endif
        </table>

        <!-- Log Table -->
        <table class="w-full border-collapse border border-slate-900 font-sans text-[11px] mb-8">
            <thead>
                <tr class="bg-slate-100 text-slate-900">
                    <th class="border border-slate-900 px-2 py-2 w-8 text-center">No</th>
                    <th class="border border-slate-900 px-3 py-2 w-24 text-center">Tanggal</th>
                    <th class="border border-slate-900 px-3 py-2 w-20 text-center">Bab</th>
                    <th class="border border-slate-900 px-3 py-2">Materi Bahasan & Catatan Pembimbing</th>
                    <th class="border border-slate-900 px-3 py-2 w-28 text-center">Status</th>
                    <th class="border border-slate-900 px-3 py-2 w-24 text-center">Paraf Dosen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbingans as $index => $b)
                <tr>
                    <td class="border border-slate-900 px-2 py-2 text-center">{{ $index + 1 }}</td>
                    <td class="border border-slate-900 px-3 py-2 text-center">{{ $b->tgl_bimbingan->format('d/m/Y') }}</td>
                    <td class="border border-slate-900 px-3 py-2 font-bold text-center">{{ $b->bab }}</td>
                    <td class="border border-slate-900 px-3 py-2">
                        <strong>{{ $b->topik_bimbingan }}</strong>
                        <p class="text-[10px] text-slate-700 mt-1 italic">{{ $b->catatan_dosen ?? 'Telah didiskusikan dan disetujui.' }}</p>
                    </td>
                    <td class="border border-slate-900 px-3 py-2 text-center font-bold text-emerald-800">
                        DISETUJUI (ACC)
                    </td>
                    <td class="border border-slate-900 px-3 py-2 text-center font-mono text-[9px] text-slate-400">
                        [ACC-DIGITAL]
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border border-slate-900 px-3 py-6 text-center text-slate-400 italic">
                        Belum ada sesi bimbingan yang berstatus ACC.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-8 font-sans text-xs pt-6">
            <div class="text-center">
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua Program Studi {{ $ta->mahasiswa->prodi->nama_prodi ?? 'Keperawatan' }}</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="px-3 py-1 bg-slate-50 border border-slate-300 text-slate-600 font-mono text-[9px] rounded">DITANDATANGANI SECARA ELEKTRONIK</span>
                </div>
                <p class="font-bold underline">{{ $ta->mahasiswa->prodi->kaprodi_nama ?? 'Ns. Felisitas A. Sri S., M.Kep.' }}</p>
                <p class="text-[10px] text-slate-500">NIDN. {{ $ta->mahasiswa->prodi->kaprodi_nidn ?? '0712048001' }}</p>
            </div>
            <div class="text-center">
                <p>Malang, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Dosen Pembimbing Utama</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-300 text-emerald-800 font-mono text-[10px] rounded font-bold">TERVALIDASI SISTEM</span>
                </div>
                <p class="font-bold underline">{{ $ta->pembimbing1->nama_lengkap ?? 'Dosen Pembimbing' }}</p>
                <p class="text-[10px] text-slate-500">NIDN. {{ $ta->pembimbing1->nidn ?? '-' }}</p>
            </div>
        </div>

    </div>

</body>
</html>

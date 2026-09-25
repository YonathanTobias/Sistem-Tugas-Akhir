<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Lulus (SKL) - {{ $pendaftaran->mahasiswa->nama_lengkap }} ({{ $pendaftaran->mahasiswa->nim }})</title>
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
        <span class="text-xs font-sans text-slate-600 font-semibold">Dokumen Resmi Surat Keterangan Lulus (SKL) Ber-QR Code.</span>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white font-sans text-xs font-bold rounded-xl shadow hover:bg-blue-500 transition">
                🖨️ Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-200 text-slate-700 font-sans text-xs font-bold rounded-xl hover:bg-slate-300 transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Certificate Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-12 border-2 border-slate-400 shadow-2xl min-h-[1050px] text-xs relative">
        
        <!-- Watermark / Security Border -->
        <div class="border border-slate-300 p-8 h-full">
            
            <!-- Header Kampus -->
            <div class="flex items-center justify-between border-b-2 border-slate-900 pb-4 mb-6">
                <div class="text-center w-full">
                    <h1 class="font-bold text-xs uppercase tracking-wider text-slate-700">YAYASAN KERUKUNAN SANTO CAROLUS BORROMEUS</h1>
                    <h2 class="font-extrabold text-xl uppercase tracking-wide text-slate-900">SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG</h2>
                    <h3 class="font-bold text-xs uppercase text-blue-900 tracking-wide">PROGRAM STUDI {{ strtoupper($pendaftaran->mahasiswa->prodi->nama_prodi ?? 'S1 KEPERAWATAN') }}</h3>
                    <p class="text-[10px] text-slate-600 mt-1 italic">Jl. Yulius Usman No. 62 Malang, Jawa Timur &bull; Telp. (0341) 369003 &bull; Website: www.stikespantiwaluya.ac.id</p>
                </div>
            </div>

            <!-- Title -->
            <div class="text-center mb-8">
                <h3 class="font-extrabold text-base uppercase tracking-wider underline">SURAT KETERANGAN LULUS (SKL)</h3>
                <p class="text-xs font-mono text-slate-600 mt-1">Nomor: {{ $pendaftaran->nomor_sk ?? 'SK-YUD/STIKES-PW/' . ($pendaftaran->mahasiswa->prodi->kode_prodi ?? 'KEP') . '/' . date('Y/m/') . '001' }}</p>
            </div>

            <!-- Content -->
            <div class="space-y-4 text-xs font-sans leading-relaxed text-slate-800">
                <p>Ketua Sekolah Tinggi Ilmu Kesehatan (STIKes) Panti Waluya Malang dengan ini menerangkan dengan sesungguhnya bahwa:</p>

                <table class="w-full my-4 text-xs ml-4">
                    <tr>
                        <td class="w-48 py-1.5 font-semibold text-slate-700">Nama Mahasiswa</td>
                        <td class="w-4">:</td>
                        <td class="font-bold uppercase text-slate-900">{{ $pendaftaran->mahasiswa->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-semibold text-slate-700">Nomor Induk Mahasiswa (NIM)</td>
                        <td>:</td>
                        <td class="font-mono font-bold text-slate-900">{{ $pendaftaran->mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-semibold text-slate-700">Program Studi / Jenjang</td>
                        <td>:</td>
                        <td>{{ $pendaftaran->mahasiswa->prodi->nama_prodi ?? 'S1 Keperawatan' }} ({{ $pendaftaran->mahasiswa->prodi->jenjang ?? 'S1' }})</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-semibold text-slate-700">Gelar Akademik yang Diberikan</td>
                        <td>:</td>
                        <td class="font-bold text-blue-900">{{ $pendaftaran->mahasiswa->prodi->gelar_lulusan ?? 'Sarjana Keperawatan (S.Kep.)' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-semibold text-slate-700">Tanggal Kelulusan Resmi</td>
                        <td>:</td>
                        <td class="font-bold">{{ $pendaftaran->tgl_lulus ? $pendaftaran->tgl_lulus->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-semibold text-slate-700">Indeks Prestasi Kumulatif (IPK)</td>
                        <td>:</td>
                        <td class="font-bold">{{ number_format($pendaftaran->ipk_final, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-semibold text-slate-700">Predikat Kelulusan</td>
                        <td>:</td>
                        <td class="font-bold uppercase text-emerald-800">{{ $pendaftaran->predikat ?? 'Dengan Pujian (Cum Laude)' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1.5 font-semibold text-slate-700 align-top">Judul Skripsi / KTI</td>
                        <td class="align-top">:</td>
                        <td class="italic font-medium leading-normal">{{ $pendaftaran->tugasAkhir->judul ?? '-' }}</td>
                    </tr>
                </table>

                <p class="text-justify leading-relaxed">
                    Telah dinyatakan <strong>LULUS</strong> dalam Sidang Yudisium Program Studi {{ $pendaftaran->mahasiswa->prodi->nama_prodi ?? 'S1 Keperawatan' }} periode <strong>{{ $pendaftaran->periodeYudisium->nama_periode }}</strong> dan telah menyelesaikan seluruh persyaratan akademik serta administrasi bebas tanggungan perpustakaan, sarana lab, dan keuangan.
                </p>

                <p class="text-justify leading-relaxed">
                    Surat Keterangan Lulus ini berlaku sebagai bukti kelulusan yang sah sampai dengan diterbitkannya Ijazah dan Transkrip Akademik resmi oleh institusi.
                </p>
            </div>

            <!-- Signature & QR Section -->
            <div class="grid grid-cols-2 gap-8 font-sans text-xs pt-12">
                <div class="space-y-2 text-center">
                    <p class="text-[10px] text-slate-500 uppercase font-semibold">Pindai QR untuk Verifikasi Keaslian:</p>
                    <div class="p-2 border border-slate-300 inline-block bg-slate-50 rounded-xl">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode(route('verify.skl', $pendaftaran->skl_token)) }}" alt="QR Code Verifikasi" class="w-24 h-24 mx-auto">
                    </div>
                    <p class="font-mono text-[9px] text-slate-400">Token: {{ substr($pendaftaran->skl_token, 0, 16) }}...</p>
                </div>

                <div class="text-center">
                    <p>Malang, {{ $pendaftaran->tanggal_sk ? $pendaftaran->tanggal_sk->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</p>
                    <p class="font-bold text-slate-800 mt-1">Ketua STIKes Panti Waluya Malang,</p>
                    <div class="h-20 flex items-center justify-center">
                        <span class="px-3 py-1 bg-blue-50 border border-blue-300 text-blue-900 font-mono text-[9px] rounded font-bold">TERVALIDASI SECARA DIGITAL</span>
                    </div>
                    <p class="font-bold text-sm underline text-slate-900">Dr. apt. Irene Ratridewi, M.Farm.</p>
                    <p class="text-[10px] text-slate-500">NIDN. 0715097501</p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>

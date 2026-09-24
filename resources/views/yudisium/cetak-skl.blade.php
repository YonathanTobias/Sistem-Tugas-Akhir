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
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white font-sans text-xs font-bold rounded-xl shadow hover:bg-emerald-500">
                🖨️ Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-200 text-slate-700 font-sans text-xs font-bold rounded-xl hover:bg-slate-300">
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
                    <h3 class="font-bold text-xs uppercase text-emerald-800 tracking-wide">PROGRAM STUDI {{ strtoupper($pendaftaran->mahasiswa->prodi->nama_prodi ?? 'S1 KEPERAWATAN') }}</h3>
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
                        <td class="font-bold text-emerald-900">{{ $pendaftaran->mahasiswa->prodi->gelar_lulusan ?? 'Sarjana Keperawatan (S.Kep.)' }}</td>
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
                    Surat Keterangan Lulus ini berlaku sebagai bukti kelulusan yang sah sementara menunggu penerbitan Ijazah, Transkrip Akademik, dan Sertifikat Profesi / STR resmi.
                </p>
            </div>

            <!-- Signatures and QR Code Validation -->
            <div class="mt-12 pt-6 grid grid-cols-2 gap-8 font-sans items-end">
                
                <!-- QR Code Verification Box -->
                <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode($verificationUrl) }}" alt="QR Code Verifikasi" class="w-24 h-24 border border-slate-300 p-1 bg-white rounded-lg">
                    <div class="text-[10px] space-y-1 text-slate-600">
                        <strong class="font-bold text-slate-900 block uppercase">Verifikasi Dokumen Digital</strong>
                        <p>Pindai (scan) QR Code ini untuk memverifikasi keaslian dan validitas SKL pada pangkalan data STIKes Panti Waluya.</p>
                        <span class="font-mono text-[9px] text-slate-400 block truncate">Token: {{ substr($pendaftaran->skl_token, 0, 16) }}...</span>
                    </div>
                </div>

                <!-- Dean / Ketua Signature -->
                <div class="text-center space-y-1">
                    <p class="text-xs text-slate-600">Malang, {{ $pendaftaran->tanggal_sk ? $pendaftaran->tanggal_sk->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</p>
                    <p class="text-xs font-bold uppercase">Ketua STIKes Panti Waluya Malang,</p>
                    <div class="h-16 flex items-center justify-center">
                        <span class="px-3 py-1 bg-emerald-50 border border-emerald-300 text-emerald-800 font-mono text-[10px] rounded font-bold uppercase tracking-wider">DITANDATANGANI SECARA ELEKTRONIK</span>
                    </div>
                    <p class="font-bold text-xs underline">Wisoedhanie Widi Anugrahanti, S.KM., M.Kes.</p>
                    <p class="text-[10px] text-slate-500">NIDN. 0718057701</p>
                </div>

            </div>

        </div>

    </div>

</body>
</html>

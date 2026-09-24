<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Ijazah & SKL - STIKes Panti Waluya Malang</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="h-full flex items-center justify-center p-4 font-sans antialiased bg-slate-950">

    <div class="w-full max-w-lg">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 text-white shadow-xl shadow-emerald-500/20 mb-3">
                <i class="fa-solid fa-shield-halved text-3xl"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">Verifikasi Dokumen Kelulusan</h1>
            <p class="text-xs text-slate-400 mt-1">Pangkalan Data Ijazah & SKL Resmi STIKes Panti Waluya Malang</p>
        </div>

        @if($pendaftaran && $pendaftaran->status === 'lulus')
        <!-- Valid Certificate Card -->
        <div class="bg-slate-900 border-2 border-emerald-500/80 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-5">
            
            <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                <i class="fa-solid fa-circle-check text-2xl"></i>
                <div>
                    <h3 class="font-extrabold text-sm uppercase tracking-wider">Dokumen Asli & Valid</h3>
                    <p class="text-[11px] text-slate-300">Surat Keterangan Lulus ini terdaftar resmi pada pangkalan data STIKes Panti Waluya Malang.</p>
                </div>
            </div>

            <div class="divide-y divide-slate-800 text-xs space-y-2.5">
                <div class="pt-2 flex justify-between">
                    <span class="text-slate-400">Nama Lulusan:</span>
                    <span class="font-bold text-white uppercase">{{ $pendaftaran->mahasiswa->nama_lengkap }}</span>
                </div>
                <div class="pt-2 flex justify-between">
                    <span class="text-slate-400">NIM:</span>
                    <span class="font-mono font-bold text-emerald-400">{{ $pendaftaran->mahasiswa->nim }}</span>
                </div>
                <div class="pt-2 flex justify-between">
                    <span class="text-slate-400">Program Studi:</span>
                    <span class="font-semibold text-white">{{ $pendaftaran->mahasiswa->prodi->nama_prodi ?? 'S1 Keperawatan' }} ({{ $pendaftaran->mahasiswa->prodi->jenjang ?? 'S1' }})</span>
                </div>
                <div class="pt-2 flex justify-between">
                    <span class="text-slate-400">Gelar Kelulusan:</span>
                    <span class="font-bold text-emerald-400">{{ $pendaftaran->mahasiswa->prodi->gelar_lulusan ?? 'Sarjana Keperawatan (S.Kep.)' }}</span>
                </div>
                <div class="pt-2 flex justify-between">
                    <span class="text-slate-400">Tanggal Kelulusan:</span>
                    <span class="font-semibold text-white">{{ $pendaftaran->tgl_lulus ? $pendaftaran->tgl_lulus->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="pt-2 flex justify-between">
                    <span class="text-slate-400">Predikat:</span>
                    <span class="font-bold text-emerald-400 uppercase">{{ $pendaftaran->predikat }}</span>
                </div>
                <div class="pt-2 flex justify-between">
                    <span class="text-slate-400">Nomor SK Yudisium:</span>
                    <span class="font-mono text-slate-300">{{ $pendaftaran->nomor_sk }}</span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 text-center text-[11px] text-slate-400">
                <i class="fa-solid fa-lock text-emerald-500 mr-1"></i> Tervalidasi dengan Token Kriptografi Digital:
                <div class="font-mono text-[9px] text-slate-400 truncate mt-1">{{ $pendaftaran->skl_token }}</div>
            </div>

        </div>
        @else
        <!-- Invalid Certificate Card -->
        <div class="bg-slate-900 border-2 border-rose-500/80 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4 text-center">
            <div class="w-16 h-16 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center text-3xl mx-auto mb-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-lg font-black text-white">Dokumen Tidak Ditemukan / Tidak Valid</h3>
            <p class="text-xs text-slate-400 leading-relaxed">
                Token verifikasi kelulusan tidak terdaftar dalam basis data resmi kami. Harap hubungi Bagian Administrasi Akademik & Kemahasiswaan (BAAK) STIKes Panti Waluya Malang untuk klarifikasi.
            </p>
        </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-white transition">
                &larr; Kembali ke Portal Utama
            </a>
        </div>

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa - SIMTA & Yudisium</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="h-full flex items-center justify-center p-4 font-sans antialiased bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950">

    <div class="w-full max-w-lg">
        <!-- App Logo & Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 text-white shadow-xl shadow-emerald-500/20 mb-3">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">Registrasi Akun Mahasiswa</h1>
            <p class="text-xs text-slate-400 mt-1">Daftarkan NIM Anda untuk memulai proses Tugas Akhir</p>
        </div>

        <!-- Card -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl">
            
            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="misal: Ahmad Fauzi"
                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required
                            placeholder="misal: 220101099"
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Program Studi</label>
                        <select name="prodi_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }} ({{ $p->jenjang }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Angkatan</label>
                        <input type="text" name="angkatan" value="{{ old('angkatan', date('Y') - 3) }}" required
                            placeholder="misal: 2022"
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">No. WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            placeholder="misal: 08123456789"
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Email Kampus / Pribadi</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="misal: mhs@kampus.ac.id"
                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-emerald-600/30 transition duration-200 mt-4">
                    Daftar Sekarang <i class="fa-solid fa-arrow-right ml-1.5"></i>
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-400">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-400 font-semibold hover:underline">Masuk disini</a>
            </div>

        </div>
    </div>

</body>
</html>

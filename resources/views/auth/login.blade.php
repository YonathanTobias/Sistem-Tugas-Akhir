<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMTA & Yudisium</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full flex items-center justify-center p-4 font-sans antialiased bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950">

    <div class="w-full max-w-lg" x-data="{
        fillCredentials(login, pwd) {
            document.getElementById('login').value = login;
            document.getElementById('password').value = pwd;
        }
    }">
        <!-- App Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-400 text-white shadow-xl shadow-blue-500/20 mb-4 ring-8 ring-blue-500/10">
                <i class="fa-solid fa-graduation-cap text-3xl"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">SIMTA <span class="text-cyan-400">Panti Waluya</span></h1>
            <p class="text-sm text-slate-400 mt-1">Sistem Informasi Tugas Akhir & Yudisium STIKes Panti Waluya Malang</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl">
            
            @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-medium flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-medium flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="login" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </span>
                        <input type="text" name="login" id="login" required autofocus
                            value="{{ old('login') }}"
                            placeholder="nama@stikespantiwaluya.ac.id"
                            class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-slate-500 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-slate-500 transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-blue-600 focus:ring-blue-500 mr-2">
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold transition">Daftar Mahasiswa</a>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-600/30 transition duration-200 mt-2">
                    Masuk ke Sistem <i class="fa-solid fa-arrow-right ml-1.5"></i>
                </button>
            </form>

            <!-- Quick Demo Accounts Switcher (2 Admins: IT & Prodi, Dosen, Mahasiswa) -->
            <div class="mt-8 pt-6 border-t border-slate-800/80">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center mb-3">Akun Uji Coba Cepat (1-Klik):</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                    <button type="button" @click="fillCredentials('it@stikespantiwaluya.ac.id', 'password')"
                        class="p-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-left border border-slate-700 text-slate-300 hover:text-white transition">
                        <div class="font-bold text-rose-400 truncate">👑 Super Admin IT</div>
                        <div class="text-[10px] text-slate-400 truncate">it@...</div>
                    </button>
                    <button type="button" @click="fillCredentials('admin.prodi@stikespantiwaluya.ac.id', 'password')"
                        class="p-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-left border border-slate-700 text-slate-300 hover:text-white transition">
                        <div class="font-bold text-indigo-400 truncate">🏢 Admin Prodi</div>
                        <div class="text-[10px] text-slate-400 truncate">admin.prodi@...</div>
                    </button>
                    <button type="button" @click="fillCredentials('dosen1@stikespantiwaluya.ac.id', 'password')"
                        class="p-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-left border border-slate-700 text-slate-300 hover:text-white transition">
                        <div class="font-bold text-sky-400 truncate">👨‍🏫 Dosen</div>
                        <div class="text-[10px] text-slate-400 truncate">dosen1@...</div>
                    </button>
                    <button type="button" @click="fillCredentials('mahasiswa@stikespantiwaluya.ac.id', 'password')"
                        class="p-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-left border border-slate-700 text-slate-300 hover:text-white transition">
                        <div class="font-bold text-emerald-400 truncate">🎓 Mahasiswa</div>
                        <div class="text-[10px] text-slate-400 truncate">mahasiswa@...</div>
                    </button>
                </div>
            </div>

        </div>

        <p class="text-center text-xs text-slate-400 mt-6">&copy; {{ date('Y') }} STIKes Panti Waluya Malang. All rights reserved.</p>
    </div>

</body>
</html>

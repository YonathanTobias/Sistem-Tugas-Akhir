<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIMTA & Yudisium</title>
    
    <!-- DNS Prefetch & Preconnect for Fast Loading -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#1d4ed8',
                            700: '#1e40af',
                            800: '#1e3a8a',
                            900: '#172554',
                            950: '#0a1532',
                        },
                        clinical: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                        },
                        navy: {
                            800: '#0f1f38',
                            900: '#0b162c',
                            950: '#060d1d',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="fixed inset-y-0 z-50 flex flex-col h-screen w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out md:static md:translate-x-0 border-r border-slate-800 flex-shrink-0 overflow-hidden"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            
            <!-- Brand Logo -->
            <div class="flex items-center justify-between h-16 px-5 bg-slate-950 border-b border-slate-800 flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-cyan-400 flex items-center justify-center text-white font-bold shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <div>
                        <span class="text-base font-black tracking-tight text-white block leading-tight">SIMTA<span class="text-cyan-400 font-extrabold">+</span></span>
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold block">STIKes Panti Waluya</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links (Scrollable Area) -->
            <div class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <div class="text-[10px] uppercase tracking-wider text-slate-400 font-black px-3 pt-1 pb-1.5 flex items-center justify-between">
                    <span>Menu Utama</span>
                    <i class="fa-solid fa-layer-group text-[9px]"></i>
                </div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 font-bold border-l-4 border-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 mr-2.5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('tugas-akhir.index') }}" class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('tugas-akhir.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 font-bold border-l-4 border-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-book-bookmark w-5 mr-2.5 text-center {{ request()->routeIs('tugas-akhir.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Tugas Akhir / Skripsi</span>
                </a>

                <a href="{{ route('bimbingan.index') }}" class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('bimbingan.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 font-bold border-l-4 border-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-comments w-5 mr-2.5 text-center {{ request()->routeIs('bimbingan.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Logbook Bimbingan</span>
                </a>

                <a href="{{ route('sidang.index') }}" class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('sidang.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 font-bold border-l-4 border-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-rectangle w-5 mr-2.5 text-center {{ request()->routeIs('sidang.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Seminar & Sidang</span>
                </a>

                <div class="pt-3 text-[10px] uppercase tracking-wider text-slate-400 font-black px-3 pb-1.5 flex items-center justify-between">
                    <span>Kelulusan</span>
                    <i class="fa-solid fa-certificate text-[9px]"></i>
                </div>

                <a href="{{ route('yudisium.index') }}" class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('yudisium.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 font-bold border-l-4 border-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-award w-5 mr-2.5 text-center {{ request()->routeIs('yudisium.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Pendaftaran Yudisium</span>
                </a>

                <a href="{{ route('pengumuman.index') }}" class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('pengumuman.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30 font-bold border-l-4 border-cyan-300' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-bullhorn w-5 mr-2.5 text-center {{ request()->routeIs('pengumuman.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Pengumuman</span>
                </a>

                @if(auth()->user()->isAdminIT())
                <div class="pt-4 text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 mb-2">Administrasi Master IT</div>

                <a href="{{ route('master.prodi') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.prodi*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 mr-3 text-center text-indigo-400"></i>
                    <span>Pengaturan 3 Prodi</span>
                </a>

                <a href="{{ route('master.dosen') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.dosen*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-tie w-5 mr-3 text-center"></i>
                    <span>Data Seluruh Dosen</span>
                </a>

                <a href="{{ route('master.mahasiswa') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.mahasiswa*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-graduate w-5 mr-3 text-center"></i>
                    <span>Data Seluruh Mahasiswa</span>
                </a>

                <a href="{{ route('master.periode-yudisium') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.periode-yudisium*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-check w-5 mr-3 text-center"></i>
                    <span>Periode Yudisium</span>
                </a>

                <a href="{{ route('master.syarat-yudisium') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.syarat-yudisium*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-list w-5 mr-3 text-center"></i>
                    <span>Syarat Bebas Tanggungan</span>
                </a>
                @elseif(auth()->user()->isAdminProdi())
                <div class="pt-4 text-[11px] uppercase tracking-wider text-slate-400 font-bold px-3 mb-2">Administrasi Prodi ({{ auth()->user()->prodi->kode_prodi ?? 'PRODI' }})</div>

                <a href="{{ route('master.dosen') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.dosen*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-tie w-5 mr-3 text-center"></i>
                    <span>Dosen {{ auth()->user()->prodi->kode_prodi ?? '' }}</span>
                </a>

                <a href="{{ route('master.mahasiswa') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.mahasiswa*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-graduate w-5 mr-3 text-center"></i>
                    <span>Mahasiswa {{ auth()->user()->prodi->kode_prodi ?? '' }}</span>
                </a>

                <a href="{{ route('master.periode-yudisium') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.periode-yudisium*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-check w-5 mr-3 text-center"></i>
                    <span>Periode Yudisium</span>
                </a>

                <a href="{{ route('master.syarat-yudisium') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all {{ request()->routeIs('master.syarat-yudisium*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-list w-5 mr-3 text-center"></i>
                    <span>Syarat Bebas Tanggungan</span>
                </a>
                @endif
            </div>

            <!-- User Info & Logout (Always Pinned at Bottom of Sidebar) -->
            <div class="p-3.5 border-t border-slate-800 bg-slate-950 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center font-bold text-white uppercase text-xs border border-slate-700">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-emerald-400 capitalize font-medium flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            @if(auth()->user()->isAdminIT())
                                Super Admin IT
                            @elseif(auth()->user()->isAdminProdi())
                                Admin Prodi
                            @elseif(auth()->user()->isDosen())
                                Dosen
                            @elseif(auth()->user()->isMahasiswa())
                                Mahasiswa
                            @else
                                {{ auth()->user()->role }}
                            @endif
                        </p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Keluar / Logout" class="text-rose-400 hover:text-white p-2 rounded-xl bg-rose-500/10 hover:bg-rose-600 border border-rose-500/20 transition flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-power-off text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay on Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm md:hidden"></div>

        <!-- MAIN CONTENT AREA -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            
            <!-- Topbar (ATM JTI Polinema Style) -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 z-10 shadow-xs">
                
                <!-- Left: Mobile Toggle & Breadcrumb Navigation -->
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 md:hidden p-2 rounded-lg bg-slate-100">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>

                    <div>
                        <!-- Breadcrumbs -->
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-medium">
                            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                                <i class="fa-solid fa-house text-[10px]"></i> Beranda
                            </a>
                            <span>/</span>
                            <span class="text-slate-700 font-bold truncate">@yield('title', 'Portal')</span>
                        </div>
                        <h1 class="text-base sm:text-lg font-black text-slate-900 leading-tight truncate">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <!-- Right: Scope, Date, User Badge, Fast Logout -->
                <div class="flex items-center gap-3">
                    
                    @if(auth()->user()->isAdminIT())
                    <!-- Quick Prodi Scope Switcher Dropdown (Super Admin IT) -->
                    <form action="{{ route('master.prodi.switch') }}" method="POST" class="hidden md:flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs">
                        @csrf
                        <span class="text-slate-500 font-bold px-2 flex items-center gap-1">
                            <i class="fa-solid fa-layer-group text-blue-600"></i> Scope:
                        </span>
                        <select name="prodi_id" onchange="this.form.submit()" class="bg-white border-0 py-1 px-2.5 rounded-lg font-bold text-slate-800 text-xs focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs">
                            <option value="all" {{ !session('active_prodi_id') ? 'selected' : '' }}>🏢 Semua Prodi (Global)</option>
                            <option value="1" {{ session('active_prodi_id') == 1 ? 'selected' : '' }}>🩺 S1 Keperawatan</option>
                            <option value="2" {{ session('active_prodi_id') == 2 ? 'selected' : '' }}>💊 S1 Farmasi</option>
                            <option value="3" {{ session('active_prodi_id') == 3 ? 'selected' : '' }}>📋 D4 Rekam Medis / MIK</option>
                        </select>
                    </form>
                    @endif

                    <div class="hidden sm:flex items-center text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fa-solid fa-calendar-day mr-1.5 text-slate-400"></i>
                        {{ now()->translatedFormat('d F Y') }}
                    </div>

                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                    <!-- Role Badge & Logout Button -->
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg uppercase tracking-wide
                            {{ auth()->user()->isAdminIT() ? 'bg-rose-100 text-rose-700 border border-rose-200' : '' }}
                            {{ auth()->user()->isAdminProdi() ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                            {{ auth()->user()->isDosen() ? 'bg-sky-100 text-sky-700 border border-sky-200' : '' }}
                            {{ auth()->user()->isMahasiswa() ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                        ">
                            @if(auth()->user()->isAdminIT())
                                👑 IT Admin
                            @elseif(auth()->user()->isAdminProdi())
                                🏢 Prodi {{ auth()->user()->prodi->kode_prodi ?? '' }}
                            @elseif(auth()->user()->isDosen())
                                🩺 Dosen
                            @elseif(auth()->user()->isMahasiswa())
                                🎓 Mahasiswa
                            @endif
                        </span>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Keluar / Logout" class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white border border-rose-200 hover:border-rose-600 rounded-xl transition shadow-xs">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Page Body -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70">
                
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 flex items-center p-4 text-sm text-emerald-800 border border-emerald-200 rounded-2xl bg-emerald-50 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check text-lg mr-3 text-emerald-600"></i>
                    <div class="font-medium">{{ session('success') }}</div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 flex items-center p-4 text-sm text-rose-800 border border-rose-200 rounded-2xl bg-rose-50 shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation text-lg mr-3 text-rose-600"></i>
                    <div class="font-medium">{{ session('error') }}</div>
                </div>
                @endif

                @if(session('info'))
                <div class="mb-6 flex items-center p-4 text-sm text-sky-800 border border-sky-200 rounded-2xl bg-sky-50 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-info text-lg mr-3 text-sky-600"></i>
                    <div class="font-medium">{{ session('info') }}</div>
                </div>
                @endif

                @if ($errors->any())
                <div class="mb-6 p-4 text-sm text-rose-800 border border-rose-200 rounded-2xl bg-rose-50 shadow-sm">
                    <div class="flex items-center font-bold mb-2">
                        <i class="fa-solid fa-circle-xmark mr-2 text-rose-600"></i> Terjadi beberapa kesalahan:
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

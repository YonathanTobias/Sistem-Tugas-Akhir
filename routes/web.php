<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\SidangController;
use App\Http\Controllers\YudisiumController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PublicVerificationController;

// Halaman Beranda / Redirect ke Login atau Dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Publik: Verifikasi Keaslian SKL via QR Code
Route::get('/verify/skl/{token}', [PublicVerificationController::class, 'verifySkl'])->name('verify.skl');

// Area Terproteksi (Login Required)
Route::middleware('auth')->group(function () {
    // Dashboard (semua role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Tugas Akhir
    Route::prefix('tugas-akhir')->name('tugas-akhir.')->group(function () {
        Route::get('/', [TugasAkhirController::class, 'index'])->name('index');
        Route::get('/create', [TugasAkhirController::class, 'create'])->name('create');
        Route::post('/', [TugasAkhirController::class, 'store'])->name('store');
        Route::get('/{id}', [TugasAkhirController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [TugasAkhirController::class, 'updateStatus'])->name('update-status');
    });

    // Modul Logbook Bimbingan
    Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
        Route::get('/', [BimbinganController::class, 'index'])->name('index');
        Route::get('/create', [BimbinganController::class, 'create'])->name('create');
        Route::post('/', [BimbinganController::class, 'store'])->name('store');
        Route::patch('/{id}/feedback', [BimbinganController::class, 'updateFeedback'])->name('feedback');
        Route::get('/cetak-kartu/{ta_id}', [BimbinganController::class, 'cetakKartu'])->name('cetak-kartu');
    });

    // Modul Sidang (Sempro & Sidang Skripsi)
    Route::prefix('sidang')->name('sidang.')->group(function () {
        Route::get('/', [SidangController::class, 'index'])->name('index');
        Route::get('/daftar', [SidangController::class, 'createDaftar'])->name('daftar');
        Route::post('/daftar', [SidangController::class, 'storeDaftar'])->name('daftar.store');
        Route::get('/{id}', [SidangController::class, 'show'])->name('show');
        Route::patch('/{id}/jadwal', [SidangController::class, 'updateJadwal'])->name('update-jadwal');
        Route::post('/{id}/nilai', [SidangController::class, 'storeNilai'])->name('nilai');
        Route::patch('/{id}/finalize', [SidangController::class, 'finalizeSidang'])->name('finalize');
    });

    // Modul Yudisium
    Route::prefix('yudisium')->name('yudisium.')->group(function () {
        Route::get('/', [YudisiumController::class, 'index'])->name('index');
        Route::get('/daftar', [YudisiumController::class, 'createDaftar'])->name('daftar');
        Route::post('/daftar', [YudisiumController::class, 'storeDaftar'])->name('daftar.store');
        Route::get('/{id}', [YudisiumController::class, 'show'])->name('show');
        Route::post('/{pendaftaran_id}/upload-berkas', [YudisiumController::class, 'uploadBerkas'])->name('upload-berkas');
        Route::patch('/berkas/{berkas_id}/verify', [YudisiumController::class, 'verifyBerkas'])->name('verify-berkas');
        Route::patch('/{id}/tetapkan-kelulusan', [YudisiumController::class, 'tetapkanKelulusan'])->name('tetapkan-kelulusan');
        Route::get('/{id}/cetak-skl', [YudisiumController::class, 'cetakSkl'])->name('cetak-skl');
    });

    // Modul Pengumuman
    Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::post('/', [PengumumanController::class, 'store'])->name('store')->middleware('role:admin');
        Route::delete('/{id}', [PengumumanController::class, 'destroy'])->name('destroy')->middleware('role:admin');
    });

    // Master Data & Pengaturan Prodi (Khusus Admin)
    Route::middleware('role:admin')->prefix('master')->name('master.')->group(function () {
        Route::post('/prodi/switch', [MasterDataController::class, 'switchProdi'])->name('prodi.switch');
        Route::get('/prodi', [MasterDataController::class, 'prodiIndex'])->name('prodi');
        Route::patch('/prodi/{id}', [MasterDataController::class, 'prodiUpdate'])->name('prodi.update');
        Route::patch('/prodi/{id}/setting', [MasterDataController::class, 'prodiUpdate'])->name('prodi.update-setting');
        Route::get('/dosen', [MasterDataController::class, 'dosenIndex'])->name('dosen');
        Route::post('/dosen', [MasterDataController::class, 'dosenStore'])->name('dosen.store');
        Route::get('/mahasiswa', [MasterDataController::class, 'mahasiswaIndex'])->name('mahasiswa');
        Route::get('/periode-yudisium', [MasterDataController::class, 'periodeYudisiumIndex'])->name('periode-yudisium');
        Route::post('/periode-yudisium', [MasterDataController::class, 'periodeYudisiumStore'])->name('periode-yudisium.store');
        Route::get('/syarat-yudisium', [MasterDataController::class, 'syaratYudisiumIndex'])->name('syarat-yudisium');
        Route::post('/syarat-yudisium', [MasterDataController::class, 'syaratYudisiumStore'])->name('syarat-yudisium.store');
    });
});

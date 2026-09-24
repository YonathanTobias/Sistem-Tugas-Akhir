<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\TugasAkhir;
use App\Models\Bimbingan;
use App\Models\Sidang;
use App\Models\PendaftaranYudisium;
use App\Models\BerkasYudisium;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pengumuman umum
        $pengumumans = Pengumuman::where(function ($q) use ($user) {
            $q->where('target_role', 'all')
              ->orWhere('target_role', $user->role);
        })->orderBy('is_pinned', 'desc')->latest()->take(5)->get();

        if ($user->isAdmin()) {
            $activeProdiId = $user->isAdminProdi() ? $user->prodi_id : session('active_prodi_id');

            $mhsQuery = Mahasiswa::query();
            $dosenQuery = Dosen::query();
            $taQuery = TugasAkhir::query();
            $sidangQuery = Sidang::query();
            $yudisiumQuery = PendaftaranYudisium::query();
            $berkasQuery = BerkasYudisium::where('status', 'menunggu');

            if ($activeProdiId) {
                $mhsQuery->where('prodi_id', $activeProdiId);
                $dosenQuery->where('prodi_id', $activeProdiId);
                $taQuery->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
                $sidangQuery->whereHas('tugasAkhir.mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
                $yudisiumQuery->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
                $berkasQuery->whereHas('pendaftaranYudisium.mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
            }

            $stats = [
                'total_mahasiswa' => $mhsQuery->count(),
                'total_dosen' => $dosenQuery->count(),
                'total_ta_aktif' => (clone $taQuery)->whereIn('status', ['disetujui', 'bimbingan_proposal', 'lulus_sempro', 'bimbingan_skripsi'])->count(),
                'total_sidang_menunggu' => (clone $sidangQuery)->where('status', 'menunggu_jadwal')->count(),
                'total_pendaftar_yudisium' => (clone $yudisiumQuery)->count(),
                'total_berkas_pending' => $berkasQuery->count(),
                'total_yudisium_lulus' => (clone $yudisiumQuery)->where('status', 'lulus')->count(),
            ];

            $recentTAs = (clone $taQuery)->with(['mahasiswa', 'pembimbing1'])->latest()->take(6)->get();
            $recentSidangs = (clone $sidangQuery)->with(['tugasAkhir.mahasiswa', 'penguji1', 'penguji2'])->latest()->take(5)->get();
            $pendingBerkas = $berkasQuery->with(['pendaftaranYudisium.mahasiswa', 'syaratYudisium'])->latest()->take(5)->get();

            return view('dashboard.admin', compact('stats', 'recentTAs', 'recentSidangs', 'pendingBerkas', 'pengumumans'));
        }

        if ($user->isDosen()) {
            $dosen = $user->dosen;
            if (!$dosen) {
                abort(403, 'Data Dosen belum terhubung dengan akun ini.');
            }

            $bimbinganTA1 = TugasAkhir::where('pembimbing1_id', $dosen->id)->with('mahasiswa')->get();
            $bimbinganTA2 = TugasAkhir::where('pembimbing2_id', $dosen->id)->with('mahasiswa')->get();
            $totalBimbingan = $bimbinganTA1->count() + $bimbinganTA2->count();

            $pendingLogbook = Bimbingan::where('dosen_id', $dosen->id)
                ->where('status', 'menunggu')
                ->with('tugasAkhir.mahasiswa')
                ->latest()
                ->get();

            $jadwalSidang = Sidang::where(function ($q) use ($dosen) {
                $q->where('penguji1_id', $dosen->id)
                  ->orWhere('penguji2_id', $dosen->id)
                  ->orWhereHas('tugasAkhir', function ($sub) use ($dosen) {
                      $sub->where('pembimbing1_id', $dosen->id)
                          ->orWhere('pembimbing2_id', $dosen->id);
                  });
            })->where('status', 'dijadwalkan')
              ->with(['tugasAkhir.mahasiswa'])
              ->orderBy('tgl_sidang')
              ->take(5)
              ->get();

            return view('dashboard.dosen', compact('dosen', 'totalBimbingan', 'pendingLogbook', 'jadwalSidang', 'pengumumans', 'bimbinganTA1', 'bimbinganTA2'));
        }

        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;
            if (!$mahasiswa) {
                abort(403, 'Data Mahasiswa belum terhubung dengan akun ini.');
            }

            $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
                ->with(['pembimbing1', 'pembimbing2', 'bimbingans', 'sidangs'])
                ->first();

            $pendaftaranYudisium = PendaftaranYudisium::where('mahasiswa_id', $mahasiswa->id)
                ->with(['periodeYudisium', 'berkasYudisiums.syaratYudisium'])
                ->first();

            $totalBimbinganAcc = $ta ? $ta->bimbingans()->where('status', 'acc')->count() : 0;
            $minBimbingan = 8; // Standar minimal 8x bimbingan ACC

            return view('dashboard.mahasiswa', compact('mahasiswa', 'ta', 'pendaftaranYudisium', 'totalBimbinganAcc', 'minBimbingan', 'pengumumans'));
        }

        return view('dashboard.default');
    }
}

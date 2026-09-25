<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PendaftaranYudisium;
use App\Models\BerkasYudisium;
use App\Models\SyaratYudisium;
use App\Models\PeriodeYudisium;
use App\Models\TugasAkhir;
use App\Models\Mahasiswa;

class YudisiumController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;
            $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();
            $pendaftaran = PendaftaranYudisium::where('mahasiswa_id', $mahasiswa->id)
                ->with(['periodeYudisium', 'berkasYudisiums.syaratYudisium'])
                ->first();

            $periodeAktif = PeriodeYudisium::where('is_aktif', true)->first();
            $syarats = SyaratYudisium::all();

            return view('yudisium.mahasiswa-index', compact('mahasiswa', 'ta', 'pendaftaran', 'periodeAktif', 'syarats'));
        }

        // Admin & Validator View
        $query = PendaftaranYudisium::with([
            'mahasiswa.prodi', 
            'periodeYudisium', 
            'tugasAkhir', 
            'berkasYudisiums.syaratYudisium'
        ]);
        $activeProdiId = $user->isAdminProdi() ? $user->prodi_id : session('active_prodi_id');

        if ($activeProdiId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
        }

        if ($request->filled('periode_id')) {
            $query->where('periode_yudisium_id', $request->periode_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $pendaftarans = $query->latest()->paginate(15);
        $periodes = PeriodeYudisium::orderBy('tgl_buka', 'desc')->get();

        return view('yudisium.admin-index', compact('pendaftarans', 'periodes'));
    }

    public function createDaftar()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();

        if (!$ta || $ta->status !== 'lulus_sidang') {
            return redirect()->route('yudisium.index')->with('error', 'Anda harus lulus Sidang Tugas Akhir terlebih dahulu sebelum mendaftar yudisium.');
        }

        $periodeAktif = PeriodeYudisium::where('is_aktif', true)->first();
        if (!$periodeAktif) {
            return redirect()->route('yudisium.index')->with('error', 'Saat ini belum ada periode pendaftaran yudisium yang dibuka.');
        }

        $syarats = SyaratYudisium::all();

        return view('yudisium.daftar', compact('mahasiswa', 'ta', 'periodeAktif', 'syarats'));
    }

    public function storeDaftar(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->firstOrFail();
        $periodeAktif = PeriodeYudisium::where('is_aktif', true)->firstOrFail();

        $existing = PendaftaranYudisium::where('mahasiswa_id', $mahasiswa->id)->first();
        if ($existing) {
            return redirect()->route('yudisium.index')->with('info', 'Anda sudah terdaftar pada periode yudisium.');
        }

        $pendaftaran = PendaftaranYudisium::create([
            'mahasiswa_id' => $mahasiswa->id,
            'periode_yudisium_id' => $periodeAktif->id,
            'tugas_akhir_id' => $ta->id,
            'ipk_final' => $mahasiswa->ipk,
            'status' => 'diajukan',
            'skl_token' => Str::random(32),
        ]);

        return redirect()->route('yudisium.index')->with('success', 'Pendaftaran yudisium berhasil! Silakan upload berkas bebas tanggungan.');
    }

    public function uploadBerkas(Request $request, $pendaftaran_id)
    {
        $pendaftaran = PendaftaranYudisium::findOrFail($pendaftaran_id);

        $validated = $request->validate([
            'syarat_yudisium_id' => 'required|exists:syarat_yudisiums,id',
            'file_berkas' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $filePath = $request->file('file_berkas')->store('berkas_yudisium', 'public');

        BerkasYudisium::updateOrCreate(
            [
                'pendaftaran_yudisium_id' => $pendaftaran->id,
                'syarat_yudisium_id' => $validated['syarat_yudisium_id'],
            ],
            [
                'file_path' => $filePath,
                'status' => 'menunggu',
                'catatan_validator' => null,
            ]
        );

        return back()->with('success', 'Berkas berhasil diupload! Menunggu verifikasi petugas.');
    }

    public function show($id)
    {
        $pendaftaran = PendaftaranYudisium::with([
            'mahasiswa.prodi', 
            'periodeYudisium', 
            'tugasAkhir.pembimbing1', 
            'tugasAkhir.pembimbing2',
            'berkasYudisiums.syaratYudisium',
            'berkasYudisiums.verifikator'
        ])->findOrFail($id);

        $syarats = SyaratYudisium::all();

        return view('yudisium.show', compact('pendaftaran', 'syarats'));
    }

    public function verifyBerkas(Request $request, $berkas_id)
    {
        $berkas = BerkasYudisium::with('pendaftaranYudisium')->findOrFail($berkas_id);

        $validated = $request->validate([
            'status' => 'required|in:menunggu,valid,ditolak',
            'catatan_validator' => 'nullable|string',
        ]);

        $berkas->update([
            'status' => $validated['status'],
            'catatan_validator' => $validated['catatan_validator'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Cek jika seluruh berkas wajib valid
        if ($berkas->pendaftaranYudisium->isSemuaBerkasValid()) {
            $berkas->pendaftaranYudisium->update(['status' => 'diverifikasi']);
        }

        return back()->with('success', 'Status berkas berhasil diverifikasi!');
    }

    public function tetapkanKelulusan(Request $request, $id)
    {
        $pendaftaran = PendaftaranYudisium::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:lulus,ditolak',
            'nomor_sk' => 'nullable|string',
            'tanggal_sk' => 'nullable|date',
            'tgl_lulus' => 'required_if:status,lulus|nullable|date',
            'predikat' => 'required_if:status,lulus|nullable|in:Dengan Pujian,Sangat Memuaskan,Memuaskan',
            'catatan_kelulusan' => 'nullable|string',
        ]);

        $pendaftaran->update($validated);

        if ($validated['status'] === 'lulus' && $pendaftaran->tugasAkhir) {
            $pendaftaran->tugasAkhir->update(['status' => 'selesai']);
        }

        return back()->with('success', 'Penetapan hasil kelulusan yudisium berhasil disimpan!');
    }

    public function cetakSkl($id)
    {
        $pendaftaran = PendaftaranYudisium::with(['mahasiswa.prodi', 'periodeYudisium', 'tugasAkhir'])->findOrFail($id);

        if ($pendaftaran->status !== 'lulus') {
            return back()->with('error', 'SKL hanya dapat dicetak untuk mahasiswa yang telah dinyatakan LULUS yudisium.');
        }

        // Verification URL for QR Code
        $verificationUrl = route('verify.skl', ['token' => $pendaftaran->skl_token]);

        return view('yudisium.cetak-skl', compact('pendaftaran', 'verificationUrl'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TugasAkhir;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\PeriodeAkademik;

class TugasAkhirController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;
            $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
                ->with(['pembimbing1', 'pembimbing2', 'bimbingans', 'sidangs'])
                ->first();
            $periodeAktif = PeriodeAkademik::where('is_aktif', true)->first();

            return view('tugas-akhir.mahasiswa-index', compact('ta', 'periodeAktif', 'mahasiswa'));
        }

        $query = TugasAkhir::with(['mahasiswa.prodi', 'pembimbing1', 'pembimbing2', 'periodeAkademik']);
        $activeProdiId = $user->isAdminProdi() ? $user->prodi_id : session('active_prodi_id');

        if ($activeProdiId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
        }

        if ($user->isDosen()) {
            $dosenId = $user->dosen->id;
            $query->where(function ($q) use ($dosenId) {
                $q->where('pembimbing1_id', $dosenId)
                  ->orWhere('pembimbing2_id', $dosenId);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($sub) use ($search) {
                      $sub->where('nama_lengkap', 'like', "%{$search}%")
                          ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        $tugasAkhirs = $query->latest()->paginate(15);
        $dosensQuery = Dosen::orderBy('nama_lengkap');
        if ($activeProdiId) {
            $dosensQuery->where('prodi_id', $activeProdiId);
        }
        $dosens = $dosensQuery->get();

        return view('tugas-akhir.admin-index', compact('tugasAkhirs', 'dosens'));
    }

    public function create()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Akses khusus mahasiswa.');
        }

        $existingTA = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();
        if ($existingTA && !in_array($existingTA->status, ['ditolak', 'revisi_judul'])) {
            return redirect()->route('tugas-akhir.index')->with('info', 'Anda sudah memiliki pengajuan Tugas Akhir aktif.');
        }

        $periodeAktif = PeriodeAkademik::where('is_aktif', true)->first();
        $dosens = Dosen::orderBy('nama_lengkap')->get();

        return view('tugas-akhir.create', compact('mahasiswa', 'periodeAktif', 'dosens', 'existingTA'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'bidang_kajian' => 'required|string|max:255',
            'abstrak' => 'required|string|min:50',
            'file_proposal' => 'nullable|file|mimes:pdf,docx|max:10240',
            'usulan_pembimbing1_id' => 'nullable|exists:dosens,id',
        ]);

        $filePath = null;
        if ($request->hasFile('file_proposal')) {
            $filePath = $request->file('file_proposal')->store('proposals', 'public');
        }

        $periodeAktif = PeriodeAkademik::where('is_aktif', true)->first();

        $ta = TugasAkhir::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'periode_akademik_id' => $periodeAktif ? $periodeAktif->id : null,
                'judul' => $validated['judul'],
                'bidang_kajian' => $validated['bidang_kajian'],
                'abstrak' => $validated['abstrak'],
                'file_proposal' => $filePath ?? optional(TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first())->file_proposal,
                'status' => 'pengajuan',
                'pembimbing1_id' => $validated['usulan_pembimbing1_id'] ?? null,
                'tgl_pengajuan' => now(),
            ]
        );

        return redirect()->route('tugas-akhir.index')->with('success', 'Pengajuan judul Tugas Akhir berhasil dikirim! Menunggu verifikasi Prodi.');
    }

    public function show($id)
    {
        $ta = TugasAkhir::with(['mahasiswa.prodi', 'pembimbing1', 'pembimbing2', 'bimbingans.dosen', 'sidangs'])->findOrFail($id);
        $dosens = Dosen::orderBy('nama_lengkap')->get();

        return view('tugas-akhir.show', compact('ta', 'dosens'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ta = TugasAkhir::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pengajuan,revisi_judul,disetujui,bimbingan_proposal,siap_sempro,lulus_sempro,bimbingan_skripsi,siap_sidang,lulus_sidang,selesai,ditolak',
            'pembimbing1_id' => 'nullable|exists:dosens,id',
            'pembimbing2_id' => 'nullable|exists:dosens,id',
            'catatan_prodi' => 'nullable|string',
        ]);

        $ta->status = $validated['status'];
        if (isset($validated['pembimbing1_id'])) $ta->pembimbing1_id = $validated['pembimbing1_id'];
        if (isset($validated['pembimbing2_id'])) $ta->pembimbing2_id = $validated['pembimbing2_id'];
        if (isset($validated['catatan_prodi'])) $ta->catatan_prodi = $validated['catatan_prodi'];

        if ($validated['status'] === 'disetujui' && !$ta->tgl_disetujui) {
            $ta->tgl_disetujui = now();
        }

        $ta->save();

        return back()->with('success', 'Status dan data Tugas Akhir berhasil diperbarui!');
    }
}

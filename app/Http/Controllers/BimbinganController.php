<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Bimbingan;
use App\Models\TugasAkhir;
use App\Models\Dosen;

class BimbinganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;
            $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)
                ->with(['pembimbing1', 'pembimbing2', 'bimbingans.dosen'])
                ->first();

            if (!$ta) {
                return redirect()->route('tugas-akhir.create')->with('info', 'Silakan ajukan judul Tugas Akhir terlebih dahulu.');
            }

            $bimbingans = $ta->bimbingans()->orderBy('tgl_bimbingan', 'desc')->get();
            $totalAcc = $bimbingans->where('status', 'acc')->count();

            return view('bimbingan.mahasiswa-index', compact('ta', 'bimbingans', 'totalAcc'));
        }

        if ($user->isDosen()) {
            $dosen = $user->dosen;
            $bimbinganQuery = Bimbingan::where('dosen_id', $dosen->id)->with('tugasAkhir.mahasiswa');

            if ($request->filled('status')) {
                $bimbinganQuery->where('status', $request->status);
            }

            $bimbingans = $bimbinganQuery->latest()->paginate(15);
            return view('bimbingan.dosen-index', compact('bimbingans', 'dosen'));
        }

        // Admin view
        $activeProdiId = $user->isAdminProdi() ? $user->prodi_id : session('active_prodi_id');
        $bimbinganQuery = Bimbingan::with(['tugasAkhir.mahasiswa', 'dosen']);
        if ($activeProdiId) {
            $bimbinganQuery->whereHas('tugasAkhir.mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
        }
        $bimbingans = $bimbinganQuery->latest()->paginate(20);
        return view('bimbingan.admin-index', compact('bimbingans'));
    }

    public function create()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();

        if (!$ta || !in_array($ta->status, ['disetujui', 'bimbingan_proposal', 'lulus_sempro', 'bimbingan_skripsi', 'siap_sidang'])) {
            return redirect()->route('tugas-akhir.index')->with('error', 'Status Tugas Akhir Anda belum dalam tahap bimbingan.');
        }

        $pembimbings = collect([$ta->pembimbing1, $ta->pembimbing2])->filter();

        return view('bimbingan.create', compact('ta', 'pembimbings'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->firstOrFail();

        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tgl_bimbingan' => 'required|date',
            'bab' => 'required|string|max:100',
            'topik_bimbingan' => 'required|string|max:255',
            'uraian_mahasiswa' => 'required|string',
            'file_draft' => 'nullable|file|mimes:pdf,doc,docx,zip|max:15360',
        ]);

        $filePath = null;
        if ($request->hasFile('file_draft')) {
            $filePath = $request->file('file_draft')->store('bimbingan_drafts', 'public');
        }

        Bimbingan::create([
            'tugas_akhir_id' => $ta->id,
            'dosen_id' => $validated['dosen_id'],
            'tgl_bimbingan' => $validated['tgl_bimbingan'],
            'bab' => $validated['bab'],
            'topik_bimbingan' => $validated['topik_bimbingan'],
            'uraian_mahasiswa' => $validated['uraian_mahasiswa'],
            'file_draft' => $filePath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('bimbingan.index')->with('success', 'Logbook bimbingan berhasil dicatat! Menunggu respon dosen.');
    }

    public function updateFeedback(Request $request, $id)
    {
        $bimbingan = Bimbingan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:menunggu,revisi,acc',
            'catatan_dosen' => 'required|string',
            'file_revisi_dosen' => 'nullable|file|mimes:pdf,doc,docx,zip|max:15360',
        ]);

        $filePath = $bimbingan->file_revisi_dosen;
        if ($request->hasFile('file_revisi_dosen')) {
            $filePath = $request->file('file_revisi_dosen')->store('bimbingan_revisi', 'public');
        }

        $bimbingan->update([
            'status' => $validated['status'],
            'catatan_dosen' => $validated['catatan_dosen'],
            'file_revisi_dosen' => $filePath,
        ]);

        return back()->with('success', 'Feedback bimbingan berhasil disimpan!');
    }

    public function cetakKartu($ta_id)
    {
        $ta = TugasAkhir::with(['mahasiswa.prodi', 'pembimbing1', 'pembimbing2', 'bimbingans.dosen'])->findOrFail($ta_id);
        $bimbingans = $ta->bimbingans()->where('status', 'acc')->orderBy('tgl_bimbingan')->get();

        return view('bimbingan.cetak-kartu', compact('ta', 'bimbingans'));
    }
}

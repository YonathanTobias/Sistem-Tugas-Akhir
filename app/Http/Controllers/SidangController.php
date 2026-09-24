<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sidang;
use App\Models\NilaiSidang;
use App\Models\TugasAkhir;
use App\Models\Dosen;

class SidangController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;
            $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();
            $sidangs = $ta ? $ta->sidangs()->with(['penguji1', 'penguji2', 'nilaiSidangs.dosen'])->latest()->get() : collect();

            return view('sidang.mahasiswa-index', compact('ta', 'sidangs'));
        }

        $query = Sidang::with(['tugasAkhir.mahasiswa.prodi', 'penguji1', 'penguji2', 'nilaiSidangs']);
        $activeProdiId = $user->isAdminProdi() ? $user->prodi_id : session('active_prodi_id');

        if ($activeProdiId) {
            $query->whereHas('tugasAkhir.mahasiswa', fn($q) => $q->where('prodi_id', $activeProdiId));
        }

        if ($user->isDosen()) {
            $dosenId = $user->dosen->id;
            $query->where(function ($q) use ($dosenId) {
                $q->where('penguji1_id', $dosenId)
                  ->orWhere('penguji2_id', $dosenId)
                  ->orWhereHas('tugasAkhir', function ($sub) use ($dosenId) {
                      $sub->where('pembimbing1_id', $dosenId)
                          ->orWhere('pembimbing2_id', $dosenId);
                  });
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sidangs = $query->latest('tgl_sidang')->paginate(15);
        $dosensQuery = Dosen::orderBy('nama_lengkap');
        if ($activeProdiId) {
            $dosensQuery->where('prodi_id', $activeProdiId);
        }
        $dosens = $dosensQuery->get();

        return view('sidang.admin-index', compact('sidangs', 'dosens'));
    }

    public function createDaftar(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->firstOrFail();
        $jenis = $request->get('jenis', 'sempro'); // sempro or sidang_akhir

        return view('sidang.daftar', compact('ta', 'jenis'));
    }

    public function storeDaftar(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->firstOrFail();

        $validated = $request->validate([
            'jenis' => 'required|in:sempro,sidang_akhir',
        ]);

        // Cek apakah sudah mendaftar sidang dengan jenis yang sama
        $existing = Sidang::where('tugas_akhir_id', $ta->id)
            ->where('jenis', $validated['jenis'])
            ->whereIn('status', ['menunggu_jadwal', 'dijadwalkan'])
            ->first();

        if ($existing) {
            return redirect()->route('sidang.index')->with('info', 'Anda sudah mendaftar ' . strtoupper($validated['jenis']) . ' dan sedang menunggu proses.');
        }

        Sidang::create([
            'tugas_akhir_id' => $ta->id,
            'jenis' => $validated['jenis'],
            'status' => 'menunggu_jadwal',
        ]);

        if ($validated['jenis'] === 'sempro') {
            $ta->update(['status' => 'siap_sempro']);
        } else {
            $ta->update(['status' => 'siap_sidang']);
        }

        return redirect()->route('sidang.index')->with('success', 'Pendaftaran ' . strtoupper($validated['jenis']) . ' berhasil dikirim ke Admin Prodi!');
    }

    public function show($id)
    {
        $sidang = Sidang::with([
            'tugasAkhir.mahasiswa.prodi', 
            'tugasAkhir.pembimbing1', 
            'tugasAkhir.pembimbing2',
            'penguji1', 
            'penguji2', 
            'nilaiSidangs.dosen'
        ])->findOrFail($id);

        $dosens = Dosen::orderBy('nama_lengkap')->get();

        return view('sidang.show', compact('sidang', 'dosens'));
    }

    public function updateJadwal(Request $request, $id)
    {
        $sidang = Sidang::findOrFail($id);

        $validated = $request->validate([
            'tgl_sidang' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string|max:100',
            'penguji1_id' => 'required|exists:dosens,id',
            'penguji2_id' => 'nullable|exists:dosens,id',
            'status' => 'required|in:menunggu_jadwal,dijadwalkan,selesai,lulus,revisi,tidak_lulus',
        ]);

        $sidang->update($validated);

        return back()->with('success', 'Jadwal dan penguji sidang berhasil diperbarui!');
    }

    public function storeNilai(Request $request, $id)
    {
        $sidang = Sidang::findOrFail($id);
        $user = Auth::user();
        $dosen = $user->dosen;

        if (!$dosen) {
            abort(403, 'Hanya dosen penguji/pembimbing yang dapat menginput nilai.');
        }

        $validated = $request->validate([
            'peran' => 'required|in:pembimbing1,pembimbing2,penguji1,penguji2',
            'nilai_presentasi' => 'required|numeric|min:0|max:100',
            'nilai_materi' => 'required|numeric|min:0|max:100',
            'nilai_tanya_jawab' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $totalNilai = ($validated['nilai_presentasi'] * 0.25) + ($validated['nilai_materi'] * 0.35) + ($validated['nilai_tanya_jawab'] * 0.40);

        NilaiSidang::updateOrCreate(
            ['sidang_id' => $sidang->id, 'dosen_id' => $dosen->id],
            [
                'peran' => $validated['peran'],
                'nilai_presentasi' => $validated['nilai_presentasi'],
                'nilai_materi' => $validated['nilai_materi'],
                'nilai_tanya_jawab' => $validated['nilai_tanya_jawab'],
                'total_nilai' => $totalNilai,
                'catatan' => $validated['catatan'],
            ]
        );

        // Hitung rata-rata nilai akhir jika semua penguji telah menilai
        $semuaNilai = $sidang->nilaiSidangs;
        if ($semuaNilai->count() > 0) {
            $avgScore = $semuaNilai->avg('total_nilai');
            $grade = 'A';
            if ($avgScore < 60) $grade = 'E';
            elseif ($avgScore < 70) $grade = 'C';
            elseif ($avgScore < 80) $grade = 'B';
            elseif ($avgScore < 85) $grade = 'B+';
            else $grade = 'A';

            $sidang->update([
                'nilai_akhir' => $avgScore,
                'grade_huruf' => $grade,
            ]);
        }

        return back()->with('success', 'Nilai sidang berhasil disimpan!');
    }

    public function finalizeSidang(Request $request, $id)
    {
        $sidang = Sidang::with('tugasAkhir')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:lulus,revisi,tidak_lulus',
            'berita_acara' => 'required|string',
            'catatan_revisi' => 'nullable|string',
        ]);

        $sidang->update([
            'status' => $validated['status'],
            'berita_acara' => $validated['berita_acara'],
            'catatan_revisi' => $validated['catatan_revisi'],
        ]);

        // Update status TA
        if ($sidang->jenis === 'sempro' && $validated['status'] === 'lulus') {
            $sidang->tugasAkhir->update(['status' => 'lulus_sempro']);
        } elseif ($sidang->jenis === 'sidang_akhir' && $validated['status'] === 'lulus') {
            $sidang->tugasAkhir->update(['status' => 'lulus_sidang']);
        }

        return back()->with('success', 'Hasil sidang & berita acara berhasil difinalisasi!');
    }
}

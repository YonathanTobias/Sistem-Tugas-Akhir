<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Prodi;
use App\Models\PeriodeAkademik;
use App\Models\PeriodeYudisium;
use App\Models\SyaratYudisium;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;

class MasterDataController extends Controller
{
    // Switch Active Prodi Scope
    public function switchProdi(Request $request)
    {
        $prodiId = $request->get('prodi_id');
        if ($prodiId && $prodiId !== 'all') {
            $prodi = Prodi::findOrFail($prodiId);
            session(['active_prodi_id' => $prodi->id, 'active_prodi_nama' => $prodi->nama_prodi]);
        } else {
            session()->forget(['active_prodi_id', 'active_prodi_nama']);
        }

        return back()->with('success', 'Fokus Program Studi berhasil diubah.');
    }

    // Program Studi Setting
    public function prodiIndex()
    {
        $prodis = Prodi::withCount(['mahasiswas', 'dosens'])->get();
        return view('master.prodi', compact('prodis'));
    }

    public function prodiUpdate(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);

        $validated = $request->validate([
            'nama_prodi' => 'nullable|string|max:100',
            'jenjang' => 'nullable|string|max:10',
            'kaprodi_nama' => 'required|string|max:255',
            'kaprodi_nip' => 'nullable|string|max:50',
            'kaprodi_nidn' => 'nullable|string|max:50',
            'gelar_lulusan' => 'required|string|max:30',
            'format_sk_prefix' => 'required|string|max:50',
            'min_bimbingan_acc' => 'required|integer|min:1',
        ]);

        if (empty($validated['kaprodi_nip']) && !empty($validated['kaprodi_nidn'])) {
            $validated['kaprodi_nip'] = $validated['kaprodi_nidn'];
        }
        unset($validated['kaprodi_nidn']);

        $prodi->update(array_filter($validated, fn($val) => !is_null($val)));

        return back()->with('success', 'Pengaturan Program Studi ' . $prodi->nama_prodi . ' berhasil diperbarui!');
    }

    // Dosen
    public function dosenIndex()
    {
        $user = auth()->user();
        $activeProdiId = $user->isAdminProdi() ? $user->prodi_id : session('active_prodi_id');

        $query = Dosen::with(['user', 'prodi']);
        if ($activeProdiId) {
            $query->where('prodi_id', $activeProdiId);
        }

        $dosens = $query->paginate(15);
        $prodis = Prodi::all();
        return view('master.dosen', compact('dosens', 'prodis'));
    }

    public function dosenStore(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:50',
            'nidn' => 'required|string|unique:dosens,nidn',
            'nip' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'prodi_id' => 'required|exists:prodis,id',
            'bidang_keahlian' => 'nullable|string',
            'kuota_bimbingan' => 'required|integer|min:1',
            'no_hp' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['nama_lengkap'] . ($validated['gelar'] ? ', ' . $validated['gelar'] : ''),
            'username' => $validated['nidn'],
            'email' => $validated['email'],
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'phone' => $validated['no_hp'],
            'is_active' => true,
        ]);

        Dosen::create([
            'user_id' => $user->id,
            'prodi_id' => $validated['prodi_id'],
            'nidn' => $validated['nidn'],
            'nip' => $validated['nip'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'gelar' => $validated['gelar'],
            'bidang_keahlian' => $validated['bidang_keahlian'],
            'kuota_bimbingan' => $validated['kuota_bimbingan'],
            'no_hp' => $validated['no_hp'],
        ]);

        return back()->with('success', 'Data Dosen berhasil ditambahkan! Password default: password');
    }

    // Mahasiswa
    public function mahasiswaIndex()
    {
        $user = auth()->user();
        $activeProdiId = $user->isAdminProdi() ? $user->prodi_id : session('active_prodi_id');

        $query = Mahasiswa::with(['user', 'prodi']);
        if ($activeProdiId) {
            $query->where('prodi_id', $activeProdiId);
        }

        $mahasiswas = $query->paginate(15);
        $prodis = Prodi::all();
        return view('master.mahasiswa', compact('mahasiswas', 'prodis'));
    }

    // Periode Yudisium
    public function periodeYudisiumIndex()
    {
        $periodes = PeriodeYudisium::latest()->paginate(10);
        return view('master.periode-yudisium', compact('periodes'));
    }

    public function periodeYudisiumStore(Request $request)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tahun_akademik' => 'required|string|max:30',
            'tgl_buka' => 'required|date',
            'tgl_tutup' => 'required|date|after_or_equal:tgl_buka',
            'tgl_pelaksanaan' => 'required|date|after_or_equal:tgl_tutup',
            'kuota' => 'nullable|integer|min:1',
            'is_aktif' => 'nullable|boolean',
        ]);

        if ($request->has('is_aktif')) {
            PeriodeYudisium::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        PeriodeYudisium::create([
            'nama_periode' => $validated['nama_periode'],
            'tahun_akademik' => $validated['tahun_akademik'],
            'tgl_buka' => $validated['tgl_buka'],
            'tgl_tutup' => $validated['tgl_tutup'],
            'tgl_pelaksanaan' => $validated['tgl_pelaksanaan'],
            'kuota' => $validated['kuota'],
            'is_aktif' => $request->has('is_aktif'),
        ]);

        return back()->with('success', 'Periode Yudisium berhasil ditambahkan!');
    }

    // Syarat Yudisium
    public function syaratYudisiumIndex()
    {
        $syarats = SyaratYudisium::all();
        return view('master.syarat-yudisium', compact('syarats'));
    }

    public function syaratYudisiumStore(Request $request)
    {
        $validated = $request->validate([
            'nama_syarat' => 'required|string|max:255',
            'kode_syarat' => 'required|string|max:50|unique:syarat_yudisiums,kode_syarat',
            'kategori' => 'required|in:perpustakaan,keuangan,laboratorium,akademik,lainnya',
            'deskripsi' => 'nullable|string',
            'is_wajib' => 'nullable|boolean',
        ]);

        SyaratYudisium::create([
            'nama_syarat' => $validated['nama_syarat'],
            'kode_syarat' => strtoupper($validated['kode_syarat']),
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'],
            'is_wajib' => $request->has('is_wajib'),
        ]);

        return back()->with('success', 'Syarat Bebas Tanggungan berhasil ditambahkan!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::with('author')->latest()->paginate(10);
        return view('pengumuman.index', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string|max:50',
            'target_role' => 'required|in:all,mahasiswa,dosen',
            'is_pinned' => 'nullable|boolean',
        ]);

        Pengumuman::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'kategori' => $validated['kategori'],
            'target_role' => $validated['target_role'],
            'is_pinned' => $request->has('is_pinned'),
        ]);

        return back()->with('success', 'Pengumuman berhasil diterbitkan!');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}

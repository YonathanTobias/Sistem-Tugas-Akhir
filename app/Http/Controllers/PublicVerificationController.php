<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranYudisium;

class PublicVerificationController extends Controller
{
    public function verifySkl($token)
    {
        $pendaftaran = PendaftaranYudisium::where('skl_token', $token)
            ->with(['mahasiswa.prodi', 'periodeYudisium', 'tugasAkhir'])
            ->first();

        return view('public.verify-skl', compact('pendaftaran', 'token'));
    }
}

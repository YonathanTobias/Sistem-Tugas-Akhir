<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Prodi;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();

            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['login' => 'Akun Anda tidak aktif. Silakan hubungi admin.']);
            }

            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name);
        }

        return back()->withErrors([
            'login' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $prodis = Prodi::all();
        return view('auth.register', compact('prodis'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:30|unique:mahasiswas,nim|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'prodi_id' => 'required|exists:prodis,id',
            'angkatan' => 'required|string|max:4',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['nim'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
            'phone' => $validated['no_hp'],
            'is_active' => true,
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'prodi_id' => $validated['prodi_id'],
            'nim' => $validated['nim'],
            'nama_lengkap' => $validated['name'],
            'angkatan' => $validated['angkatan'],
            'semester' => 7,
            'no_hp' => $validated['no_hp'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di SIMTA-Yudisium.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}

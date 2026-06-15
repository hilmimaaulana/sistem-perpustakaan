<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Tampilan Halaman Login gais
    public function showLogin() {
        if (session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // Proses Pengecekan Akun Admin
    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // KITA SET AKUN ADMIN DEFAULT KELOMPOK KAMU DI SINI GAIS!
        // Kamu bisa ganti username & password-nya sesuai selera kelompok ya gais
        if ($request->username === 'admin' && $request->password === 'admin123') {
            
            // Simpan status login di memori session laptop gais
            session(['admin_logged_in' => true]);
            
            return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Kembali Admin! 👋');
        }

        // Jika salah, kita tendang balik dengan pesan eror gais
        return redirect()->back()->withErrors(['error' => 'Username atau Password salah gais!']);
    }

    // Proses Keluar (Logout)
    public function logout() {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'Berhasil logout, sistem aman gais!');
    }
}
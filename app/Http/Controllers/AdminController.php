<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Setting;
use App\Models\Peminjaman; // <-- Memanggil model Peminjaman agar bisa dipantau admin gais

class AdminController extends Controller
{
    // Tampilan Dashboard Admin (DIPERBARUI: Ikut membawa data Peminjaman gais)
    public function index() {
        $bukus = Buku::all();
        $peminjamans = Peminjaman::all(); // <-- Mengambil seluruh riwayat transaksi peminjaman gais
        $apiToken = Setting::where('key', 'api_token')->first()->value ?? '';
        $apiUrl = Setting::where('key', 'api_url')->first()->value ?? 'http://127.0.0.1:8000/api';
        
        return view('admin.dashboard', compact('bukus', 'peminjamans', 'apiToken', 'apiUrl'));
    }

    // CRUD BUKU: Simpan Buku Baru (TIDAK BERUBAH)
    public function storeBuku(Request $request) {
        $request->validate([
            'judul' => 'required',
            'kode_buku' => 'required|unique:bukus,kode_buku', // Menyesuaikan pengecekan ke kolom kode_buku kamu gais
            'pengarang' => 'required', // Validasi input pengarang wajib diisi gais
        ]);

        // KITA PETAKAN MANUAL AGAR MASUK KE KOLOM DATABASE KAMU GAIS
        Buku::create([
            'kode_buku'  => $request->kode_buku,
            'judul_buku' => $request->judul,      // Menghubungkan input form 'judul' ke kolom 'judul_buku'
            'pengarang'  => $request->pengarang,  // Menghubungkan input form 'pengarang' ke kolom 'pengarang'
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    // CRUD BUKU: Hapus Buku (TIDAK BERUBAH)
    public function destroyBuku($id) {
        Buku::find($id)->delete();
        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }

    // UPDATE SETTING: Simpan Konfigurasi API (TIDAK BERUBAH)
    public function updateSettings(Request $request) {
        Setting::updateOrCreate(['key' => 'api_url'], ['value' => $request->api_url]);
        Setting::updateOrCreate(['key' => 'api_token'], ['value' => $request->api_token]);

        return redirect()->back()->with('success', 'Konfigurasi API berhasil diperbarui!');
    }

    // =========================================================================
    // ⚙️ FITUR BARU: Mengubah Status Peminjaman (SUPER KEBAL & ANTI MACET GAIS)
    // =========================================================================
    public function updateStatus($id) {
        $peminjaman = Peminjaman::find($id);
        
        // Kita ubah dulu status dari database menjadi huruf kecil semua gais biar aman dievaluasi
        $statusSekarang = strtolower($peminjaman->status);

        // Pengecekan kebal: jika kosong, atau berbunyi 'dipinjam', kita balik jadi Dikembalikan
        if (empty($statusSekarang) || $statusSekarang == 'dipinjam') {
            $peminjaman->status = 'Dikembalikan';
        } else {
            // Jika selain itu (berarti statusnya sedang dikembalikan), kita balik jadi Dipinjam
            $peminjaman->status = 'Dipinjam';
        }

        // Simpan langsung perubahan objeknya gais!
        $peminjaman->save();

        return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui gais!');
    }
}
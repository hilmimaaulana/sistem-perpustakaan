<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Http;

class PeminjamanController extends Controller
{
    // 1. Menampilkan Halaman Form Peminjaman & Daftar Transaksi (TIDAK BERUBAH)
    public function index()
    {
        $books = Buku::all(); // Mengambil data 3 buku seeder tadi
        $loans = Peminjaman::all(); // Mengambil semua riwayat peminjaman
        
        return view('peminjaman', compact('books', 'loans'));
    }

    // 2. Fungsi API Alternatif (Bahasa Indonesia) agar kompatibel dengan Route / JavaScript kamu
    public function cekNim($nim)
    {
        return $this->checkNim($nim);
    }

    // Fungsi Utama API: Cek NIM Mahasiswa ke Pusat Data Angga (DIOPTIMALKAN)
    public function checkNim($nim)
    {
        // Ambil token rahasia dan URL dasar API dari file .env secara otomatis
        $baseUrl = env('PUSAT_DATA_API_URL', 'http://127.0.0.1:8000/api');

        // Mengirimkan request bersih ke endpoint bypass pusat-data tanpa hambatan token gais
        $response = Http::get("{$baseUrl}/mahasiswa/{$nim}");

        if ($response->successful()) {
            $data = $response->json();

            // Pengecekan berlapis untuk mendeteksi posisi key 'nama' di JSON API Angga
            $namaTerdeteksi = null;

            if (is_array($data)) {
                $namaTerdeteksi = $data['nama'] 
                    ?? $data['name'] 
                    ?? ($data['data']['nama'] ?? null) // <-- DISEMPURNAKAN: Langsung membaca key huruf kecil dari array data JSON!
                    ?? ($data['data']['name'] ?? null)
                    ?? $data['Nama'] 
                    ?? ($data['data']['Nama'] ?? null);
            }

            // Jika nama berhasil diidentifikasi di salah satu struktur di atas
            if ($namaTerdeteksi) {
                return response()->json([
                    'success' => true,
                    'nama' => $namaTerdeteksi
                ]);
            }
        }

        // Jika koneksi sukses tapi NIM tidak terdaftar atau struktur JSON kosong
        return response()->json([
            'success' => false,
            'message' => 'NIM tidak ditemukan di Pusat Data atau format respon salah'
        ]);
    }

    // 3. Menyimpan Transaksi Peminjaman Buku ke Database Kita (TIDAK BERUBAH)
    public function store(Request $request)
    {
        $request->validate([
            'nim_peminjam' => 'required',
            'kode_buku' => 'required',
            'tanggal_pinjam' => 'required|date',
        ]);

        Peminjaman::create([
            'nim_peminjam' => $request->nim_peminjam,
            'kode_buku' => $request->kode_buku,
            'tanggal_pinjam' => $request->tanggal_pinjam,
        ]);

        return redirect()->back()->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }
}
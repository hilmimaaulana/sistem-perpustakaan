<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Setting; // Memanggil model Setting untuk mengambil konfigurasi admin gais
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

    // Fungsi Utama API: Otomatis Cek NIM Mahasiswa atau NIP Dosen secara berlapis gais
    public function checkNim($nim)
    {
        // Ambil URL dasar API dan Token (Prioritas dari database Admin, kalau kosong pakai bawaan .env gais)
        $baseUrl = Setting::where('key', 'api_url')->first()->value 
            ?? env('PUSAT_DATA_API_URL', 'http://127.0.0.1:8000/api');
        
        $token = Setting::where('key', 'api_token')->first()->value 
            ?? env('PUSAT_DATA_API_TOKEN');

        // 🔍 TAHAP 1: Tembak ke API Mahasiswa dulu gais
        $responseMhs = Http::withToken($token)->get("{$baseUrl}/mahasiswa/{$nim}");

        if ($responseMhs->successful()) {
            $data = $responseMhs->json();
            $namaTerdeteksi = null;

            if (is_array($data)) {
                $namaTerdeteksi = $data['nama'] 
                    ?? $data['name'] 
                    ?? ($data['data']['nama'] ?? null)
                    ?? ($data['data']['name'] ?? null)
                    ?? $data['Nama'] 
                    ?? ($data['data']['Nama'] ?? null);
            }

            if ($namaTerdeteksi) {
                return response()->json([
                    'success' => true,
                    'nama' => $namaTerdeteksi . ' (Mahasiswa)' // Kita kasih tanda biar admin tahu ini Mahasiswa gais
                ]);
            }
        }

        // 🔍 TAHAP 2: Kalau Mahasiswa tidak ketemu, otomatis beralih tembak ke API Dosen gais!
        $responseDosen = Http::withToken($token)->get("{$baseUrl}/dosen/{$nim}");

        if ($responseDosen->successful()) {
            $dataDosen = $responseDosen->json();
            $namaDosenTerdeteksi = null;

            if (is_array($dataDosen)) {
                $namaDosenTerdeteksi = $dataDosen['nama'] 
                    ?? $dataDosen['name'] 
                    ?? ($dataDosen['data']['nama'] ?? null)
                    ?? ($dataDosen['data']['name'] ?? null)
                    ?? $dataDosen['Nama'] 
                    ?? ($dataDosen['data']['Nama'] ?? null);
            }

            if ($namaDosenTerdeteksi) {
                return response()->json([
                    'success' => true,
                    'nama' => $namaDosenTerdeteksi . ' (Dosen)' // Kita kasih tanda biar admin tahu ini Dosen gais
                ]);
            }
        }

        // Jika dicari di Mahasiswa maupun Dosen tetap tidak ketemu gais
        return response()->json([
            'success' => false,
            'message' => 'NIM / NIP tidak ditemukan di Pusat Data atau format respon salah'
        ]);
    }

    // 3. Menyimpan Transaksi Peminjaman Buku ke Database Kita (TERMASUK STATUS DEFAULT UNTUK ADMIN)
    public function store(Request $request)
    {
        $request->validate([
            'nim_peminjam' => 'required',
            'kode_buku' => 'required',
            'tanggal_pinjam' => 'required|date',
        ]);

        // Menyimpan transaksi dengan menyelipkan status awal 'Dipinjam' gais
        Peminjaman::create([
            'nim_peminjam' => $request->nim_peminjam,
            'kode_buku' => $request->kode_buku,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'Dipinjam' // Kolom ini yang nanti akan diubah-ubah oleh admin gais
        ]);

        return redirect()->back()->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }
}
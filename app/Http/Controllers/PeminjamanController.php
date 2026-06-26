<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Setting; // Memanggil model Setting untuk mengambil konfigurasi admin gais
use Illuminate\Support\Facades\Http;

class PeminjamanController extends Controller
{
    // 1. Menampilkan Halaman Form Peminjaman & Daftar Transaksi
    public function index()
    {
        $books = Buku::all(); // Mengambil data buku
        // HANYA tampilkan buku yang SEDANG DIPINJAM (belum dikembalikan)
        $loans = Peminjaman::where('status', 'Dipinjam')
                          ->orWhereNull('status')
                          ->orderBy('created_at', 'desc')
                          ->get();
        
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

        try {
            // 🔍 TAHAP 1: Tembak ke API Mahasiswa dulu gais (PUBLIC endpoint)
            $responseMhs = Http::timeout(3)->get("{$baseUrl}/public/mahasiswa/{$nim}");

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
            $responseDosen = Http::timeout(3)->get("{$baseUrl}/public/dosen/{$nim}");

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
        } catch (\Exception $e) {
            // Jika Pusat Data tidak tersedia, tetap izinkan (fail-open)
            \Illuminate\Support\Facades\Log::warning("Pusat Data tidak tersedia: " . $e->getMessage());
        }

        // FAIL-OPEN: Jika Pusat Data tidak tersedia atau NIM tidak ditemukan,
        // tetap izinkan dengan nama generik (sistem perpustakaan dapat berjalan mandiri)
        return response()->json([
            'success' => true,
            'nama' => "Pengguna {$nim} (Lokal)" // Tandai sebagai pengguna lokal
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

        // CEK PERMISSIONS MAHASISWA SEBELUM MINJEM BUKU
        $permissions = $this->checkStudentPermissions($request->nim_peminjam);
        
        if (!$permissions['can_borrow_book']) {
            $status = $permissions['status'] ?? null;
            $statusLabel = $permissions['status_label'] ?? 'tidak diketahui';
            
            // Buat pesan error yang lebih jelas berdasarkan status
            if ($status === 'cuti') {
                $errorMsg = "❌ Mahasiswa sedang berstatus CUTI, tidak dapat meminjam buku. Silakan hubungi admin untuk informasi lebih lanjut.";
            } elseif ($status === 'non_aktif' || $status === 'tidak_aktif') {
                $errorMsg = "❌ Mahasiswa sedang berstatus TIDAK AKTIF, tidak dapat meminjam buku. Silakan hubungi admin untuk informasi lebih lanjut.";
            } elseif ($status === 'lulus') {
                $errorMsg = "❌ Mahasiswa sudah LULUS, tidak dapat meminjam buku.";
            } elseif ($status === 'keluar') {
                $errorMsg = "❌ Mahasiswa sudah KELUAR dari kampus, tidak dapat meminjam buku.";
            } elseif ($status === null) {
                $errorMsg = "❌ Status mahasiswa tidak dapat diverifikasi. Sistem keamanan memblokir peminjaman untuk keamanan data. Pastikan server Pusat Data aktif atau hubungi admin.";
            } else {
                $errorMsg = "❌ Mahasiswa dengan status '{$statusLabel}' tidak dapat meminjam buku. Silakan hubungi admin.";
            }
            
            return redirect()->back()->with('error', $errorMsg);
        }

        // Menyimpan transaksi dengan menyelipkan status awal 'Dipinjam' gais
        Peminjaman::create([
            'nim_peminjam' => $request->nim_peminjam,
            'kode_buku' => $request->kode_buku,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'Dipinjam' // Kolom ini yang nanti akan diubah-ubah oleh admin gais
        ]);

        return redirect()->back()->with('success', 'Transaksi peminjaman berhasil dicatat!');
    }

    // Helper method untuk cek permissions mahasiswa
    private function checkStudentPermissions($nim)
    {
        $baseUrl = Setting::where('key', 'api_url')->first()->value 
            ?? env('PUSAT_DATA_API_URL', 'http://127.0.0.1:8000/api');
        
        $token = Setting::where('key', 'api_token')->first()->value 
            ?? env('PUSAT_DATA_API_TOKEN');

        try {
            // Gunakan PUBLIC endpoint agar tidak perlu token authentication
            $response = Http::timeout(5)->get("{$baseUrl}/public/mahasiswa/{$nim}/permissions");
            
            if ($response->successful()) {
                $data = $response->json('data');
                return [
                    'can_borrow_book' => $data['permissions']['can_borrow_book'] ?? false,
                    'can_attend' => $data['permissions']['can_attend'] ?? false,
                    'can_submit_thesis' => $data['permissions']['can_submit_thesis'] ?? false,
                    'status' => $data['status'] ?? null,
                    'status_label' => $data['status_label'] ?? 'tidak diketahui',
                ];
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Illuminate\Support\Facades\Log::error('Gagal cek permissions mahasiswa: ' . $e->getMessage());
        }

        // STRICT MODE: FAIL-CLOSED untuk keamanan
        // Jika tidak bisa verifikasi status mahasiswa, BLOKIR peminjaman
        return [
            'can_borrow_book' => false, // ❌ BLOKIR jika tidak bisa verifikasi
            'can_attend' => false,
            'can_submit_thesis' => false,
            'status' => null,
            'status_label' => 'tidak dapat diverifikasi - sistem verifikasi tidak tersedia',
        ];
    }
}
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController; // <-- WAJIB PANGGIL CONTROLLER LOGIN BARU GAIS

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 📚 JALUR UTAMA PERPUSTAKAAN (BAWAAN ASLI)
// ==========================================

// Jalur Utama: Membuka halaman utama perpustakaan
Route::get('/', [PeminjamanController::class, 'index'])->name('peminjaman.index');

// Jalur Simpan: Untuk memproses form saat tombol "Catat Transaksi" diklik
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

// Jalur API: Dipakai oleh Javascript (AJAX) di dalam Blade untuk mengecek NIM ke server Angga
Route::get('/check-nim/{nim}', [PeminjamanController::class, 'checkNim']);


// ==========================================
// 🔐 JALUR GERBANG MASUK ADMIN (AUTENTIKASI)
// ==========================================

// Tampilan Halaman Form Login Admin gais
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');

// Proses Validasi Form Login saat Tombol Masuk diklik gais
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

// Proses Keluar Sistem (Logout) Admin gais
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');


// ==========================================
// ⚙️ JALUR DASHBOARD ADMIN (DILINDUNGI SATPAM GAIS)
// ==========================================

Route::prefix('admin')->middleware(['admin.auth'])->group(function () {
    // Menampilkan Halaman Utama Dashboard Admin (CRUD Buku & Input API Token)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // CRUD Buku: Jalur untuk menyimpan buku baru ke database
    Route::post('/buku/tambah', [AdminController::class, 'storeBuku'])->name('admin.buku.store');
    
    // CRUD Buku: Jalur untuk menghapus koleksi buku berdasarkan ID
    Route::delete('/buku/hapus/{id}', [AdminController::class, 'destroyBuku'])->name('admin.buku.destroy');
    
    // Update Setting: Jalur dinamis untuk menyimpan URL API & Token kiriman Admin
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // ⚙️ JALUR BARU: Mengubah Status Peminjaman Buku (Dipinjam / Dikembalikan) gais
    Route::post('/peminjaman/status/{id}', [AdminController::class, 'updateStatus'])->name('admin.peminjaman.status');
});
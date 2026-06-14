<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminController;

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
// ⚙️ JALUR DASHBOARD ADMIN (FITUR BARU)
// ==========================================

Route::prefix('admin')->group(function () {
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
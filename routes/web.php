<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;

// Jalur Utama: Membuka halaman utama perpustakaan
Route::get('/', [PeminjamanController::class, 'index'])->name('peminjaman.index');

// Jalur Simpan: Untuk memproses form saat tombol "Catat Transaksi" diklik
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');

// Jalur API: Dipakai oleh Javascript (AJAX) di dalam Blade untuk mengecek NIM ke server Angga
Route::get('/check-nim/{nim}', [PeminjamanController::class, 'checkNim']);
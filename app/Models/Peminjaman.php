<?php

namespace App\App\Models; // Sesuaikan dengan folder bawaan proyekmu gais, biasanya namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // LANGKAH PENTING: Memaksa Laravel mencari tabel 'peminjamans', bukan 'peminjamen' (TIDAK BERUBAH)
    protected $table = 'peminjamans';

    // SEKARANG SUDAH AMAN GAIS, KOLOM STATUS SUDAH DIBERI IZIN AKSES!
    protected $fillable = [
        'nim_peminjam',
        'kode_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status', // <-- KITA TAMBAHKAN INI AGAR SAKLAR STATUS DI ADMIN BISA DIUBAH GAIS!
    ];
}
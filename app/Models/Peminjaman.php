<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // LANGKAH PENTING: Memaksa Laravel mencari tabel 'peminjamans', bukan 'peminjamen'
    protected $table = 'peminjamans';

    protected $fillable = [
        'nim_peminjam',
        'kode_buku',
        'tanggal_pinjam',
        'tanggal_kembali'
    ];
}
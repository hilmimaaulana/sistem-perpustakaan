<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    DB::table('bukus')->insert([
        // Akademik & Teknologi
        ['kode_buku' => 'BK001', 'judul_buku' => 'Pemrograman Web dengan Laravel', 'pengarang' => 'Andi Wijaya', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK002', 'judul_buku' => 'Belajar API dan Jaringan Cloud', 'pengarang' => 'Budi Santoso', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK003', 'judul_buku' => 'Dasar-Dasar Database MySQL', 'pengarang' => 'Citra Lestari', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK004', 'judul_buku' => 'Algoritma dan Struktur Data', 'pengarang' => 'Prof. Dr. Irwan', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK005', 'judul_buku' => 'Artificial Intelligence Dasar', 'pengarang' => 'Dian Kusuma', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK006', 'judul_buku' => 'Manajemen Proyek Perangkat Lunak', 'pengarang' => 'Eko Prasetyo', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK007', 'judul_buku' => 'Pengantar Ilmu Ekonomi', 'pengarang' => 'Siti Aminah', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK008', 'judul_buku' => 'Statistika untuk Penelitian', 'pengarang' => 'Bambang H.', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK009', 'judul_buku' => 'Keamanan Siber Modern', 'pengarang' => 'Kevin Mitnick', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK010', 'judul_buku' => 'Metodologi Penelitian Kualitatif', 'pengarang' => 'Ratna Sari', 'created_at' => now(), 'updated_at' => now()],
        
        // Fiksi & Novel (Tere Liye & Populer)
        ['kode_buku' => 'BK011', 'judul_buku' => 'Hujan', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK012', 'judul_buku' => 'Pulang', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK013', 'judul_buku' => 'Pergi', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK014', 'judul_buku' => 'Rindu', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK015', 'judul_buku' => 'Daun yang Jatuh Tak Pernah Membenci Angin', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK016', 'judul_buku' => 'Bumi', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK017', 'judul_buku' => 'Bulan', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK018', 'judul_buku' => 'Matahari', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK019', 'judul_buku' => 'Tentang Kamu', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK020', 'judul_buku' => 'Negeri di Ujung Tanduk', 'pengarang' => 'Tere Liye', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK021', 'judul_buku' => 'Laskar Pelangi', 'pengarang' => 'Andrea Hirata', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK022', 'judul_buku' => 'Cantik Itu Luka', 'pengarang' => 'Eka Kurniawan', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK023', 'judul_buku' => 'Filosofi Teras', 'pengarang' => 'Henry Manampiring', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK024', 'judul_buku' => 'Atomic Habits', 'pengarang' => 'James Clear', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK025', 'judul_buku' => 'Dunia Sophie', 'pengarang' => 'Jostein Gaarder', 'created_at' => now(), 'updated_at' => now()],
        
        // Umum & Pengembangan Diri
        ['kode_buku' => 'BK026', 'judul_buku' => 'Sejarah Dunia yang Disembunyikan', 'pengarang' => 'Jonathan Black', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK027', 'judul_buku' => 'Berani Tidak Disukai', 'pengarang' => 'Ichiro Kishimi', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK028', 'judul_buku' => 'Sapiens: Riwayat Singkat Umat Manusia', 'pengarang' => 'Yuval Noah Harari', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK029', 'judul_buku' => 'Psikologi Komunikasi', 'pengarang' => 'Jalaluddin Rakhmat', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK030', 'judul_buku' => 'Deep Work', 'pengarang' => 'Cal Newport', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK031', 'judul_buku' => 'Sebuah Seni untuk Bersikap Bodo Amat', 'pengarang' => 'Mark Manson', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK032', 'judul_buku' => 'Thinking, Fast and Slow', 'pengarang' => 'Daniel Kahneman', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK033', 'judul_buku' => 'Rich Dad Poor Dad', 'pengarang' => 'Robert Kiyosaki', 'created_at' => now(), 'updated_at' => now()],
        ['kode_buku' => 'BK034', 'judul_buku' => 'The Psychology of Money', 'pengarang' => 'Morgan Housel', 'created_at' => now(), 'updated_at' => now()],
    ]);
}
}
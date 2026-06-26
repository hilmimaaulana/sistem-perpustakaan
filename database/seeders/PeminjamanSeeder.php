<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada buku di database
        $bukuData = [
            ['kode_buku' => 'BK001', 'judul_buku' => 'Pemrograman Laravel untuk Pemula', 'pengarang' => 'John Doe', 'tahun_terbit' => 2023, 'stok' => 5],
            ['kode_buku' => 'BK002', 'judul_buku' => 'Database Design dan Optimization', 'pengarang' => 'Jane Smith', 'tahun_terbit' => 2022, 'stok' => 3],
            ['kode_buku' => 'BK003', 'judul_buku' => 'Web Security Fundamentals', 'pengarang' => 'Bob Wilson', 'tahun_terbit' => 2024, 'stok' => 4],
            ['kode_buku' => 'BK004', 'judul_buku' => 'Machine Learning with Python', 'pengarang' => 'Alice Johnson', 'tahun_terbit' => 2023, 'stok' => 2],
            ['kode_buku' => 'BK005', 'judul_buku' => 'Cloud Computing Architecture', 'pengarang' => 'Michael Brown', 'tahun_terbit' => 2024, 'stok' => 6],
        ];

        foreach ($bukuData as $data) {
            Buku::firstOrCreate(
                ['kode_buku' => $data['kode_buku']],
                $data
            );
        }

        // SKENARIO PEMINJAMAN UNTUK TESTING:
        // 2024001: Eligible (attendance >=75%, no unreturned books) ✅✅
        // 2024002: Eligible (attendance >=75%, no unreturned books) ✅✅
        // 2024003: Not Eligible (attendance >=75%, HAS unreturned books) ✅❌
        // 2024004: Not Eligible (attendance <75%, HAS unreturned books) ❌❌
        // 2024005: Not Eligible (attendance <75%, no unreturned books) ❌✅

        // Mahasiswa 2024001 - SEMUA BUKU SUDAH DIKEMBALIKAN
        Peminjaman::create([
            'nim_peminjam' => '2024001',
            'kode_buku' => 'BK001',
            'tanggal_pinjam' => now()->subDays(45),
            'tanggal_kembali' => now()->addDays(14 - 45), // Due 2 minggu dari pinjam
            'status' => 'dikembalikan',
        ]);

        Peminjaman::create([
            'nim_peminjam' => '2024001',
            'kode_buku' => 'BK002',
            'tanggal_pinjam' => now()->subDays(20),
            'tanggal_kembali' => now()->addDays(14 - 20),
            'status' => 'dikembalikan',
        ]);

        // Mahasiswa 2024002 - SEMUA BUKU SUDAH DIKEMBALIKAN
        Peminjaman::create([
            'nim_peminjam' => '2024002',
            'kode_buku' => 'BK003',
            'tanggal_pinjam' => now()->subDays(30),
            'tanggal_kembali' => now()->addDays(14 - 30),
            'status' => 'dikembalikan',
        ]);

        // Mahasiswa 2024003 - ADA BUKU YANG BELUM DIKEMBALIKAN
        Peminjaman::create([
            'nim_peminjam' => '2024003',
            'kode_buku' => 'BK001',
            'tanggal_pinjam' => now()->subDays(50),
            'tanggal_kembali' => now()->addDays(14 - 50),
            'status' => 'dikembalikan',
        ]);

        Peminjaman::create([
            'nim_peminjam' => '2024003',
            'kode_buku' => 'BK004',
            'tanggal_pinjam' => now()->subDays(10),
            'tanggal_kembali' => now()->addDays(4), // Masih 4 hari lagi
            'status' => 'dipinjam', // BELUM DIKEMBALIKAN!
        ]);

        Peminjaman::create([
            'nim_peminjam' => '2024003',
            'kode_buku' => 'BK005',
            'tanggal_pinjam' => now()->subDays(20),
            'tanggal_kembali' => now()->subDays(6), // Sudah terlambat 6 hari!
            'status' => 'dipinjam', // BELUM DIKEMBALIKAN & TERLAMBAT!
        ]);

        // Mahasiswa 2024004 - ADA BUKU YANG BELUM DIKEMBALIKAN
        Peminjaman::create([
            'nim_peminjam' => '2024004',
            'kode_buku' => 'BK002',
            'tanggal_pinjam' => now()->subDays(25),
            'tanggal_kembali' => now()->subDays(11), // Terlambat 11 hari!
            'status' => 'dipinjam', // BELUM DIKEMBALIKAN & SANGAT TERLAMBAT!
        ]);

        // Mahasiswa 2024005 - SEMUA BUKU SUDAH DIKEMBALIKAN
        Peminjaman::create([
            'nim_peminjam' => '2024005',
            'kode_buku' => 'BK003',
            'tanggal_pinjam' => now()->subDays(60),
            'tanggal_kembali' => now()->addDays(14 - 60),
            'status' => 'dikembalikan',
        ]);

        Peminjaman::create([
            'nim_peminjam' => '2024005',
            'kode_buku' => 'BK001',
            'tanggal_pinjam' => now()->subDays(35),
            'tanggal_kembali' => now()->addDays(14 - 35),
            'status' => 'dikembalikan',
        ]);

        $this->command->info('✅ Seeder Peminjaman Buku berhasil!');
        $this->command->info('📊 Data yang dibuat:');
        $this->command->info('   - 5 Buku');
        $this->command->info('   - 9 Record Peminjaman');
        $this->command->info('');
        $this->command->info('🎯 Skenario Testing:');
        $this->command->info('   ✅ 2024001: Attendance >=75%, No unreturned books → ELIGIBLE');
        $this->command->info('   ✅ 2024002: Attendance >=75%, No unreturned books → ELIGIBLE');
        $this->command->info('   ❌ 2024003: Attendance >=75%, HAS 2 unreturned books → NOT ELIGIBLE');
        $this->command->info('   ❌ 2024004: Attendance <75%, HAS 1 unreturned book → NOT ELIGIBLE');
        $this->command->info('   ❌ 2024005: Attendance <75%, No unreturned books → NOT ELIGIBLE');
    }
}

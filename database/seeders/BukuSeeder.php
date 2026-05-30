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
            [
                'kode_buku' => 'BK001',
                'judul_buku' => 'Pemrograman Web dengan Laravel',
                'pengarang' => 'Andi Wijaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_buku' => 'BK002',
                'judul_buku' => 'Belajar API dan Jaringan Cloud',
                'pengarang' => 'Budi Santoso',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_buku' => 'BK003',
                'judul_buku' => 'Dasar-Dasar Database MySQL',
                'pengarang' => 'Citra Lestari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
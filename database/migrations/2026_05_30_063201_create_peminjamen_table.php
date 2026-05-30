<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nim_peminjam');    // NIM Mahasiswa yang meminjam
            $table->string('kode_buku');       // Kode buku yang dipinjam
            $table->date('tanggal_pinjam');    // Tanggal buku dipinjam
            $table->date('tanggal_kembali')->nullable(); // Tanggal dikembalikan (boleh kosong dulu sebelum dikembalikan)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
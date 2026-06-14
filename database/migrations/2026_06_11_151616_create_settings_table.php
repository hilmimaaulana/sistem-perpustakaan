<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * * Fungsi untuk membuat tabel settings di database gais.
     */
    public function up(): void
     {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Tempat menyimpan nama pengaturannya (contoh: 'api_token', 'api_url')
            $table->text('value')->nullable(); // Tempat menyimpan nilai/isinya gais (bisa diisi atau dikosongkan)
            $table->timestamps(); // Otomatis membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * * Fungsi pembatalan jika kamu ingin rollback database gais.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
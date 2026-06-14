<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Menambahkan kolom status dengan nilai default 'Dipinjam' gais
            // Kolom diletakkan setelah kolom tanggal_pinjam biar rapi di database
            $table->string('status')->default('Dipinjam')->after('tanggal_pinjam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Fungsi rollback untuk menghapus kolom status jika migrasi dibatalkan gais
            $table->dropColumn('status');
        });
    }
};
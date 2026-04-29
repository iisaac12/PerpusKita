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
        Schema::create('peminjaman', function (Blueprint $table) {
            // PK: format PMJ-XXXXXXXXXXX (maks 15 karakter)
            $table->char('id_peminjaman', 15)->primary();
            $table->char('id_peminjam', 12);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            // lama_peminjaman dalam satuan hari, maks 3 hari (constraint divalidasi di application layer)
            $table->tinyInteger('lama_peminjaman')->unsigned();
            $table->enum('status', ['menunggu', 'aktif', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->timestamps();
            // Catatan: tidak pakai softDeletes karena histori peminjaman harus selalu ada
            // untuk keperluan audit dan laporan perpustakaan

            // Foreign Key
            $table->foreign('id_peminjam')
                  ->references('id_peminjam')
                  ->on('peminjam')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};

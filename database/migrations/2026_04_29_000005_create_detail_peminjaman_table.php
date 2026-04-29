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
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            // PK: format DET-XXXXXXXXXXX (maks 15 karakter)
            $table->char('id_detail', 15)->primary();
            $table->char('id_peminjaman', 15);
            $table->char('id_buku', 10);
            $table->tinyInteger('jumlah')->unsigned()->default(1);
            // Tidak ada timestamps/softDeletes karena detail adalah bagian dari transaksi peminjaman
            // dan hidupnya mengikuti parent (peminjaman)

            // Constraint: 1 buku tidak boleh duplikat dalam 1 transaksi
            $table->unique(['id_peminjaman', 'id_buku'], 'uq_detail');

            // Foreign Keys
            $table->foreign('id_peminjaman')
                  ->references('id_peminjaman')
                  ->on('peminjaman')
                  ->onUpdate('cascade')
                  ->onDelete('cascade'); // hapus detail jika peminjaman dihapus

            $table->foreign('id_buku')
                  ->references('id_buku')
                  ->on('buku')
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // jangan hapus buku jika masih ada di detail
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};

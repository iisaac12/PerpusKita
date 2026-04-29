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
        Schema::create('buku', function (Blueprint $table) {
            // PK: format BK-XXXXXX (maks 10 karakter)
            $table->char('id_buku', 10)->primary();
            $table->char('id_kategori', 10);
            $table->string('judul', 255);
            $table->string('pengarang', 150);
            $table->string('penerbit', 150);
            $table->integer('stok')->unsigned()->default(0);
            // Tambahan: path cover buku (nullable karena buku lama mungkin tidak ada cover)
            $table->string('cover_buku', 255)->nullable();
            $table->timestamps();
            $table->softDeletes(); // tambahan: deleted_at untuk soft delete

            // Foreign Key
            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategori')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};

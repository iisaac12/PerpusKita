<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku_kategori', function (Blueprint $table) {
            $table->id();
            $table->char('id_buku', 10);
            $table->char('id_kategori', 10);
            $table->timestamps();

            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // Migrasi data lama dari tabel buku ke tabel pivot
        $buku = DB::table('buku')->get();
        foreach ($buku as $b) {
            DB::table('buku_kategori')->insert([
                'id_buku' => $b->id_buku,
                'id_kategori' => $b->id_kategori,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_kategori');
    }
};

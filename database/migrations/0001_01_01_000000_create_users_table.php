<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catatan: Tabel 'users' bawaan Laravel TIDAK dibuat karena aplikasi ini
     * menggunakan tabel 'peminjam' sebagai tabel autentikasi utama.
     * Konfigurasi sudah disesuaikan di config/auth.php.
     * 
     * Tabel 'sessions' tetap dibuat untuk manajemen sesi Laravel.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 12)->nullable()->index(); // sesuai CHAR(12) id_peminjam
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel ini menggantikan tabel 'users' bawaan Laravel sebagai tabel autentikasi utama.
     * Kolom 'role' ditambahkan untuk membedakan akses antara admin dan peminjam biasa.
     */
    public function up(): void
    {
        Schema::create('peminjam', function (Blueprint $table) {
            // PK: format PM-XXXXXXXXX (maks 12 karakter)
            $table->char('id_peminjam', 12)->primary();
            $table->string('nama', 150);
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 150)->unique();
            $table->string('password', 255); // disimpan dalam bentuk hash (bcrypt)
            // Tambahan: role untuk sistem CRUD admin
            // 'admin'    = bisa melakukan CRUD semua data
            // 'peminjam' = hanya bisa meminjam dan melihat riwayat
            $table->enum('role', ['admin', 'peminjam'])->default('peminjam');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken(); // untuk fitur "ingat saya" saat login
            $table->timestamps();
            $table->softDeletes(); // tambahan: deleted_at untuk soft delete (non-aktifkan akun tanpa hapus data)
        });

        // Tabel untuk fitur reset password bawaan Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjam');
        Schema::dropIfExists('password_reset_tokens');
    }
};

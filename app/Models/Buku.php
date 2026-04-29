<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'buku';
    protected $primaryKey = 'id_buku';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = [
        'id_buku',
        'id_kategori',
        'judul',
        'pengarang',
        'penerbit',
        'stok',
        'cover_buku',
    ];

    /**
     * Auto-generate ID dengan format BK-XXXXXX (total 9 karakter, maks 10).
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_buku)) {
                $model->id_buku = self::generateId();
            }
        });
    }

    private static function generateId(): string
    {
        $prefix = 'BK-';
        $lastId = self::withTrashed()->orderByDesc('id_buku')->value('id_buku');

        $nextNumber = $lastId ? ((int) substr($lastId, strlen($prefix))) + 1 : 1;

        // Format: BK- + 7 digit angka = 10 karakter total
        return $prefix . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: satu buku dimiliki satu kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi: satu buku bisa ada di banyak detail peminjaman.
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_buku', 'id_buku');
    }

    /**
     * Helper: dapatkan URL cover buku (gambar default jika tidak ada).
     */
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_buku
            ? asset('storage/' . $this->cover_buku)
            : asset('images/cover-default.png');
    }
}

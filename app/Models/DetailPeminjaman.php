<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table      = 'detail_peminjaman';
    protected $primaryKey = 'id_detail';
    protected $keyType    = 'string';
    public    $incrementing = false;

    // Tidak ada timestamps di tabel ini
    public $timestamps = false;

    protected $fillable = [
        'id_detail',
        'id_peminjaman',
        'id_buku',
        'jumlah',
    ];

    /**
     * Auto-generate ID dengan format DET-XXXXXXXXXXX (total 15 karakter).
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_detail)) {
                $model->id_detail = self::generateId();
            }
        });
    }

    private static function generateId(): string
    {
        $prefix = 'DET-';
        $lastId = self::orderByDesc('id_detail')->value('id_detail');

        $nextNumber = $lastId ? ((int) substr($lastId, strlen($prefix))) + 1 : 1;

        // Format: DET- + 11 digit angka = 15 karakter total
        return $prefix . str_pad($nextNumber, 11, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: detail ini milik satu peminjaman.
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Relasi: detail ini merujuk satu buku.
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}

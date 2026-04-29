<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table      = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = [
        'id_peminjaman',
        'id_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'lama_peminjaman',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tanggal_kembali' => 'date',
    ];

    /**
     * Auto-generate ID dengan format PMJ-XXXXXXXXXXX (total 15 karakter).
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_peminjaman)) {
                $model->id_peminjaman = self::generateId();
            }
        });
    }

    private static function generateId(): string
    {
        $prefix = 'PMJ-';
        $lastId = self::orderByDesc('id_peminjaman')->value('id_peminjaman');

        $nextNumber = $lastId ? ((int) substr($lastId, strlen($prefix))) + 1 : 1;

        // Format: PMJ- + 11 digit angka = 15 karakter total
        return $prefix . str_pad($nextNumber, 11, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: satu peminjaman dimiliki satu peminjam.
     */
    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class, 'id_peminjam', 'id_peminjam');
    }

    /**
     * Relasi: satu peminjaman punya banyak detail.
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Relasi shortcut: ambil buku-buku yang dipinjam melalui detail.
     */
    public function buku()
    {
        return $this->belongsToMany(
            Buku::class,
            'detail_peminjaman',
            'id_peminjaman',
            'id_buku'
        )->withPivot('jumlah', 'id_detail');
    }
}

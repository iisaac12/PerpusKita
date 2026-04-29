<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Auto-generate ID dengan format KAT-XXXXX (total 9 karakter, maks 10).
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_kategori)) {
                $model->id_kategori = self::generateId();
            }
        });
    }

    private static function generateId(): string
    {
        $prefix = 'KAT-';
        $lastId = self::withTrashed()->orderByDesc('created_at')->value('id_kategori');

        $nextNumber = $lastId ? ((int) substr($lastId, strlen($prefix))) + 1 : 1;

        // Format: KAT- + 6 digit angka = 10 karakter total
        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi: satu kategori memiliki banyak buku.
     */
    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_kategori', 'id_kategori');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Peminjam extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Nama tabel di database.
     */
    protected $table = 'peminjam';

    /**
     * Primary key tabel (bukan auto-increment integer).
     */
    protected $primaryKey = 'id_peminjam';

    /**
     * Tipe primary key adalah string (bukan integer).
     */
    protected $keyType = 'string';

    /**
     * Nonaktifkan auto-increment karena kita generate ID secara manual.
     */
    public $incrementing = false;

    /**
     * Atribut yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'id_peminjam',
        'nama',
        'alamat',
        'no_telepon',
        'email',
        'password',
        'role',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (e.g. ke JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Auto-generate ID dengan format PM-XXXXXXXXX saat model dibuat.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_peminjam)) {
                $model->id_peminjam = self::generateId();
            }
        });
    }

    /**
     * Generate ID unik dengan format PM-XXXXXXXXX (total 12 karakter).
     * Contoh: PM-000000001
     */
    private static function generateId(): string
    {
        $prefix = 'PM-';
        $lastId = self::withTrashed()
            ->orderByDesc('created_at')
            ->value('id_peminjam');

        if ($lastId) {
            $lastNumber = (int) substr($lastId, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format: PM- + 9 digit angka = 12 karakter total
        return $prefix . str_pad($nextNumber, 9, '0', STR_PAD_LEFT);
    }

    /**
     * Helper: cek apakah peminjam adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relasi: satu peminjam bisa punya banyak peminjaman.
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_peminjam', 'id_peminjam');
    }
}

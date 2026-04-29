<?php

namespace Database\Seeders;

use App\Models\Peminjam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed 4 akun admin untuk anggota kelompok PerpusKita.
     */
    public function run(): void
    {
        $admins = [
            [
                'id_peminjam' => 'PM-000000001',
                'nama'        => 'AdminZeva',
                'email'       => 'adminzeva@perpuskita.id',
                'password'    => 'adminzeva123',
            ],
            [
                'id_peminjam' => 'PM-000000002',
                'nama'        => 'AdminRamzy',
                'email'       => 'adminramzy@perpuskita.id',
                'password'    => 'adminramzy123',
            ],
            [
                'id_peminjam' => 'PM-000000003',
                'nama'        => 'AdminEca',
                'email'       => 'admineca@perpuskita.id',
                'password'    => 'admineca123',
            ],
            [
                'id_peminjam' => 'PM-000000004',
                'nama'        => 'AdminAlvin',
                'email'       => 'adminalvin@perpuskita.id',
                'password'    => 'adminalvin123',
            ],
        ];

        foreach ($admins as $admin) {
            // firstOrCreate: skip jika sudah ada berdasarkan email
            Peminjam::firstOrCreate(
                ['email' => $admin['email']],
                [
                    'id_peminjam' => $admin['id_peminjam'],
                    'nama'        => $admin['nama'],
                    'password'    => Hash::make($admin['password']),
                    'role'        => 'admin',
                ]
            );
        }

        $this->command->info('✅ 4 akun admin berhasil dibuat!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NasabahSeeder extends Seeder
{
    public function run()
    {
        DB::table('nasabah')->insert([
            [
                'nama' => 'rayong',
                'nik' => '3201123456789011',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'telepon' => '081234567890',
                'status_blacklist' => false,
                'username' => 'budi_santoso',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Amanda',
                'nik' => '3201123456789011',
                'alamat' => 'Jl. Diponegoro No. 5, Bandung',
                'telepon' => '082345678901',
                'status_blacklist' => true,
                'username' => 'siti_aminah',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::table('cabang')->insert([
            [
                'id_cabang'  => 1,
                'nama_cabang'=> 'Cabang Jakarta',
                'alamat'     => 'Jl. Sudirman No. 10, Jakarta',
                'kontak'     => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_cabang'  => 2,
                'nama_cabang'=> 'Cabang Bandung',
                'alamat'     => 'Jl. Asia Afrika No. 15, Bandung',
                'kontak'     => '082345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('users')->insert([
            [
                'id'       => 1, // Pastikan id sesuai dengan DB
                'nama'      => 'Super Admin',
                'email'     => 'superadmin@example.com',
                'username'  => 'superadmin',
                'password'  => Hash::make('password123'),
                'role'      => 'Superadmin',
                'id_cabang' => null,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'id'       => 2,
                'nama'      => 'Admin Cabang 1',
                'email'     => 'admin1@example.com',
                'username'  => 'admin1',
                'password'  => Hash::make('password123'),
                'role'      => 'Admin',
                'id_cabang' => 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'id'       => 3,
                'nama'      => 'Admin Cabang 2',
                'email'     => 'admin2@example.com',
                'username'  => 'admin2',
                'password'  => Hash::make('password123'),
                'role'      => 'Admin',
                'id_cabang' => 2,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]
        ]);
    }
}

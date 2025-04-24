<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'Superadmin',
                'id_cabang' => 1,
            ],
            [
                'nama' => 'Admin Kalisuren',
                'email' => 'admin.Kalisuren@example.com',
                'username' => 'adminA',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'id_cabang' => 1,
            ],
            [
                'nama' => 'Admin parung',
                'email' => 'admin.parung@example.com',
                'username' => 'adminB',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'id_cabang' => 2,
            ],
            [
                'nama' => 'Admin lebakwangi',
                'email' => 'admin.lebakwangi@example.com',
                'username' => 'adminC',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'id_cabang' => 3,
            ],
            [
                'nama' => 'Nasabah Satu',
                'email' => 'nasabah1@example.com',
                'username' => 'nasabah1',
                'password' => Hash::make('password'),
                'role' => 'Nasabah',
                'id_cabang' => 1,
            ],
            [
                'nama' => 'Nasabah Dua',
                'email' => 'nasabah2@example.com',
                'username' => 'nasabah2',
                'password' => Hash::make('password'),
                'role' => 'Nasabah',
                'id_cabang' => 2,
            ],
            [
                'nama' => 'Nasabah Tiga',
                'email' => 'nasabah3@example.com',
                'username' => 'nasabah3',
                'password' => Hash::make('password'),
                'role' => 'Nasabah',
                'id_cabang' => 3,
            ]
        ]);
    }
}

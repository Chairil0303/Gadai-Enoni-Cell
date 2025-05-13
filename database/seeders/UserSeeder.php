<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();

        // DB::table('users')->insert([
        //     [
        //         'nama' => 'Super Admin',
        //         'email' => 'superadmin@example.com',
        //         'username' => 'superadmin',
        //         'password' => Hash::make('password'),
        //         'role' => 'Superadmin',
        //         'id_cabang' => 1,
        //     ],
        //     [
        //         'nama' => 'Admin Kalisuren',
        //         'email' => 'admin.Kalisuren@example.com',
        //         'username' => 'adminA',
        //         'password' => Hash::make('password'),
        //         'role' => 'Admin',
        //         'id_cabang' => 1,
        //     ],
        //     [
        //         'nama' => 'Staf Kalisuren',
        //         'email' => 'Staf.kalisuren@example.com',
        //         'username' => 'StafA',
        //         'password' => Hash::make('password'),
        //         'role' => 'Staf',
        //         'id_cabang' => 1,
        //     ],
        //     [
        //         'nama' => 'Admin parung',
        //         'email' => 'admin.parung@example.com',
        //         'username' => 'adminB',
        //         'password' => Hash::make('password'),
        //         'role' => 'Admin',
        //         'id_cabang' => 2,
        //     ],
        //     [
        //         'nama' => 'Admin lebakwangi',
        //         'email' => 'admin.lebakwangi@example.com',
        //         'username' => 'adminC',
        //         'password' => Hash::make('password'),
        //         'role' => 'Admin',
        //         'id_cabang' => 3,
        //     ],
        //     [
        //         'nama' => 'Staf lebakwangi',
        //         'email' => 'Staf.lebakwangi@example.com',
        //         'username' => 'StafB',
        //         'password' => Hash::make('password'),
        //         'role' => 'Staf',
        //         'id_cabang' => 2,
        //     ],
        //     [
        //         'nama' => 'Nasabah Satu',
        //         'email' => 'nasabah1@example.com',
        //         'username' => 'nasabah1',
        //         'password' => Hash::make('password'),
        //         'role' => 'Nasabah',
        //         'id_cabang' => 1,
        //     ],
        //     [
        //         'nama' => 'Nasabah Dua',
        //         'email' => 'nasabah2@example.com',
        //         'username' => 'nasabah2',
        //         'password' => Hash::make('password'),
        //         'role' => 'Nasabah',
        //         'id_cabang' => 2,
        //     ],
        //     [
        //         'nama' => 'Nasabah Tiga',
        //         'email' => 'nasabah3@example.com',
        //         'username' => 'nasabah3',
        //         'password' => Hash::make('password'),
        //         'role' => 'Nasabah',
        //         'id_cabang' => 3,
        //     ]
        // ]);
        DB::table('users')->insert([
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'Superadmin',
                'id_cabang' => null,
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
                'nama' => 'Staf Kalisuren 1',
                'email' => 'staf1.kalisuren@example.com',
                'username' => 'staf_kalisuren_1',
                'password' => Hash::make('password'),
                'role' => 'Staf',
                'id_cabang' => 1,
            ],
            [
                'nama' => 'Admin Parung',
                'email' => 'admin.parung@example.com',
                'username' => 'adminB',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'id_cabang' => 2,
            ],
            [
                'nama' => 'Admin Lebak Wangi',
                'email' => 'admin.lebakwangi@example.com',
                'username' => 'adminC',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'id_cabang' => 3,
            ],
            [
                'nama' => 'Staf Parung 1',
                'email' => 'staf1.parung@example.com',
                'username' => 'staf_parung_1',
                'password' => Hash::make('password'),
                'role' => 'Staf',
                'id_cabang' => 2,
            ],
            [
                'nama' => 'Staf Lebak Wangi 1',
                'email' => 'staf1.lebakwangi@example.com',
                'username' => 'staf_lebakwangi_1',
                'password' => Hash::make('password'),
                'role' => 'Staf',
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
            ],
            [
                'nama' => 'Nasabah Empat',
                'email' => 'nasabah4@example.com',
                'username' => 'nasabah4',
                'password' => Hash::make('password'),
                'role' => 'Nasabah',
                'id_cabang' => 1,
                ],
            ],
        ]);

        // Tambahkan 2 staf tambahan per cabang
        $cabangs = DB::table('cabang')->get();
        foreach ($cabangs as $cabang) {
            for ($i = 2; $i <= 3; $i++) {
                DB::table('users')->insert([
                    'nama' => "Staf {$cabang->nama_cabang} {$i}",
                    'email' => "staf{$i}." . Str::slug($cabang->nama_cabang, '_') . "@example.com",
                    'username' => "staf_" . Str::slug($cabang->nama_cabang, '_') . "_{$i}",
                    'password' => Hash::make('password'),
                    'role' => 'Staf',
                    'id_cabang' => $cabang->id_cabang,
                ]);
            }
        }



    }
}

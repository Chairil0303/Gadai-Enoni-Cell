<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;

// class UserSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         DB::table('users')->insert([
//             [
//                 'id_users'  => 1,
//                 'nama'      => 'Super Admin',
//                 'email'     => 'superadmin@example.com',
//                 'username'  => 'superadmin',
//                 'password'  => Hash::make('password123'),
//                 'role'      => 'Superadmin',
//                 'id_cabang' => null, // Superadmin tidak terkait dengan cabang
//                 'created_at'=> now(),
//                 'updated_at'=> now(),
//             ],
//             [
//                 'id_users'  => 2,
//                 'nama'      => 'Admin Cabang 1',
//                 'email'     => 'admin1@example.com',
//                 'username'  => 'admin1',
//                 'password'  => Hash::make('password123'),
//                 'role'      => 'Admin',
//                 'id_cabang' => 1, // Admin ini di cabang 1
//                 'created_at'=> now(),
//                 'updated_at'=> now(),
//             ],
//             [
//                 'id_users'  => 3,
//                 'nama'      => 'Admin Cabang 2',
//                 'email'     => 'admin2@example.com',
//                 'username'  => 'admin2',
//                 'password'  => Hash::make('password123'),
//                 'role'      => 'Admin',
//                 'id_cabang' => 2, // Admin ini di cabang 2
//                 'created_at'=> now(),
//                 'updated_at'=> now(),
//             ]
//         ]);
//     }
// }

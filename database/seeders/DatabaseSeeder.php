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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('cabang')->truncate();
        DB::table('kategori_barang')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

         // Seed cabang
        DB::table('cabang')->insert([
            ['nama_cabang' => 'Cabang Kalisuren', 'alamat' => 'Jl. Kalisuren No.1, Bogor', 'kontak' => '081234567890'],
            ['nama_cabang' => 'Cabang Parung', 'alamat' => 'Jl. Asia Afrika No.2, Parung', 'kontak' => '081234567891'],
            ['nama_cabang' => 'Cabang Lebak Wangi', 'alamat' => 'Jl. Raya Darmo No.3, Lebak Wangi', 'kontak' => '081234567892'],
        ]);

        // Seed users
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
                'id_cabang' => null,
            ],
            [
                'nama' => 'Nasabah Dua',
                'email' => 'nasabah2@example.com',
                'username' => 'nasabah2',
                'password' => Hash::make('password'),
                'role' => 'Nasabah',
                'id_cabang' => null,
            ],
        ]);

        // Seed kategori_barang
        DB::table('kategori_barang')->insert([
            ['nama_kategori' => 'Laptop', 'deskripsi' => 'Kategori untuk barang berupa laptop'],
            ['nama_kategori' => 'HP', 'deskripsi' => 'Kategori untuk barang berupa handphone'],
            ['nama_kategori' => 'TV', 'deskripsi' => 'Kategori untuk barang berupa televisi'],
        ]);
    }
}
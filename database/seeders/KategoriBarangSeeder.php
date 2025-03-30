<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriBarangSeeder extends Seeder
{
    public function run()
    {
        DB::table('kategori_barang')->truncate();

        DB::table('kategori_barang')->insert([
            ['nama_kategori' => 'Laptop', 'deskripsi' => 'Kategori untuk barang berupa laptop'],
            ['nama_kategori' => 'HP', 'deskripsi' => 'Kategori untuk barang berupa handphone'],
            ['nama_kategori' => 'TV', 'deskripsi' => 'Kategori untuk barang berupa televisi'],
        ]);
    }
}

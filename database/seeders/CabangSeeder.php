<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    public function run()
    {
        DB::table('cabang')->truncate();

        DB::table('cabang')->insert([
            ['nama_cabang' => 'Cabang Kalisuren', 'alamat' => 'Jl. Kalisuren No.1, Bogor', 'kontak' => '081234567890', 'google_maps_link' => 'https://maps.app.goo.gl/raomLc4mYXNGZPjLA'],
            ['nama_cabang' => 'Cabang Parung', 'alamat' => 'Jl. Asia Afrika No.2, Parung', 'kontak' => '081234567891','google_maps_link' => 'https://maps.app.goo.gl/raomLc4mYXNGZPjLA'],
            ['nama_cabang' => 'Cabang Lebak Wangi', 'alamat' => 'Jl. Raya Darmo No.3, Lebak Wangi', 'kontak' => '081234567892','google_maps_link' => 'https://maps.app.goo.gl/raomLc4mYXNGZPjLA'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SaldoCabang;

class SaldoCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        SaldoCabang::create([
            'id_cabang' => 1,
            'saldo_awal' => 40000000,
            'saldo_saat_ini' => 40000000,
        ]);
    }
}

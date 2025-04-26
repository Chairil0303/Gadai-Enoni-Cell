<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BungaTenor;

class BungaTenorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BungaTenor::insert([
            ['tenor' => 7, 'bunga_percent' => 5],
            ['tenor' => 14, 'bunga_percent' => 10],
            ['tenor' => 30, 'bunga_percent' => 15],
        ]);
    }
}

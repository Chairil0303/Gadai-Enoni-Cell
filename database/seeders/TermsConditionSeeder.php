<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TermsCondition;

class TermsConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TermsCondition::create([
            'content' => '<ul><li>Wajib bawa KTP dan Surat Gadai.</li></ul><ol><li>Setuju denda 1% per hari keterlambatan.</li></ol>'
        ]);
    }
}

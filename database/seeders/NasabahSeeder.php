<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NasabahSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nasabah')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil ID dari nasabah yang ada di tabel 'users'
        $nasabah1 = DB::table('users')->where('username', 'nasabah1')->value('id_users');
        $nasabah2 = DB::table('users')->where('username', 'nasabah2')->value('id_users');

        // Cek apakah user ditemukan, jika tidak hentikan seeder ini
        if (!$nasabah1 || !$nasabah2) {
            dd("User Nasabah1 atau Nasabah2 tidak ditemukan. Pastikan seeder UsersSeeder sudah dijalankan.");
        }

        DB::table('nasabah')->insert([
            [
                'id_user' => $nasabah1,
                'nama' => 'Nasabah Satu',
                'nik' => '3201010101010001',
                'alamat' => 'Jl. Melati No.1, Bogor',
                'telepon' => '081234567890',
                'status_blacklist' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => $nasabah2,
                'nama' => 'Nasabah Dua',
                'nik' => '3201010101010002',
                'alamat' => 'Jl. Mawar No.2, Depok',
                'telepon' => '081234567891',
                'status_blacklist' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

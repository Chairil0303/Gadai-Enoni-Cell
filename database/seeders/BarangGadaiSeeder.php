<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangGadaiSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan pemeriksaan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Bersihkan data sebelumnya
        DB::table('barang_gadai')->delete();

        // Ambil ID dari Admin A (adminA) dan Admin B (adminB) dari tabel users
        $adminA = DB::table('users')->where('username', 'adminA')->value('id_users');
        $adminB = DB::table('users')->where('username', 'adminB')->value('id_users');

        // Ambil ID dari Nasabah Satu dan Nasabah Dua dari tabel nasabah
        $nasabah1 = DB::table('nasabah')->where('nama', 'Nasabah Satu')->value('id_nasabah');
        $nasabah2 = DB::table('nasabah')->where('nama', 'Nasabah Dua')->value('id_nasabah');

        // Data barang gadai dengan perhitungan bunga otomatis berdasarkan tenor
        $barangGadaiData = [
            [
                'no_bon' => 'BON001',
                'id_nasabah' => $nasabah1,
                'id_user' => $adminA,
                'nama_barang' => 'Laptop Asus ROG',
                'deskripsi' => 'high-end.',
                'imei' => '123456789012345',
                'tenor' => 30,
                'tempo' => Carbon::now()->addDays(30),
                'telat' => 0,
                'harga_gadai' => 1500000.00,
                'status' => 'Tergadai',
                'id_kategori' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_bon' => 'BON002',
                'id_nasabah' => $nasabah2,
                'id_user' => $adminA,
                'nama_barang' => 'Samsung Galaxy J2',
                'deskripsi' => 'RAM 8GB, dan memori internal 128GB.',
                'imei' => '987654321098765',
                'tenor' => 14,
                'tempo' => Carbon::now()->addDays(14),
                'telat' => 0,
                'harga_gadai' => 800000.00,
                'status' => 'Tergadai',
                'id_kategori' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_bon' => 'BON003',
                'id_nasabah' => $nasabah2,
                'id_user' => $adminB,
                'nama_barang' => 'Samsung Galaxy J3',
                'deskripsi' => 'RAM 8GB, dan memori internal 128GB.',
                'imei' => '987654321098765',
                'tenor' => 14,
                'tempo' => Carbon::now()->addDays(14),
                'telat' => 0,
                'harga_gadai' => 800000.00,
                'status' => 'Tergadai',
                'id_kategori' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Simpan persentase bunga berdasarkan tenor
        foreach ($barangGadaiData as &$data) {
            switch ($data['tenor']) {
                case 7:
                    $data['bunga'] = 5;
                    break;
                case 14:
                    $data['bunga'] = 10;
                    break;
                case 30:
                    $data['bunga'] = 15;
                    break;
                default:
                    $data['bunga'] = 0;
            }
        }

        // Insert data ke tabel barang_gadai
        DB::table('barang_gadai')->insert($barangGadaiData);

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

// seeder barang gadai
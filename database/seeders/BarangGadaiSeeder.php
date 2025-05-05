<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangGadaiSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus data sebelumnya
        DB::table('barang_gadai')->truncate();

        // Ambil ID nasabah
        $nasabah1 = DB::table('nasabah')->where('nama', 'Nasabah Satu')->value('id_nasabah');
        $nasabah2 = DB::table('nasabah')->where('nama', 'Nasabah Dua')->value('id_nasabah');
        $nasabah3 = DB::table('nasabah')->where('nama', 'Nasabah Tiga')->value('id_nasabah'); // Kapitalisasi konsisten
        $nasabah4 = DB::table('nasabah')->where('nama', 'Nasabah Empat')->value('id_nasabah'); // Kapitalisasi konsisten


        // Ambil ID bunga_tenor berdasarkan tenor
        $tenor7 = DB::table('bunga_tenor')->where('tenor', 7)->value('id');
        $tenor14 = DB::table('bunga_tenor')->where('tenor', 14)->value('id');
        $tenor30 = DB::table('bunga_tenor')->where('tenor', 30)->value('id');

        // Tanggal dan waktu
        $now = Carbon::now();
        $tempoTelat = $now->copy()->subDays(7);
        $hariTelat = $now->diffInDays($tempoTelat);

        // Data barang gadai
        $barangGadaiData = [
            [
                'no_bon' => 'BON001',
                'id_nasabah' => $nasabah1,
                'id_cabang' => 1,
                'id_kategori' => 1,
                'id_bunga_tenor' => $tenor30,
                'nama_barang' => 'Laptop Asus ROG',
                'deskripsi' => 'high-end.',
                'imei' => '123456789012345',
                'tempo' => $now->copy()->addDays(30),
                'telat' => 0,
                'harga_gadai' => 1500000.00,
                'status' => 'Tergadai',
                'bunga' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'no_bon' => 'BON002',
                'id_nasabah' => $nasabah2,
                'id_cabang' => 2,
                'id_kategori' => 2,
                'id_bunga_tenor' => $tenor14,
                'nama_barang' => 'Samsung Galaxy J2',
                'deskripsi' => 'RAM 8GB, dan memori internal 128GB.',
                'imei' => '987654321098765',
                'tempo' => $now->copy()->addDays(14),
                'telat' => 0,
                'harga_gadai' => 800000.00,
                'status' => 'Tergadai',
                'bunga' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'no_bon' => 'BON003',
                'id_nasabah' => $nasabah3,
                'id_cabang' => 2,
                'id_kategori' => 2,
                'id_bunga_tenor' => $tenor14,
                'nama_barang' => 'Samsung Galaxy J3',
                'deskripsi' => 'RAM 8GB, dan memori internal 128GB.',
                'imei' => '987654321098765',
                'tempo' => $now->copy()->addDays(14),
                'telat' => 0,
                'harga_gadai' => 800000.00,
                'status' => 'Tergadai',
                'bunga' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'no_bon' => 'BON004',
                'id_nasabah' => $nasabah4,
                'id_cabang' => 1,
                'id_kategori' => 2,
                'id_bunga_tenor' => $tenor14,
                'nama_barang' => 'Realme 5 Pro',
                'deskripsi' => 'Smartphone yang tempo-nya sudah telat.',
                'imei' => '321654987654321',
                'tempo' => $tempoTelat,
                'telat' => $hariTelat,
                'harga_gadai' => 900000.00,
                'status' => 'Tergadai',
                'bunga' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Masukkan ke database
        DB::table('barang_gadai')->insert($barangGadaiData);

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

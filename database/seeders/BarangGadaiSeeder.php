<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BarangGadaiSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('barang_gadai')->truncate();
        DB::table('nasabah')->truncate();
        DB::table('users')->where('role', 'Nasabah')->delete();

        $tenor7  = DB::table('bunga_tenor')->where('tenor', 7)->value('id');
        $tenor14 = DB::table('bunga_tenor')->where('tenor', 14)->value('id');
        $tenor30 = DB::table('bunga_tenor')->where('tenor', 30)->value('id');
        $tenors = [
            ['id' => $tenor7, 'hari' => 7, 'bunga' => 5],
            ['id' => $tenor14, 'hari' => 14, 'bunga' => 10],
            ['id' => $tenor30, 'hari' => 30, 'bunga' => 15],
        ];

        $startDate = Carbon::create(2025, 6, 14);
        $endDate = Carbon::now();
        $bonCounter = 1;
        $nasabahCounter = 1;

        $now = Carbon::now();
        $users = [];
        $nasabahs = [];
        $barangGadaiData = [];
        $userId = DB::table('users')->max('id_users') + 1;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $jumlahTransaksi = rand(3, 7);

            for ($i = 0; $i < $jumlahTransaksi; $i++) {
                // 1. Users & Nasabah
                $users[] = [
                    'id_users' => $userId,
                    'nama' => "Nasabah Dummy $nasabahCounter",
                    'email' => "nasabah$nasabahCounter@example.com",
                    'username' => "nasabah$nasabahCounter",
                    'password' => Hash::make('password'),
                    'role' => 'Nasabah',
                    'id_cabang' => rand(1, 3),
                    'created_at' => $date->copy(),
                    'updated_at' => $date->copy(),
                ];
                $nasabahs[] = [
                    'id_user' => $userId,
                    'nama' => "Nasabah Dummy $nasabahCounter",
                    'nik' => str_pad($nasabahCounter, 16, '0', STR_PAD_LEFT),
                    'alamat' => "Alamat Dummy $nasabahCounter",
                    'telepon' => '08' . rand(1000000000, 9999999999),
                    'status_blacklist' => false,
                    'created_at' => $date->copy(),
                    'updated_at' => $date->copy(),
                ];

                // 2. Barang Gadai
                $tenor = $tenors[array_rand($tenors)];
                $tempo = $date->copy()->addDays($tenor['hari']);
                $barangGadaiData[] = [
                    'no_bon' => 'BON' . str_pad($bonCounter++, 5, '0', STR_PAD_LEFT),
                    'id_nasabah' => $nasabahCounter, // nanti akan sesuai urutan insert
                    'id_cabang' => rand(1, 3),
                    'id_kategori' => rand(1, 5),
                    'id_bunga_tenor' => $tenor['id'],
                    'nama_barang' => 'Barang ' . $bonCounter,
                    'deskripsi' => 'Deskripsi barang ' . $bonCounter,
                    'imei' => rand(100000000000000, 999999999999999),
                    'bunga' => $tenor['bunga'],
                    'harga_gadai' => rand(1000000, 5000000),
                    'status' => 'Tergadai',
                    'created_at' => $date->copy(),
                    'updated_at' => $date->copy(),
                    'tempo' => $tempo,
                    'telat' => $now->gt($tempo) ? $now->diffInDays($tempo) : 0,
                ];

                $userId++;
                $nasabahCounter++;
            }
        }

        // Batch insert
        foreach (array_chunk($users, 500) as $batch) {
            DB::table('users')->insert($batch);
        }

        foreach (array_chunk($nasabahs, 500) as $batch) {
            DB::table('nasabah')->insert($batch);
        }

        foreach (array_chunk($barangGadaiData, 500) as $batch) {
            DB::table('barang_gadai')->insert($batch);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

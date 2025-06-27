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
        // Disable foreign key checks for truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all relevant tables
        DB::table('transaksi')->truncate();
        DB::table('barang_gadai')->truncate();
        DB::table('nasabah')->truncate();
        DB::table('log_aktivitas')->truncate();
        DB::table('users')->where('role', 'Nasabah')->delete(); // Only delete Nasabah users

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Fetch a random tenor ID once
        $tenorOptions = [7, 14, 30];
        $selectedTenor = $tenorOptions[array_rand($tenorOptions)];
        $tenorId = DB::table('bunga_tenor')->where('tenor', $selectedTenor)->value('id');

        // Define a base branch ID
        $idCabang = 1;

        // Data for all transactions
        $transactionsData = [
            // June 14, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 14),
                'userId' => 1001,
                'nasabahId' => 1,
                'nama' => "Dewi Kartika",
                'nik' => "3201121401980001",
                'telp' => "0812345678912",
                'email' => "dewi.kartika@example.com",
                'noBon' => "KLS0001",
                'kategori' => 1, // Laptop
                'namaBarang' => "Laptop ASUS",
                'deskripsiBarang' => "Kondisi sangat baik",
                'imei' => "352099000001111",
                'hargaGadai' => 1750000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 14),
                'userId' => 1002,
                'nasabahId' => 2,
                'nama' => "Ahmad Rizki",
                'nik' => "3201132305900002",
                'telp' => "0823456789123",
                'email' => "ahmad.rizki@example.com",
                'noBon' => "KLS0002",
                'kategori' => 2, // Handphone
                'namaBarang' => "HP Samsung",
                'deskripsiBarang' => "Kondisi normal, lecet ringan",
                'imei' => "352099000002222",
                'hargaGadai' => 1200000,
            ],
            // June 15, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 15),
                'userId' => 1003,
                'nasabahId' => 3,
                'nama' => "Siti Nurhaliza",
                'nik' => "3201141202980003",
                'telp' => "0838123456789",
                'email' => "siti.nurhaliza@example.com",
                'noBon' => "KLS0003",
                'kategori' => 2, // Handphone
                'namaBarang' => "HP Oppo",
                'deskripsiBarang' => "Layar mulus, baterai bagus",
                'imei' => "352099000003333",
                'hargaGadai' => 1000000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 15),
                'userId' => 1004,
                'nasabahId' => 4,
                'nama' => "Rizky Hidayat",
                'nik' => "3201150101990004",
                'telp' => "0815987654321",
                'email' => "rizky.hidayat@example.com",
                'noBon' => "KLS0004",
                'kategori' => 3, // TV
                'namaBarang' => "TV Polytron",
                'deskripsiBarang' => "Masih normal, layar bersih",
                'imei' => "352099000004444",
                'hargaGadai' => 800000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 15),
                'userId' => 1005,
                'nasabahId' => 5,
                'nama' => "Yuli Andriani",
                'nik' => "3201162705950005",
                'telp' => "0878123456700",
                'email' => "yuli.andriani@example.com",
                'noBon' => "KLS0005",
                'kategori' => 1, // Laptop
                'namaBarang' => "Laptop Lenovo",
                'deskripsiBarang' => "Baret tipis, masih cepat",
                'imei' => "352099000005555",
                'hargaGadai' => 1500000,
            ],
            // June 16, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 16),
                'userId' => 1006,
                'nasabahId' => 6,
                'nama' => "Dedi Supriyadi",
                'nik' => "3201171010920006",
                'telp' => "0821123456789",
                'email' => "dedi.supriyadi@example.com",
                'noBon' => "KLS0006",
                'kategori' => 3, // TV
                'namaBarang' => "TV Samsung",
                'deskripsiBarang' => "Layar bersih, remote tidak ada",
                'imei' => "352099000006666",
                'hargaGadai' => 900000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 16),
                'userId' => 1007,
                'nasabahId' => 7,
                'nama' => "Ani Marlina",
                'nik' => "3201182205980007",
                'telp' => "0896123456798",
                'email' => "ani.marlina@example.com",
                'noBon' => "KLS0007",
                'kategori' => 2, // Handphone
                'namaBarang' => "HP Xiaomi",
                'deskripsiBarang' => "Kondisi normal, casing lecet",
                'imei' => "352099000007777",
                'hargaGadai' => 850000,
            ],
            // June 17, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 17),
                'userId' => 1008,
                'nasabahId' => 8,
                'nama' => "Rina Apriani",
                'nik' => "3201192302930008",
                'telp' => "0813123456789",
                'email' => "rina.apriani@example.com",
                'noBon' => "KLS0008",
                'kategori' => 2, // Handphone
                'namaBarang' => "HP Nokia",
                'deskripsiBarang' => "Body kusam, layar normal",
                'imei' => "352099000008888",
                'hargaGadai' => 500000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 17),
                'userId' => 1009,
                'nasabahId' => 9,
                'nama' => "Asep Mulyana",
                'nik' => "3201202509870009",
                'telp' => "0899988776655",
                'email' => "asep.mulyana@example.com",
                'noBon' => "KLS0009",
                'kategori' => 2, // Handphone
                'namaBarang' => "HP Samsung J2",
                'deskripsiBarang' => "Layar retak sedikit, masih nyala",
                'imei' => "352099000009999",
                'hargaGadai' => 700000,
            ],
            // June 18, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 18),
                'userId' => 1010,
                'nasabahId' => 10,
                'nama' => "Yuliani Sari",
                'nik' => "3201213101980010",
                'telp' => "0812345678910",
                'email' => "yuliani.sari@example.com",
                'noBon' => "KLS0010",
                'kategori' => 1, // Laptop
                'namaBarang' => "Laptop Asus",
                'deskripsiBarang' => "Bodi mulus, baterai soak",
                'imei' => "352099000001010",
                'hargaGadai' => 1200000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 18),
                'userId' => 1011,
                'nasabahId' => 11,
                'nama' => "Dedi Kurniawan",
                'nik' => "3201221205800011",
                'telp' => "0898765432101",
                'email' => "dedi.kurniawan@example.com",
                'noBon' => "KLS0011",
                'kategori' => 3, // TV
                'namaBarang' => "TV Sharp",
                'deskripsiBarang' => "Gores di sisi, gambar normal",
                'imei' => "352099000001011",
                'hargaGadai' => 1000000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 19),
                'userId' => 1012,
                'nasabahId' => 12,
                'nama' => "Rina Marlina",
                'nik' => "3201230504930012",
                'telp' => "0899123456712",
                'email' => "rina.marlina@example.com",
                'noBon' => "KLS0012",
                'kategori' => 2,
                'namaBarang' => "Samsung note 7",
                'deskripsiBarang' => "Layar gores, fungsi normal",
                'imei' => "352099000001012",
                'hargaGadai' => 400000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 19),
                'userId' => 1013,
                'nasabahId' => 13,
                'nama' => "Fajar Pratama",
                'nik' => "3201241207810013",
                'telp' => "0899554433213",
                'email' => "fajar.pratama@example.com",
                'noBon' => "KLS0013",
                'kategori' => 2,
                'namaBarang' => "Xiaomi note 9",
                'deskripsiBarang' => "Baterai cepat habis",
                'imei' => "352099000001013",
                'hargaGadai' => 500000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 19),
                'userId' => 1014,
                'nasabahId' => 14,
                'nama' => "Mulyadi Rahman",
                'nik' => "3201252506780014",
                'telp' => "0899111223314",
                'email' => "mulyadi.rahman@example.com",
                'noBon' => "KLS0014",
                'kategori' => 2,
                'namaBarang' => "Samsung Galaxy S8",
                'deskripsiBarang' => "Casing retak, tombol keras",
                'imei' => "352099000001014",
                'hargaGadai' => 450000,
            ],
            // June 20, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 20),
                'userId' => 1015,
                'nasabahId' => 15,
                'nama' => "Tia Nurhaliza",
                'nik' => "3201261501990015",
                'telp' => "0888123412315",
                'email' => "tia.nurhaliza@example.com",
                'noBon' => "KLS0015",
                'kategori' => 2,
                'namaBarang' => "Oppo A12",
                'deskripsiBarang' => "Masih mulus, sedikit lecet",
                'imei' => "352099000001015",
                'hargaGadai' => 700000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 20),
                'userId' => 1016,
                'nasabahId' => 16,
                'nama' => "Ilham Setiawan",
                'nik' => "3201271203940016",
                'telp' => "0898445567816",
                'email' => "ilham.setiawan@example.com",
                'noBon' => "KLS0016",
                'kategori' => 2,
                'namaBarang' => "Samsung A10",
                'deskripsiBarang' => "Layar agak buram",
                'imei' => "352099000001016",
                'hargaGadai' => 600000,
            ],
            // June 21, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 21),
                'userId' => 1017,
                'nasabahId' => 17,
                'nama' => "Yuliana Sari",
                'nik' => "3201281101830017",
                'telp' => "0888432156717",
                'email' => "yuliana.sari@example.com",
                'noBon' => "KLS0017",
                'kategori' => 2, // HP
                'namaBarang' => "Xiaomi Redmi 9",
                'deskripsiBarang' => "Bodi ada lecet, fungsi normal",
                'imei' => "352099000001017",
                'hargaGadai' => 550000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 21),
                'userId' => 1018,
                'nasabahId' => 18,
                'nama' => "Rahmat Widodo",
                'nik' => "3201290302800018",
                'telp' => "0898765432118",
                'email' => "rahmat.widodo@example.com",
                'noBon' => "KLS0018",
                'kategori' => 3, // TV
                'namaBarang' => "TV LED 24 Inch LG",
                'deskripsiBarang' => "Layar normal, bekas lama",
                'imei' => "352099000001018",
                'hargaGadai' => 600000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 21),
                'userId' => 1019,
                'nasabahId' => 19,
                'nama' => "Aris Munandar",
                'nik' => "3201302402920019",
                'telp' => "0888999123419",
                'email' => "aris.munandar@example.com",
                'noBon' => "KLS0019",
                'kategori' => 2,
                'namaBarang' => "Pocco F1",
                'deskripsiBarang' => "Fungsi normal, batre cepat habis",
                'imei' => "352099000001019",
                'hargaGadai' => 450000,
            ],
            // June 22, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 22),
                'userId' => 1020,
                'nasabahId' => 20,
                'nama' => "Linda Susanti",
                'nik' => "3201311010950020",
                'telp' => "0811223344520",
                'email' => "linda.susanti@example.com",
                'noBon' => "KLS0020",
                'kategori' => 1, // Laptop
                'namaBarang' => "Laptop Acer",
                'deskripsiBarang' => "Kondisi fisik baik, baterai agak drop",
                'imei' => "352099000001020",
                'hargaGadai' => 1100000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 22),
                'userId' => 1021,
                'nasabahId' => 21,
                'nama' => "Doni Pratama",
                'nik' => "3201322011880021",
                'telp' => "0812345678921",
                'email' => "doni.pratama@example.com",
                'noBon' => "KLS0021",
                'kategori' => 2, // Handphone
                'namaBarang' => "iPhone 7 Plus",
                'deskripsiBarang' => "Kondisi mulus, iCloud aman",
                'imei' => "352099000001021",
                'hargaGadai' => 900000,
            ],
            // June 23, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 23),
                'userId' => 1022,
                'nasabahId' => 22,
                'nama' => "Putri Amelia",
                'nik' => "3201331503900022",
                'telp' => "0813224466822",
                'email' => "putri.amelia@example.com",
                'noBon' => "KLS0022",
                'kategori' => 2, // Handphone
                'namaBarang' => "Vivo Y12",
                'deskripsiBarang' => "Ada retak di backdoor, fungsi normal",
                'imei' => "352099000001022",
                'hargaGadai' => 650000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 23),
                'userId' => 1023,
                'nasabahId' => 23,
                'nama' => "Budi Santoso",
                'nik' => "3201342507850023",
                'telp' => "0815123456723",
                'email' => "budi.santoso@example.com",
                'noBon' => "KLS0023",
                'kategori' => 3, // TV
                'namaBarang' => "TV Samsung 32 Inch",
                'deskripsiBarang' => "Masih bagus, tanpa dus",
                'imei' => "352099000001023",
                'hargaGadai' => 1100000,
            ],
            // June 24, 2025
            [
                'tanggal' => Carbon::create(2025, 6, 24),
                'userId' => 1024,
                'nasabahId' => 24,
                'nama' => "Sari Lestari",
                'nik' => "3201351204910024",
                'telp' => "0877112233424",
                'email' => "sari.lestari@example.com",
                'noBon' => "KLS0024",
                'kategori' => 2, // Handphone
                'namaBarang' => "Realme C11",
                'deskripsiBarang' => "Kondisi mulus, kelengkapan fullset",
                'imei' => "352099000001024",
                'hargaGadai' => 750000,
            ],
            [
                'tanggal' => Carbon::create(2025, 6, 24),
                'userId' => 1025,
                'nasabahId' => 25,
                'nama' => "Joko Susilo",
                'nik' => "3201362009890025",
                'telp' => "0857123456725",
                'email' => "joko.susilo@example.com",
                'noBon' => "KLS0025",
                'kategori' => 1, // Laptop
                'namaBarang' => "MacBook Air M1",
                'deskripsiBarang' => "Like new, jarang dipakai",
                'imei' => "352099000001025",
                'hargaGadai' => 6000000,
            ],
        ];

        foreach ($transactionsData as $data) {
            $username = strtolower(str_replace(' ', '', $data['nama']) . substr($data['nik'], -2));
            $password = substr($data['nik'], 0, 6);

            DB::table('users')->insert([
                'id_users' => $data['userId'],
                'nama' => $data['nama'],
                'email' => $data['email'],
                'username' => $username,
                'password' => Hash::make($password),
                'role' => 'Nasabah',
                'id_cabang' => $idCabang,
                'created_at' => $data['tanggal'],
                'updated_at' => $data['tanggal'],
            ]);

            DB::table('nasabah')->insert([
                'id_user' => $data['userId'],
                'nama' => $data['nama'],
                'nik' => $data['nik'],
                'alamat' => $this->getRandomAddress(), // Using a helper function for random addresses
                'telepon' => $data['telp'],
                'status_blacklist' => false,
                'created_at' => $data['tanggal'],
                'updated_at' => $data['tanggal'],
            ]);

            DB::table('barang_gadai')->insert([
                'no_bon' => $data['noBon'],
                'id_nasabah' => $data['nasabahId'],
                'id_cabang' => $idCabang,
                'id_kategori' => $data['kategori'],
                'id_bunga_tenor' => $tenorId,
                'nama_barang' => $data['namaBarang'],
                'deskripsi' => $data['deskripsiBarang'],
                'imei' => $data['imei'],
                'bunga' => 5, // Assuming fixed bunga for now
                'harga_gadai' => $data['hargaGadai'],
                'status' => 'Tergadai',
                'tempo' => $data['tanggal']->copy()->addDays($selectedTenor),
                'telat' => 0,
                'created_at' => $data['tanggal'],
                'updated_at' => $data['tanggal'],
            ]);

            DB::table('transaksi')->insert([
                'no_bon' => $data['noBon'],
                'id_nasabah' => $data['nasabahId'],
                'id_user' => $data['userId'],
                'id_cabang' => $idCabang,
                'jenis_transaksi' => 'terima',
                'arus_kas' => 'keluar',
                'jumlah' => $data['hargaGadai'],
                'created_at' => $data['tanggal'],
                'updated_at' => $data['tanggal'],
            ]);

            DB::table('log_aktivitas')->insert([
                'id_users' => 3, // Assuming this is a static admin/staff user ID
                'id_cabang' => $idCabang,
                'kategori' => 'transaksi',
                'aksi' => 'terima',
                'deskripsi' => "Transaksi terima untuk no bon {$data['noBon']}",
                'data_lama' => null,
                'data_baru' => json_encode([
                    'no_bon' => $data['noBon'],
                    'id_nasabah' => $data['nasabahId'],
                    'harga_gadai' => $data['hargaGadai'],
                    'tanggal' => $data['tanggal']->toDateString(),
                    'status' => 'Tergadai'
                ]),
                'ip_address' => '192.168.1.10',
                'user_agent' => 'SeederScript/1.0',
                'waktu_aktivitas' => $data['tanggal'],
                'created_at' => $data['tanggal'],
                'updated_at' => $data['tanggal'],
            ]);
        }
    }

    /**
     * Helper function to get a random address from a predefined list.
     * You can expand this list as needed.
     * @return string
     */
    protected function getRandomAddress()
    {
        $addresses = [
            "Jl. Raya Parung, Kab. Bogor",
            "Kp. Ciseeng, Kab. Bogor",
            "Desa Kemang, Kab. Bogor",
            "Jl. Raya Ciseeng, Kab. Bogor",
            "Cibinong, Kab. Bogor",
            "Parung, Kab. Bogor",
            "Gunung Sindur, Kab. Bogor",
            "Cigombong, Kab. Bogor",
            "Ciawi, Kab. Bogor",
            "Dramaga, Kab. Bogor",
            "Cisarua, Kab. Bogor",
            "Tajut halang, Kab. Bogor",
            "Leuwiliang, Kab. Bogor",
            "Kp Poncol, tajur, Kab. Bogor",
            "Parung Panjang, Kab. Bogor",
            "Citeureup, Kab. Bogor",
            "Sukaraja, Kab. Bogor",
            "Tanah Sereal, Kab. Bogor",
            "Ciomas, Kab. Bogor",
        ];
        return $addresses[array_rand($addresses)];
    }
}

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
        DB::table('transaksi')->truncate();
        DB::table('barang_gadai')->truncate();
        DB::table('nasabah')->truncate();
        DB::table('log_aktivitas')->truncate();
        DB::table('users')->where('role', 'Nasabah')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tenorOptions = [7, 14, 30];
        $selectedTenor = $tenorOptions[array_rand($tenorOptions)];
        $tenorId = DB::table('bunga_tenor')->where('tenor', $selectedTenor)->value('id');
        
        $tanggal = Carbon::create(2025, 6, 14);
        $idCabang = 1;

        // ========== Transaksi 1 ==========
        $userId1 = 1001;
        $nasabahId1 = 1;
        $nama1 = "Dewi Kartika";
        $nik1 = "3201121401980001";
        $telp1 = "0812345678912";
        $username1 = "dewikartika12";
        $password1 = substr($nik1, 0, 6);
        $noBon1 = "KLS0001";

        DB::table('users')->insert([
            'id_users' => $userId1,
            'nama' => $nama1,
            'email' => "dewi.kartika@example.com",
            'username' => $username1,
            'password' => Hash::make($password1),
            'role' => 'Nasabah',
            'id_cabang' => $idCabang,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('nasabah')->insert([
            'id_user' => $userId1,
            'nama' => $nama1,
            'nik' => $nik1,
            'alamat' => "Jl. Raya Parung, Kab. Bogor",
            'telepon' => $telp1,
            'status_blacklist' => false,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('barang_gadai')->insert([
            'no_bon' => $noBon1,
            'id_nasabah' => $nasabahId1,
            'id_cabang' => $idCabang,
            'id_kategori' => 1, // Laptop
            'id_bunga_tenor' => $tenorId,
            'nama_barang' => "Laptop ASUS",
            'deskripsi' => "Kondisi sangat baik",
            'imei' => "352099000001111",
            'bunga' => 5,
            'harga_gadai' => 1750000,
            'status' => 'Tergadai',
            'tempo' => $tanggal->copy()->addDays($selectedTenor),
            'telat' => 0,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('transaksi')->insert([
            'no_bon' => $noBon1,
            'id_nasabah' => $nasabahId1,
            'id_user' => $userId1,
            'id_cabang' => $idCabang,
            'jenis_transaksi' => 'terima',
            'arus_kas' => 'keluar',
            'jumlah' => 1750000,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('log_aktivitas')->insert([
            'id_users' => 3,
            'id_cabang' => $idCabang,
            'kategori' => 'transaksi',
            'aksi' => 'terima',
            'deskripsi' => "Transaksi terima untuk no bon $noBon1",
            'data_lama' => null,
            'data_baru' => json_encode([
                'no_bon' => $noBon1,
                'id_nasabah' => $nasabahId1,
                'harga_gadai' => 1750000,
                'tanggal' => $tanggal->toDateString(),
                'status' => 'Tergadai'
            ]),
            'ip_address' => '192.168.1.10',
            'user_agent' => 'SeederScript/1.0',
            'waktu_aktivitas' => $tanggal,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        // ========== Transaksi 2 ==========
        $userId2 = 1002;
        $nasabahId2 = 2;
        $nama2 = "Ahmad Rizki";
        $nik2 = "3201132305900002";
        $telp2 = "0823456789123";
        $username2 = "ahmadrizki23";
        $password2 = substr($nik2, 0, 6);
        $noBon2 = "KLS0002";

        DB::table('users')->insert([
            'id_users' => $userId2,
            'nama' => $nama2,
            'email' => "ahmad.rizki@example.com",
            'username' => $username2,
            'password' => Hash::make($password2),
            'role' => 'Nasabah',
            'id_cabang' => $idCabang,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('nasabah')->insert([
            'id_user' => $userId2,
            'nama' => $nama2,
            'nik' => $nik2,
            'alamat' => "Kp. Ciseeng, Kab. Bogor",
            'telepon' => $telp2,
            'status_blacklist' => false,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('barang_gadai')->insert([
            'no_bon' => $noBon2,
            'id_nasabah' => $nasabahId2,
            'id_cabang' => $idCabang,
            'id_kategori' => 2, // Handphone
            'id_bunga_tenor' => $tenorId,
            'nama_barang' => "HP Samsung",
            'deskripsi' => "Kondisi normal, lecet ringan",
            'imei' => "352099000002222",
            'bunga' => 5,
            'harga_gadai' => 1200000,
            'status' => 'Tergadai',
            'tempo' => $tanggal->copy()->addDays($selectedTenor),
            'telat' => 0,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('transaksi')->insert([
            'no_bon' => $noBon2,
            'id_nasabah' => $nasabahId2,
            'id_user' => $userId2,
            'id_cabang' => $idCabang,
            'jenis_transaksi' => 'terima',
            'arus_kas' => 'keluar',
            'jumlah' => 1200000,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);

        DB::table('log_aktivitas')->insert([
            'id_users' => 3,
            'id_cabang' => $idCabang,
            'kategori' => 'transaksi',
            'aksi' => 'terima',
            'deskripsi' => "Transaksi terima untuk no bon $noBon2",
            'data_lama' => null,
            'data_baru' => json_encode([
                'no_bon' => $noBon2,
                'id_nasabah' => $nasabahId2,
                'harga_gadai' => 1200000,
                'tanggal' => $tanggal->toDateString(),
                'status' => 'Tergadai'
            ]),
            'ip_address' => '192.168.1.10',
            'user_agent' => 'SeederScript/1.0',
            'waktu_aktivitas' => $tanggal,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);
        
// ========== Tanggal 15 Juni 2025 ==========

$tanggal = Carbon::create(2025, 6, 15);

// ---------- Transaksi 1 ----------
$userId3 = 1003;
$nasabahId3 = 3;
$nama3 = "Siti Nurhaliza";
$nik3 = "3201141202980003";
$telp3 = "0838123456789";
$username3 = "sitinurhaliza89";
$password3 = substr($nik3, 0, 6);
$noBon3 = "KLS0003";

DB::table('users')->insert([
    'id_users' => $userId3,
    'nama' => $nama3,
    'email' => "siti.nurhaliza@example.com",
    'username' => $username3,
    'password' => Hash::make($password3),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId3,
    'nama' => $nama3,
    'nik' => $nik3,
    'alamat' => "Desa Kemang, Kab. Bogor",
    'telepon' => $telp3,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon3,
    'id_nasabah' => $nasabahId3,
    'id_cabang' => $idCabang,
    'id_kategori' => 2, // Handphone
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "HP Oppo",
    'deskripsi' => "Layar mulus, baterai bagus",
    'imei' => "352099000003333",
    'bunga' => 5,
    'harga_gadai' => 1000000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon3,
    'id_nasabah' => $nasabahId3,
    'id_user' => $userId3,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 1000000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon3",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon3,
        'id_nasabah' => $nasabahId3,
        'harga_gadai' => 1000000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ---------- Transaksi 2 ----------
$userId4 = 1004;
$nasabahId4 = 4;
$nama4 = "Rizky Hidayat";
$nik4 = "3201150101990004";
$telp4 = "0815987654321";
$username4 = "rizkyhidayat21";
$password4 = substr($nik4, 0, 6);
$noBon4 = "KLS0004";

DB::table('users')->insert([
    'id_users' => $userId4,
    'nama' => $nama4,
    'email' => "rizky.hidayat@example.com",
    'username' => $username4,
    'password' => Hash::make($password4),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId4,
    'nama' => $nama4,
    'nik' => $nik4,
    'alamat' => "Jl. Raya Ciseeng, Kab. Bogor",
    'telepon' => $telp4,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon4,
    'id_nasabah' => $nasabahId4,
    'id_cabang' => $idCabang,
    'id_kategori' => 3, // TV
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "TV Polytron",
    'deskripsi' => "Masih normal, layar bersih",
    'imei' => "352099000004444",
    'bunga' => 5,
    'harga_gadai' => 800000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon4,
    'id_nasabah' => $nasabahId4,
    'id_user' => $userId4,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 800000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon4",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon4,
        'id_nasabah' => $nasabahId4,
        'harga_gadai' => 800000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ---------- Transaksi 3 ----------
$userId5 = 1005;
$nasabahId5 = 5;
$nama5 = "Yuli Andriani";
$nik5 = "3201162705950005";
$telp5 = "0878123456700";
$username5 = "yuliandriani00";
$password5 = substr($nik5, 0, 6);
$noBon5 = "KLS0005";

DB::table('users')->insert([
    'id_users' => $userId5,
    'nama' => $nama5,
    'email' => "yuli.andriani@example.com",
    'username' => $username5,
    'password' => Hash::make($password5),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId5,
    'nama' => $nama5,
    'nik' => $nik5,
    'alamat' => "Cibinong, Kab. Bogor",
    'telepon' => $telp5,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon5,
    'id_nasabah' => $nasabahId5,
    'id_cabang' => $idCabang,
    'id_kategori' => 1, // Laptop
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Laptop Lenovo",
    'deskripsi' => "Baret tipis, masih cepat",
    'imei' => "352099000005555",
    'bunga' => 5,
    'harga_gadai' => 1500000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon5,
    'id_nasabah' => $nasabahId5,
    'id_user' => $userId5,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 1500000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon5",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon5,
        'id_nasabah' => $nasabahId5,
        'harga_gadai' => 1500000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ========== Tanggal 16 Juni 2025 ==========

$tanggal = Carbon::create(2025, 6, 16);

// ---------- Transaksi 1 ----------
$userId6 = 1006;
$nasabahId6 = 6;
$nama6 = "Dedi Supriyadi";
$nik6 = "3201171010920006";
$telp6 = "0821123456789";
$username6 = "dedisupriyadi89";
$password6 = substr($nik6, 0, 6);
$noBon6 = "KLS0006";

DB::table('users')->insert([
    'id_users' => $userId6,
    'nama' => $nama6,
    'email' => "dedi.supriyadi@example.com",
    'username' => $username6,
    'password' => Hash::make($password6),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId6,
    'nama' => $nama6,
    'nik' => $nik6,
    'alamat' => "Parung, Kab. Bogor",
    'telepon' => $telp6,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon6,
    'id_nasabah' => $nasabahId6,
    'id_cabang' => $idCabang,
    'id_kategori' => 3, // TV
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "TV Samsung",
    'deskripsi' => "Layar bersih, remote tidak ada",
    'imei' => "352099000006666",
    'bunga' => 5,
    'harga_gadai' => 900000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon6,
    'id_nasabah' => $nasabahId6,
    'id_user' => $userId6,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 900000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon6",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon6,
        'id_nasabah' => $nasabahId6,
        'harga_gadai' => 900000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ---------- Transaksi 2 ----------
$userId7 = 1007;
$nasabahId7 = 7;
$nama7 = "Ani Marlina";
$nik7 = "3201182205980007";
$telp7 = "0896123456798";
$username7 = "animarlina98";
$password7 = substr($nik7, 0, 6);
$noBon7 = "KLS0007";

DB::table('users')->insert([
    'id_users' => $userId7,
    'nama' => $nama7,
    'email' => "ani.marlina@example.com",
    'username' => $username7,
    'password' => Hash::make($password7),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId7,
    'nama' => $nama7,
    'nik' => $nik7,
    'alamat' => "Gunung Sindur, Kab. Bogor",
    'telepon' => $telp7,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon7,
    'id_nasabah' => $nasabahId7,
    'id_cabang' => $idCabang,
    'id_kategori' => 2, // Handphone
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "HP Xiaomi",
    'deskripsi' => "Kondisi normal, casing lecet",
    'imei' => "352099000007777",
    'bunga' => 5,
    'harga_gadai' => 850000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon7,
    'id_nasabah' => $nasabahId7,
    'id_user' => $userId7,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 850000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon7",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon7,
        'id_nasabah' => $nasabahId7,
        'harga_gadai' => 850000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ========== Tanggal 17 Juni 2025 ==========
$tanggal = Carbon::create(2025, 6, 17);

// ---------- Transaksi 1 ----------
$userId8 = 1008;
$nasabahId8 = 8;
$nama8 = "Rina Apriani";
$nik8 = "3201192302930008";
$telp8 = "0813123456789";
$username8 = "rinaapriani89";
$password8 = substr($nik8, 0, 6);
$noBon8 = "KLS0008";

DB::table('users')->insert([
    'id_users' => $userId8,
    'nama' => $nama8,
    'email' => "rina.apriani@example.com",
    'username' => $username8,
    'password' => Hash::make($password8),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId8,
    'nama' => $nama8,
    'nik' => $nik8,
    'alamat' => "Ciseeng, Kab. Bogor",
    'telepon' => $telp8,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon8,
    'id_nasabah' => $nasabahId8,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "HP Nokia",
    'deskripsi' => "Body kusam, layar normal",
    'imei' => "352099000008888",
    'bunga' => 5,
    'harga_gadai' => 500000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon8,
    'id_nasabah' => $nasabahId8,
    'id_user' => $userId8,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 500000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon8",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon8,
        'id_nasabah' => $nasabahId8,
        'harga_gadai' => 500000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ---------- Transaksi 2 ----------
$userId9 = 1009;
$nasabahId9 = 9;
$nama9 = "Asep Mulyana";
$nik9 = "3201202509870009";
$telp9 = "0899988776655";
$username9 = "asepmulyana55";
$password9 = substr($nik9, 0, 6);
$noBon9 = "KLS0009";

DB::table('users')->insert([
    'id_users' => $userId9,
    'nama' => $nama9,
    'email' => "asep.mulyana@example.com",
    'username' => $username9,
    'password' => Hash::make($password9),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId9,
    'nama' => $nama9,
    'nik' => $nik9,
    'alamat' => "Kemang, Kab. Bogor",
    'telepon' => $telp9,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon9,
    'id_nasabah' => $nasabahId9,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "HP Samsung J2",
    'deskripsi' => "Layar retak sedikit, masih nyala",
    'imei' => "352099000009999",
    'bunga' => 5,
    'harga_gadai' => 700000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon9,
    'id_nasabah' => $nasabahId9,
    'id_user' => $userId9,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 700000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon9",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon9,
        'id_nasabah' => $nasabahId9,
        'harga_gadai' => 700000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ========== Tanggal 18 Juni 2025 ==========
$tanggal = Carbon::create(2025, 6, 18);

// ---------- Transaksi 1 ----------
$userId10 = 1010;
$nasabahId10 = 10;
$nama10 = "Yuliani Sari";
$nik10 = "3201213101980010";
$telp10 = "0812345678910";
$username10 = "yulianisari10";
$password10 = substr($nik10, 0, 6);
$noBon10 = "KLS0010";

DB::table('users')->insert([
    'id_users' => $userId10,
    'nama' => $nama10,
    'email' => "yuliani.sari@example.com",
    'username' => $username10,
    'password' => Hash::make($password10),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId10,
    'nama' => $nama10,
    'nik' => $nik10,
    'alamat' => "Cigombong, Kab. Bogor",
    'telepon' => $telp10,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon10,
    'id_nasabah' => $nasabahId10,
    'id_cabang' => $idCabang,
    'id_kategori' => 1,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Laptop Asus",
    'deskripsi' => "Bodi mulus, baterai soak",
    'imei' => "352099000001010",
    'bunga' => 5,
    'harga_gadai' => 1200000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon10,
    'id_nasabah' => $nasabahId10,
    'id_user' => $userId10,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 1200000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon10",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon10,
        'id_nasabah' => $nasabahId10,
        'harga_gadai' => 1200000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ---------- Transaksi 2 ----------
$userId11 = 1011;
$nasabahId11 = 11;
$nama11 = "Dedi Kurniawan";
$nik11 = "3201221205800011";
$telp11 = "0898765432101";
$username11 = "dedikurniawan01";
$password11 = substr($nik11, 0, 6);
$noBon11 = "KLS0011";

DB::table('users')->insert([
    'id_users' => $userId11,
    'nama' => $nama11,
    'email' => "dedi.kurniawan@example.com",
    'username' => $username11,
    'password' => Hash::make($password11),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId11,
    'nama' => $nama11,
    'nik' => $nik11,
    'alamat' => "Parung, Kab. Bogor",
    'telepon' => $telp11,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon11,
    'id_nasabah' => $nasabahId11,
    'id_cabang' => $idCabang,
    'id_kategori' => 3,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "TV Sharp",
    'deskripsi' => "Gores di sisi, gambar normal",
    'imei' => "352099000001011",
    'bunga' => 5,
    'harga_gadai' => 1000000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon11,
    'id_nasabah' => $nasabahId11,
    'id_user' => $userId11,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 1000000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon11",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon11,
        'id_nasabah' => $nasabahId11,
        'harga_gadai' => 1000000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ========== Tanggal 19 Juni 2025 ==========
$tanggal = Carbon::create(2025, 6, 19);

// ---------- Transaksi 1 ----------
$userId12 = 1012;
$nasabahId12 = 12;
$nama12 = "Rina Marlina";
$nik12 = "3201230504930012";
$telp12 = "0899123456712";
$username12 = "rinamarlina12";
$password12 = substr($nik12, 0, 6);
$noBon12 = "KLS0012";

DB::table('users')->insert([
    'id_users' => $userId12,
    'nama' => $nama12,
    'email' => "rina.marlina@example.com",
    'username' => $username12,
    'password' => Hash::make($password12),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId12,
    'nama' => $nama12,
    'nik' => $nik12,
    'alamat' => "Cibinong, Kab. Bogor",
    'telepon' => $telp12,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon12,
    'id_nasabah' => $nasabahId12,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Samsung note 7",
    'deskripsi' => "Layar gores, fungsi normal",
    'imei' => "352099000001012",
    'bunga' => 5,
    'harga_gadai' => 400000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon12,
    'id_nasabah' => $nasabahId12,
    'id_user' => $userId12,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 400000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon12",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon12,
        'id_nasabah' => $nasabahId12,
        'harga_gadai' => 400000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ---------- Transaksi 2 ----------
$userId13 = 1013;
$nasabahId13 = 13;
$nama13 = "Fajar Pratama";
$nik13 = "3201241207810013";
$telp13 = "0899554433213";
$username13 = "fajarpratama13";
$password13 = substr($nik13, 0, 6);
$noBon13 = "KLS0013";

DB::table('users')->insert([
    'id_users' => $userId13,
    'nama' => $nama13,
    'email' => "fajar.pratama@example.com",
    'username' => $username13,
    'password' => Hash::make($password13),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId13,
    'nama' => $nama13,
    'nik' => $nik13,
    'alamat' => "Ciawi, Kab. Bogor",
    'telepon' => $telp13,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon13,
    'id_nasabah' => $nasabahId13,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Xiaomi note 9",
    'deskripsi' => "Baterai cepat habis",
    'imei' => "352099000001013",
    'bunga' => 5,
    'harga_gadai' => 500000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon13,
    'id_nasabah' => $nasabahId13,
    'id_user' => $userId13,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 500000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon13",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon13,
        'id_nasabah' => $nasabahId13,
        'harga_gadai' => 500000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// ---------- Transaksi 3 ----------
$userId14 = 1014;
$nasabahId14 = 14;
$nama14 = "Mulyadi Rahman";
$nik14 = "3201252506780014";
$telp14 = "0899111223314";
$username14 = "mulyadirahman14";
$password14 = substr($nik14, 0, 6);
$noBon14 = "KLS0014";

DB::table('users')->insert([
    'id_users' => $userId14,
    'nama' => $nama14,
    'email' => "mulyadi.rahman@example.com",
    'username' => $username14,
    'password' => Hash::make($password14),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId14,
    'nama' => $nama14,
    'nik' => $nik14,
    'alamat' => "Parung, Kab. Bogor",
    'telepon' => $telp14,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon14,
    'id_nasabah' => $nasabahId14,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Samsung Galaxy S8",
    'deskripsi' => "Casing retak, tombol keras",
    'imei' => "352099000001014",
    'bunga' => 5,
    'harga_gadai' => 450000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon14,
    'id_nasabah' => $nasabahId14,
    'id_user' => $userId14,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 450000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon14",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon14,
        'id_nasabah' => $nasabahId14,
        'harga_gadai' => 450000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);


// tanggal 20 
$tanggal = Carbon::create(2025, 6, 20);

// --- Transaksi 1 ---
$userId15 = 1015;
$nasabahId15 = 15;
$nama15 = "Tia Nurhaliza";
$nik15 = "3201261501990015";
$telp15 = "0888123412315";
$username15 = "tianurhaliza15";
$password15 = substr($nik15, 0, 6);
$noBon15 = "KLS0015";

DB::table('users')->insert([
    'id_users' => $userId15,
    'nama' => $nama15,
    'email' => "tia.nurhaliza@example.com",
    'username' => $username15,
    'password' => Hash::make($password15),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId15,
    'nama' => $nama15,
    'nik' => $nik15,
    'alamat' => "Dramaga, Kab. Bogor",
    'telepon' => $telp15,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon15,
    'id_nasabah' => $nasabahId15,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Oppo A12",
    'deskripsi' => "Masih mulus, sedikit lecet",
    'imei' => "352099000001015",
    'bunga' => 5,
    'harga_gadai' => 700000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon15,
    'id_nasabah' => $nasabahId15,
    'id_user' => $userId15,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 700000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon15",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon15,
        'id_nasabah' => $nasabahId15,
        'harga_gadai' => 700000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// --- Transaksi 2 ---
$userId16 = 1016;
$nasabahId16 = 16;
$nama16 = "Ilham Setiawan";
$nik16 = "3201271203940016";
$telp16 = "0898445567816";
$username16 = "ilhamsetiawan16";
$password16 = substr($nik16, 0, 6);
$noBon16 = "KLS0016";

DB::table('users')->insert([
    'id_users' => $userId16,
    'nama' => $nama16,
    'email' => "ilham.setiawan@example.com",
    'username' => $username16,
    'password' => Hash::make($password16),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId16,
    'nama' => $nama16,
    'nik' => $nik16,
    'alamat' => "Cisarua, Kab. Bogor",
    'telepon' => $telp16,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon16,
    'id_nasabah' => $nasabahId16,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Samsung A10",
    'deskripsi' => "Layar agak buram",
    'imei' => "352099000001016",
    'bunga' => 5,
    'harga_gadai' => 600000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon16,
    'id_nasabah' => $nasabahId16,
    'id_user' => $userId16,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 600000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon16",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon16,
        'id_nasabah' => $nasabahId16,
        'harga_gadai' => 600000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);



// tanggal 21 Juni 2025
$tanggal = Carbon::create(2025, 6, 21);

// --- Transaksi 1 ---
$userId17 = 1017;
$nasabahId17 = 17;
$nama17 = "Yuliana Sari";
$nik17 = "3201281101830017";
$telp17 = "0888432156717";
$username17 = "yulianasari17";
$password17 = substr($nik17, 0, 6);
$noBon17 = "KLS0017";

DB::table('users')->insert([
    'id_users' => $userId17,
    'nama' => $nama17,
    'email' => "yuliana.sari@example.com",
    'username' => $username17,
    'password' => Hash::make($password17),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId17,
    'nama' => $nama17,
    'nik' => $nik17,
    'alamat' => "Tajut halang, Kab. Bogor",
    'telepon' => $telp17,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon17,
    'id_nasabah' => $nasabahId17,
    'id_cabang' => $idCabang,
    'id_kategori' => 2, // HP
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Xiaomi Redmi 9",
    'deskripsi' => "Bodi ada lecet, fungsi normal",
    'imei' => "352099000001017",
    'bunga' => 5,
    'harga_gadai' => 550000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon17,
    'id_nasabah' => $nasabahId17,
    'id_user' => $userId17,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 550000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon17",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon17,
        'id_nasabah' => $nasabahId17,
        'harga_gadai' => 550000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// --- Transaksi 2 ---
$userId18 = 1018;
$nasabahId18 = 18;
$nama18 = "Rahmat Widodo";
$nik18 = "3201290302800018";
$telp18 = "0898765432118";
$username18 = "rahmatwidodo18";
$password18 = substr($nik18, 0, 6);
$noBon18 = "KLS0018";

DB::table('users')->insert([
    'id_users' => $userId18,
    'nama' => $nama18,
    'email' => "rahmat.widodo@example.com",
    'username' => $username18,
    'password' => Hash::make($password18),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId18,
    'nama' => $nama18,
    'nik' => $nik18,
    'alamat' => "Leuwiliang, Kab. Bogor",
    'telepon' => $telp18,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon18,
    'id_nasabah' => $nasabahId18,
    'id_cabang' => $idCabang,
    'id_kategori' => 3, // TV
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "TV LED 24 Inch LG",
    'deskripsi' => "Layar normal, bekas lama",
    'imei' => "352099000001018",
    'bunga' => 5,
    'harga_gadai' => 600000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon18,
    'id_nasabah' => $nasabahId18,
    'id_user' => $userId18,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 600000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon18",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon18,
        'id_nasabah' => $nasabahId18,
        'harga_gadai' => 600000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

// --- Transaksi 3 ---
$userId19 = 1019;
$nasabahId19 = 19;
$nama19 = "Aris Munandar";
$nik19 = "3201302402920019";
$telp19 = "0888999123419";
$username19 = "arismunandar19";
$password19 = substr($nik19, 0, 6);
$noBon19 = "KLS0019";

DB::table('users')->insert([
    'id_users' => $userId19,
    'nama' => $nama19,
    'email' => "aris.munandar@example.com",
    'username' => $username19,
    'password' => Hash::make($password19),
    'role' => 'Nasabah',
    'id_cabang' => $idCabang,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('nasabah')->insert([
    'id_user' => $userId19,
    'nama' => $nama19,
    'nik' => $nik19,
    'alamat' => "Kp Poncol, tajur, Kab. Bogor",
    'telepon' => $telp19,
    'status_blacklist' => false,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('barang_gadai')->insert([
    'no_bon' => $noBon19,
    'id_nasabah' => $nasabahId19,
    'id_cabang' => $idCabang,
    'id_kategori' => 2,
    'id_bunga_tenor' => $tenorId,
    'nama_barang' => "Pocco F1",
    'deskripsi' => "Fungsi normal, batre cepat habis",
    'imei' => "352099000001019",
    'bunga' => 5,
    'harga_gadai' => 450000,
    'status' => 'Tergadai',
    'tempo' => $tanggal->copy()->addDays($selectedTenor),
    'telat' => 0,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('transaksi')->insert([
    'no_bon' => $noBon19,
    'id_nasabah' => $nasabahId19,
    'id_user' => $userId19,
    'id_cabang' => $idCabang,
    'jenis_transaksi' => 'terima',
    'arus_kas' => 'keluar',
    'jumlah' => 450000,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);

DB::table('log_aktivitas')->insert([
    'id_users' => 3,
    'id_cabang' => $idCabang,
    'kategori' => 'transaksi',
    'aksi' => 'terima',
    'deskripsi' => "Transaksi terima untuk no bon $noBon19",
    'data_lama' => null,
    'data_baru' => json_encode([
        'no_bon' => $noBon19,
        'id_nasabah' => $nasabahId19,
        'harga_gadai' => 450000,
        'tanggal' => $tanggal->toDateString(),
        'status' => 'Tergadai'
    ]),
    'ip_address' => '192.168.1.10',
    'user_agent' => 'SeederScript/1.0',
    'waktu_aktivitas' => $tanggal,
    'created_at' => $tanggal,
    'updated_at' => $tanggal,
]);





    }
}

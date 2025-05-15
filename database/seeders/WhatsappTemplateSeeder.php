<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WhatsappTemplate;

class WhatsappTemplateSeeder extends Seeder
{
    public function run()
    {
        WhatsappTemplate::create([
            'type' => 'perpanjang',
            'message' => "*🔁 Perpanjangan Berhasil!*\n\n" .
                         "🆔 No BON: {{no_bon}}\n" .
                         "🏷 Nama Barang: {{nama_barang}}\n" .
                         "👤 Nama: {{nama}}\n" .
                         "💰 Jumlah: Rp {{jumlah}}\n" .
                         "📅 Tanggal: {{tanggal}}\n\n" .
                         "Barang Anda telah berhasil diperpanjang di *Pegadaian Kami*. Terima kasih 🙏"
        ]);

        WhatsappTemplate::create([
            'type' => 'tebus',
            'message' => "*📦 Transaksi Tebus Berhasil!*\n\n" .
                         "🆔 No BON: {{no_bon}}\n" .
                         "🏷 Nama Barang: {{nama_barang}}\n" .
                         "👤 Nama: {{nama}}\n" .
                         "🏦 Cabang: {{nama_cabang}}\n" .
                         "💰 Jumlah: Rp {{jumlah}}\n" .
                         "📅 Tanggal: {{tanggal}}\n\n" .
                         "Terima kasih telah menebus barang Anda di *Pegadaian Kami* 🙏"
        ]);


    }
}

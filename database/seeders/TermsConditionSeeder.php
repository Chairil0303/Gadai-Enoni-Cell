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
            'title' => 'Syarat dan Ketentuan Pengguna',
            'content' => '
<ol>
    <li>Mengetahui dan menyatakan setuju:
        <ol type="a">
            <li>Mengikuti seluruh aturan yang berlaku di Enoni Cellular.</li>
            <li>Barang yang saya gadaikan adalah milik pribadi dan bukan hasil tindak kejahatan, dan apabila terjadi masalah, pihak saya akan bertanggung jawab tanpa melibatkan pihak Enoni Cellular.</li>
            <li>Menyetujui bahwa tidak ada pengurangan apabila pengambilan barang sebelum jatuh tempo.</li>
            <li>Menyetujui denda keterlambatan sebesar 1% per hari dari besar gadai.</li>
            <li>Menerima dan menyetujui barang menjadi Hak Milik Enoni Cellular dan kemudian dijual setelah kesepakatan tidak berakhir, minimal 1 (satu) tahun setelah jatuh tempo.</li>
            <li>Mengetahui dan menyetujui bahwa kerusakan barang apabila melewati 1 bulan di penyimpanan Enoni Cellular menjadi tanggung jawab saya.</li>
        </ol>
    </li>
</ol>
<b>Syarat Pengambilan Barang:</b>
<ul>
    <li>Wajib membawa Surat/Bon gadai dan KTP Asli.</li>
    <li>Pengambilan barang yang diwakilkan diwajibkan:</li>
    <ul>
        <li>Surat kuasa dari si penggadai,</li>
        <li>Surat/bon gadai,</li>
        <li>KTP asli atas nama,</li>
        <li>KTP asli yang mewakilkan.</li>
    </ul>
</ul>
'
        ]);
    }
}

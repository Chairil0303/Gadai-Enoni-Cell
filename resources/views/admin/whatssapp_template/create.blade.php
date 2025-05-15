@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h2 class="text-xl font-bold mb-4">Tambah Template WhatsApp</h2>
        <form action="{{ route('admin.whatsapp_template.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block">Type:</label>
                <select name="type" class="w-full p-2 border">
                    <option value="perpanjang">Perpanjang Gadai</option>
                    <option value="tebus">Tebus Gadai</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block">Message Template:</label>
                <textarea name="message" class="w-full p-2 border" rows="5" placeholder="Contoh: Terima kasih, barang Anda sudah diperpanjang..."></textarea>
                @verbatim
                <p class="text-sm text-gray-500 mt-2">
                    Gunakan format berikut untuk template:<br>
                    - {{no_bon}} → Nomor BON<br>
                    - {{nama_barang}} → Nama Barang<br>
                    - {{nama}} → Nama Nasabah<br>
                    - {{nama_cabang}} → Nama Cabang<br>
                    - {{jumlah}} → Jumlah Pembayaran<br>
                    - {{tanggal}} → Tanggal Transaksi<br>
                </p>
                @endverbatim

            </div>

            <button class="bg-green-500 text-white px-4 py-2 rounded-lg">Simpan</button>
        </form>
    </div>
@endsection

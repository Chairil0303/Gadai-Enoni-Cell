@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white py-1 px-6 rounded-xl shadow-md text-sm">

    <!-- Header -->
    <div class="text-center mb-3">
        <h2 class="text-2xl font-semibold text-gray-800">Konfirmasi Perpanjangan Gadai</h2>
    </div>

    <!-- 3 Kolom: Nasabah, Bon Lama, Bon Baru -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Modal Pop-up -->
        <div id="modalDetail" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <!-- Close Button -->
                <button onclick="document.getElementById('modalDetail').classList.add('hidden')" class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-lg">
                    &times;
                </button>
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Informasi Barang Gadai</h3>
                <p><strong>Nama Barang:</strong> {{ $lama->nama_barang }}</p>
                <p><strong>Deskripsi:</strong> {{ $lama->deskripsi ?? '-' }}</p>
                <p><strong>IMEI:</strong> {{ $lama->imei }}</p>
                <p><strong>Status:</strong> {{ $lama->status }}</p>
                {{-- Jika kamu ingin tampilkan kategori, pastikan relasi ke model kategori sudah dibuat --}}
                @if($lama->kategori)
                    <p><strong>Kategori:</strong> {{ $lama->kategori->nama_kategori }}</p>
                @endif
            </div>
        </div>


        <!-- Informasi Nasabah -->
        <div class="bg-gray-50 p-4 rounded-lg border">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Nasabah</h3>
            <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
            <p><strong>NIK:</strong> {{ $nasabah->nik }}</p>
            <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
            <p><strong>Telepon:</strong> {{ $nasabah->telepon }}</p>
        </div>

        <!-- Bon Lama -->
        <div class="bg-gray-50 p-4 rounded-lg border relative">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Detail Bon Lama</h3>
            <p><strong>No BON:</strong> {{ $lama->no_bon }}</p>
            <p><strong>Tenor:</strong> {{ $lama->tenor }} hari</p>
            <p><strong>Tempo:</strong> {{ $lama->tempo }}</p>
            <p><strong>Harga Gadai:</strong> Rp {{ number_format($lama->harga_gadai, 0, ',', '.') }}</p>
            <p><strong>Bunga:</strong> Rp {{ number_format($lama->bunga, 0, ',', '.') }}</p>
            <p><strong>Denda Keterlambatan:</strong> Rp {{ number_format($denda_lama, 0, ',', '.') }}</p>
            <hr class="my-2">
            <p><strong>Total Bon Lama:</strong> Rp {{ number_format($total_lama, 0, ',', '.') }}</p>
            <!-- Tombol Detail -->
            <button onclick="document.getElementById('modalDetail').classList.remove('hidden')"
                class="bg-success hover:bg-blue-600 text-white px-4 py-1 rounded text-sm">
                Detail Barang
            </button>
        </div>


        <!-- Bon Baru -->
        <div class="bg-gray-50 p-4 rounded-lg border">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Detail Bon Baru</h3>
            <p><strong>No BON Baru:</strong> {{ $baru['no_bon'] }}</p>
            <p><strong>Tenor:</strong> {{ $baru['tenor'] }} hari</p>
            <p><strong>Harga Gadai:</strong> Rp {{ number_format($baru['harga_gadai'], 0, ',', '.') }}</p>
            <p><strong>Bunga:</strong> Rp {{ number_format($baru['bunga'], 0, ',', '.') }}</p>
            <p><strong>Tempo:</strong> {{ $baru['tempo'] }}</p>
            <hr class="my-2">
            <p><strong>Total Bon Baru:</strong> Rp {{ number_format($total_baru, 0, ',', '.') }}</p>
        </div>

    </div>

    <!-- Total Keseluruhan -->
    <div class="mt-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded text-center text-base">
        <p><strong>Total Tagihan yang Harus Dibayar:</strong> Rp {{ number_format($total_tagihan, 0, ',', '.') }}</p>
    </div>

    <!-- Form Simpan Final -->
    <form action="{{ route('perpanjang_gadai.store') }}" method="POST" class="mt-6">
        @csrf
        <input type="hidden" name="no_bon_lama" value="{{ $lama->no_bon }}">
        <input type="hidden" name="no_bon_baru" value="{{ $baru['no_bon'] }}">
        <input type="hidden" name="tenor" value="{{ $baru['tenor'] }}">
        <input type="hidden" name="harga_gadai" value="{{ $baru['harga_gadai'] }}">
        <input type="hidden" name="bunga" value="{{ $baru['bunga'] }}">

        <div class="flex justify-end gap-4">
            <a href="{{ route('perpanjang_gadai.create') }}"
                class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600">Kembali</a>
            <button type="submit"
                class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700">Perpanjang Gadai</button>
        </div>
    </form>
</div>
@endsection

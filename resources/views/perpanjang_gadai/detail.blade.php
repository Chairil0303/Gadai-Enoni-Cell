@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-3 rounded-xl shadow-md text-sm">

    <!-- Informasi Nasabah -->
    <div class="mb-1 border-b pb-1 text-center">
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Informasi Nasabah</h3>
        <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
        <p><strong>NIK:</strong> {{ $nasabah->nik }}</p>
        <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
        <p><strong>Telepon:</strong> {{ $nasabah->telepon }}</p>
    </div>

    <!-- Row Bon Lama dan Baru -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Bon Lama -->
        <div class="bg-gray-50 p-4 rounded-lg border">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Detail Bon Lama</h3>
            <p><strong>No BON:</strong> {{ $lama->no_bon }}</p>
            <p><strong>Tenor:</strong> {{ $lama->tenor }} hari</p>
            <p><strong>Harga Gadai:</strong> Rp {{ number_format($lama->harga_gadai, 0, ',', '.') }}</p>
            <p><strong>Bunga:</strong> Rp {{ number_format($lama->bunga, 0, ',', '.') }}</p>
            <p><strong>Tempo:</strong> {{ $lama->tempo }}</p>
            <hr class="my-2">
            <p><strong>Total Bon Lama:</strong> Rp {{ number_format($total_lama, 0, ',', '.') }}</p>
        </div>

        <!-- Bon Baru -->
        <div class="bg-gray-50 p-4 rounded-lg border">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Detail Bon Baru</h3>
            <p><strong>No BON Baru:</strong> {{ $baru['no_bon'] }}</p>
            <p><strong>Tenor:</strong> {{ $baru['tenor'] }} hari</p>
            <p><strong>Harga Gadai:</strong> Rp {{ number_format($baru['harga_gadai'], 0, ',', '.') }}</p>
            <p><strong>Bunga:</strong> Rp {{ number_format($baru['bunga'], 0, ',', '.') }}</p>
            <p><strong>Tempo:</strong> {{ \Carbon\Carbon::now()->addDays((int) $baru['tenor'])->format('Y-m-d') }}</p>
            <hr class="my-2">
            <p><strong>Total Bon Baru:</strong> Rp {{ number_format($total_baru, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Total Keseluruhan -->
    <div class="mt-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
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

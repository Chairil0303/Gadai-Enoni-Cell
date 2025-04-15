@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">Konfirmasi Perpanjang Gadai</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card Data Nasabah -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="bg-dark text-white px-4 py-2 rounded-t-md">
                <h3 class="text-lg font-semibold m-0">Data Nasabah</h3>
            </div>
            <div class="p-4">
                <p><strong>Nama Nasabah:</strong> {{ $barangGadai->nasabah->nama }}</p>
                <p><strong>NIK:</strong> {{ $barangGadai->nasabah->nik }}</p>
                <p><strong>Alamat:</strong> {{ $barangGadai->nasabah->alamat }}</p>
                <p><strong>No Telp:</strong> {{ $barangGadai->nasabah->telepon }}</p>
            </div>
        </div>

        <!-- Card Data Barang Gadai -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="bg-dark text-white px-4 py-2 rounded-t-md">
                <h3 class="text-lg font-semibold m-0">Data Barang Gadai</h3>
            </div>
            <div class="p-4">
                <p><strong>Nama Barang:</strong> {{ $barangGadai->nama_barang }}</p>
                <p><strong>No Bon:</strong> {{ $barangGadai->no_bon }}</p>
                <p><strong>Harga Gadai:</strong> Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</p>
                <p><strong>Tenor:</strong> {{ $barangGadai->tenor }} hari</p>
                <p><strong>Jatuh Tempo:</strong> {{ $barangGadai->tempo }}</p>
                <p><strong>Bunga:</strong> {{ $bungaPersen }}% (Rp {{ number_format($bunga, 0, ',', '.') }})</p>
                <p><strong>Telat:</strong> {{ $barangGadai->telat }} hari</p>
                <p><strong>Denda:</strong> Rp {{ number_format($denda, 0, ',', '.') }}</p>
                <p><strong>Total Perpanjang:</strong> <span class="text-success fw-bold">Rp {{ number_format($totalPerpanjang, 0, ',', '.') }}</span></p>
                <p><strong>Penerima Tebusan:</strong> {{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-col md:flex-row justify-between mt-6 gap-4">
        <div id="continue-payment-container"></div>

        <div class="flex gap-2">
            <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
            <input type="hidden" id="total-tebus-{{ $barangGadai->no_bon }}" value="{{ $totalTebus }}">
            <input type="hidden" id="denda-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->denda }}">

            <button id="confirmTebusBtn" class="btn btn-success">
                Tebus Sekarang
            </button>
            <button onclick="window.location.href='{{ route('profile') }}'" class="btn btn-danger">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Script tetap sama -->
@endsection

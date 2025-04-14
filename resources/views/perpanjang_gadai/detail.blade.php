@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Konfirmasi Perpanjangan Gadai</h4>

    {{-- Detail Nasabah --}}
    <div class="card mb-4">
        <div class="card-header">Detail Nasabah</div>
        <div class="card-body">
            <p><strong>ID Nasabah:</strong> {{ $nasabah->id_nasabah }}</p>
            <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
            <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
            <p><strong>No. HP:</strong> {{ $nasabah->no_hp }}</p>
        </div>
    </div>

    <div class="row">
        {{-- Bon Lama --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Bon Lama</div>
                <div class="card-body">
                    <p><strong>No Bon:</strong> {{ $lama->no_bon }}</p>
                    <p><strong>Nama Barang:</strong> {{ $lama->nama_barang }}</p>
                    <p><strong>Deskripsi:</strong> {{ $lama->deskripsi }}</p>
                    <p><strong>Harga Gadai:</strong> Rp{{ number_format($lama->harga_gadai, 0, ',', '.') }}</p>
                    <p><strong>Bunga:</strong> Rp{{ number_format($bunga_lama, 0, ',', '.') }}</p>
                    <p><strong>Denda Telat:</strong> Rp{{ number_format($denda_lama, 0, ',', '.') }}</p>
                    <p><strong>Total Tagihan Lama:</strong> <span class="text-danger fw-bold">Rp{{ number_format($total_lama, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>

        {{-- Bon Baru --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Bon Baru</div>
                <div class="card-body">
                    <p><strong>No Bon Baru:</strong> {{ $no_bon_baru }}</p>
                    <p><strong>Tenor:</strong> {{ $tenor }} hari</p>
                    <p><strong>Harga Gadai Baru:</strong> Rp{{ number_format($baru['harga_gadai'], 0, ',', '.') }}</p>
                    <p><strong>Bunga Baru:</strong> Rp{{ number_format($bunga_baru, 0, ',', '.') }}</p>
                    <p><strong>Jatuh Tempo Baru:</strong> {{ $baru['tempo'] }}</p>
                    <p><strong>Total Bon Baru:</strong> <span class="text-success fw-bold">Rp{{ number_format($total_baru, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan dan Tombol --}}
    <div class="card">
        <div class="card-body">
            <!-- <p><strong>Jenis Perpanjangan:</strong> 
                @if ($jenis_perpanjangan === 'tanpa_perubahan')
                    Tanpa Perubahan Harga
                @elseif ($jenis_perpanjangan === 'penambahan')
                    Penambahan Harga (Rp{{ number_format($nominal, 0, ',', '.') }})
                @else
                    Pengurangan Harga (Rp{{ number_format($nominal, 0, ',', '.') }})
                @endif
            </p> -->
            <p><strong>Jenis Perpanjangan:</strong> {{ $catatan }}</p>

            <h5>Total yang Harus Dibayar: 
                <span class="text-primary fw-bold">Rp{{ number_format($total_tagihan, 0, ',', '.') }}</span>
            </h5>

            <form action="{{ route('perpanjang_gadai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="no_bon_lama" value="{{ $lama->no_bon }}">
                <input type="hidden" name="no_bon_baru" value="{{ $no_bon_baru }}">
                <input type="hidden" name="tenor" value="{{ $tenor }}">
                <input type="hidden" name="jenis_perpanjangan" value="{{ $jenis_perpanjangan }}">
                <input type="hidden" name="nominal" value="{{ $nominal }}">

                <button type="submit" class="btn btn-success">Lanjutkan Perpanjangan</button>
                <a href="{{ route('perpanjang_gadai.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

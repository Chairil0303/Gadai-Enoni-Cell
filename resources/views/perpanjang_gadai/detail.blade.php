@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Konfirmasi Perpanjangan Gadai</h4>

    <div class="row">
        {{-- Data Nasabah --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">Data Nasabah</div>
                <div class="card-body">
                    <p><strong>ID Nasabah:</strong> {{ $nasabah->id_nasabah }}</p>
                    <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
                    <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
                    <p><strong>No. HP:</strong> {{ $nasabah->telepon }}</p>
                </div>
            </div>
        </div>

        {{-- Detail Bon Lama --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">Bon Lama</div>
                <div class="card-body">
                    <p><strong>No Bon:</strong> {{ $lama->no_bon }}</p>
                    <p><strong>Nama Barang:</strong> {{ $lama->nama_barang }}</p>
                    <p><strong>Tenor:</strong> {{ $lama->tenor }} hari</p>
                    <p><strong>Harga Gadai:</strong> Rp{{ number_format($lama->harga_gadai, 0, ',', '.') }}</p>
                    <p><strong>Bunga:</strong> Rp{{ number_format($bunga_lama, 0, ',', '.') }}</p>
                    <p><strong>Denda Telat:</strong> Rp{{ number_format($denda_lama, 0, ',', '.') }}</p>
                    <p><strong>Tempo:</strong> {{ $lama->tempo }}</p>
                    <p class="text-danger fw-bold"><strong>Total Tagihan Lama:</strong> Rp{{ number_format($total_lama, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>


        {{-- Detail Bon Baru --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">Bon Baru</div>
                <div class="card-body">
                    <p><strong>No Bon Baru:</strong> {{ $no_bon_baru }}</p>
                    <p><strong>Tenor:</strong> {{ $tenor }} hari</p>
                    <p><strong>Harga Gadai Baru:</strong> Rp{{ number_format($baru['harga_gadai'], 0, ',', '.') }}</p>
                    <p><strong>Bunga Baru:</strong> Rp{{ number_format($bunga_baru, 0, ',', '.') }}</p>
                    <p><strong>Jatuh Tempo Baru:</strong> {{ $baru['tempo'] }}</p>
                    <p class="text-success fw-bold"><strong>Total Bon Baru:</strong> Rp{{ number_format($total_baru, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan dan Tombol Aksi --}}
    <div class="card mt-4">
        <div class="card-body">
            <p>{{ $catatan }}</p>

            <h5>Total yang Harus Dibayar:</h5>
            <p><strong>Bunga Bon Lama:</strong> Rp{{ number_format($bunga_lama, 0, ',', '.') }}</p>

            @if($denda > 0)
                <p><strong>Denda (jika ada):</strong> Rp{{ number_format($denda, 0, ',', '.') }}</p>
            @endif

            <h5>Tagihan Bon Baru:</h5>
            <p><strong>Total Tagihan Bon Baru:</strong> Rp{{ number_format($total_baru, 0, ',', '.') }}</p>

            <form action="{{ route('perpanjang_gadai.store') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="harga_gadai" value="{{ $baru['harga_gadai'] }}">
                <input type="hidden" name="bunga" value="{{ $bunga_baru }}">
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

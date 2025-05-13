@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="mb-4 fw-semibold text-success"><i class="fas fa-check-circle me-1"></i> Konfirmasi Perpanjangan Gadai</h4>

    <div class="row">
        {{-- Data Nasabah --}}
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header bg-success text-white fw-semibold rounded-top-4">
                    Data Nasabah
                </div>
                <div class="card-body">
                    <p><strong>ID Nasabah:</strong> {{ $nasabah->id_nasabah }}</p>
                    <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
                    <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
                    <p><strong>No. HP:</strong> {{ $nasabah->telepon }}</p>
                </div>
            </div>
        </div>

        {{-- Bon Lama --}}
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header bg-success text-white fw-semibold rounded-top-4">
                    Bon Lama
                </div>
                <div class="card-body">
                    <p><strong>No Bon:</strong> {{ $lama->no_bon }}</p>
                    <p><strong>Nama Barang:</strong> {{ $lama->nama_barang }}</p>
                    <p><strong>Tenor:</strong> {{ $lama->bungaTenor->tenor }} hari</p>
                    <p><strong>Harga Gadai:</strong> Rp{{ number_format($lama->harga_gadai, 0, ',', '.') }}</p>
                    <p><strong>Bunga ({{ $lama->bungaTenor->bunga_percent }}%):</strong> Rp{{ number_format($bunga_lama, 0, ',', '.') }}</p>
                    <p><strong>Denda Telat:</strong> Rp{{ number_format($denda_lama, 0, ',', '.') }}</p>
                    <p><strong>Tempo:</strong> {{ $lama->tempo }}</p>
                    <p class="fw-bold text-danger"><strong>Total Tagihan Lama:</strong> Rp{{ number_format($total_lama, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Bon Baru --}}
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header bg-success text-white fw-semibold rounded-top-4">
                    Bon Baru
                </div>
                <div class="card-body">
                    <p><strong>No Bon Baru:</strong> {{ $no_bon_baru }}</p>
                    <p><strong>Tenor:</strong> {{ $tenor }} hari</p>

                    @if ($jenis_perpanjangan === 'penambahan')
                        <p><strong>Harga Penambahan:</strong> Rp{{ number_format($nominal, 0, ',', '.') }}</p>
                    @elseif ($jenis_perpanjangan === 'pengurangan')
                        <p><strong>Harga Pengurangan:</strong> Rp{{ number_format($nominal, 0, ',', '.') }}</p>
                    @endif

                    <p><strong>Harga Gadai Baru:</strong> Rp{{ number_format($baru['harga_gadai'], 0, ',', '.') }}</p>
                    <p><strong>Bunga Baru:</strong> Rp{{ number_format($bunga_baru, 0, ',', '.') }}</p>
                    <p><strong>Jatuh Tempo Baru:</strong> {{ $baru['tempo'] }}</p>
                    <p class="fw-bold text-success"><strong>Total Bon Baru:</strong> Rp{{ number_format($total_baru, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Pembayaran --}}
    <div class="card shadow-sm border-0 rounded-4 mt-3">
        <div class="card-body">
            <p class="mb-3">{{ $catatan }}</p>

            <h5 class="fw-semibold mb-2">Total yang Harus Dibayar:
                <span class="text-danger">Rp {{ number_format($bunga_lama + $denda_lama + $pengurangan, 0, ',', '.') }}</span>
            </h5>

            <p><strong>Bunga Bon Lama:</strong> Rp{{ number_format($bunga_lama, 0, ',', '.') }}</p>
            @if($denda > 0)
                <p><strong>Denda Telat:</strong> Rp{{ number_format($denda_lama, 0, ',', '.') }}</p>
            @endif

            <h5 class="mt-4">Tagihan Bon Baru:</h5>
            <p><strong>Total Tagihan Bon Baru:</strong> Rp{{ number_format($total_baru, 0, ',', '.') }}</p>

            {{-- Tombol Aksi --}}
            <form action="{{ route('perpanjang_gadai.store') }}" method="POST" class="mt-4 d-flex gap-3">
                @csrf
                <input type="hidden" name="harga_gadai" value="{{ $baru['harga_gadai'] }}">
                <input type="hidden" name="bunga" value="{{ $bunga_baru }}">
                <input type="hidden" name="no_bon_lama" value="{{ $lama->no_bon }}">
                <input type="hidden" name="no_bon_baru" value="{{ $no_bon_baru }}">
                <input type="hidden" name="tenor" value="{{ $tenor }}">
                <input type="hidden" name="jenis_perpanjangan" value="{{ $jenis_perpanjangan }}">
                <input type="hidden" name="nominal" value="{{ $nominal }}">

                <button type="submit" class="btn btn-success shadow-sm px-4">
                    <i class="fas fa-check-circle me-1"></i> Lanjutkan Perpanjangan
                </button>
                <a href="{{ route('perpanjang_gadai.create') }}" class="btn btn-secondary shadow-sm px-4">
                    <i class="fas fa-times-circle me-1"></i> Batal
                </a>
            </form>
        </div>
    </div>

</div>
@endsection

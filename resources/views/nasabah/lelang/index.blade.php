@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Barang Lelang</h1>

    <div class="row">
        @foreach($barangLelang as $barang)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $barang->foto_barang) }}" class="card-img-top" alt="Foto Barang">
                    <div class="card-body">
                        <h5 class="card-title">{{ $barang->barangGadai->nama_barang }}</h5>
                        <p class="card-text">{{ $barang->kondisi_barang }}</p>
                        <p class="card-text">Harga Lelang: Rp {{ number_format($barang->harga_lelang, 0, ',', '.') }}</p>
                        <a href="#" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

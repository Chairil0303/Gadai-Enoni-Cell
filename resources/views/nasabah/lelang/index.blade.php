@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Barang Lelang</h1>

    <div class="row">
        @foreach($barangLelang as $barang)
            <div class="col-md-4 mb-4">
                <div class="card shadow">
                    <div style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        <img
                            src="{{ asset('storage/' . json_decode($barang->foto_barang)[0]) }}"
                            class="card-img-top img-fluid"
                            alt="Foto Barang"
                            style="height: 100%; width: auto; object-fit: cover;"
                        >
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $barang->barangGadai->nama_barang }}</h5>
                        <p class="card-text">{{ $barang->kondisi_barang }}</p>
                        <p class="card-text">Harga Lelang: Rp {{ number_format($barang->harga_lelang, 0, ',', '.') }}</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#barangLelangModal{{ $barang->id }}">Lihat Detail</button>
                    </div>
                </div>
            </div>

            <!-- Modal untuk menampilkan detail barang lelang -->
            <div class="modal fade" id="barangLelangModal{{ $barang->id }}" tabindex="-1" aria-labelledby="barangLelangModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="barangLelangModalLabel">Detail Barang Lelang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h5>{{ $barang->barangGadai->nama_barang }}</h5>
                            <p><strong>Kondisi:</strong> {{ $barang->kondisi_barang }}</p>
                            <p><strong>Keterangan:</strong> {{ $barang->keterangan }}</p>
                            <p><strong>Harga Lelang:</strong> Rp {{ number_format($barang->harga_lelang, 0, ',', '.') }}</p>

                            <div class="row">
                                @foreach(json_decode($barang->foto_barang) as $foto)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ asset('storage/' . $foto) }}" class="img-fluid" alt="Foto Barang Lelang" style="width: 100%; height: 100%; object-fit: contain;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <a href="#" class="btn btn-primary">Ikut Lelang</a> <!-- Tombol untuk ikut lelang -->
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

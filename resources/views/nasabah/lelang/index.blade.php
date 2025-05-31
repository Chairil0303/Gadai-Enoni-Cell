@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('profile') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Daftar Barang Lelang</h1>
        <p class="lead text-muted">Temukan barang unik dengan harga terbaik</p>
    </div>

    @if($barangLelang->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-gavel fa-4x text-muted mb-3"></i>
            <h3 class="text-muted">Belum ada barang yang dilelang</h3>
            <p class="text-muted">Silakan cek kembali nanti untuk melihat barang lelang terbaru</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($barangLelang as $barang)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm hover-shadow transition-all">
                        <div class="position-relative">
                            <div style="height: 250px; overflow: hidden;">
                                <img
                                    src="{{ asset('storage/' . json_decode($barang->foto_barang)[0]) }}"
                                    class="card-img-top img-fluid h-100 w-100"
                                    alt="Foto Barang"
                                    style="object-fit: cover;"
                                >
                            </div>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-primary">Lelang</span>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-2">{{ $barang->barangGadai->nama_barang }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ $barang->kondisi_barang }}
                            </p>
                            <p class="card-text fw-bold text-primary mb-3">
                                Rp {{ number_format($barang->harga_lelang, 0, ',', '.') }}
                            </p>
                            <button class="btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#barangLelangModal{{ $barang->id }}">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk menampilkan detail barang lelang -->
                <div class="modal fade" id="barangLelangModal{{ $barang->id }}" tabindex="-1" aria-labelledby="barangLelangModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="barangLelangModalLabel">
                                    <i class="fas fa-gavel me-2"></i>Detail Barang Lelang
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="carousel{{ $barang->id }}" class="carousel slide mb-4" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach(json_decode($barang->foto_barang) as $index => $foto)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <img src="{{ asset('storage/' . $foto) }}" class="d-block w-100 rounded" alt="Foto Barang Lelang" style="height: 300px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if(count(json_decode($barang->foto_barang)) > 1)
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $barang->id }}" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $barang->id }}" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="fw-bold mb-3">{{ $barang->barangGadai->nama_barang }}</h4>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Kondisi</h6>
                                            <p class="mb-0">{{ $barang->kondisi_barang }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Keterangan</h6>
                                            <p class="mb-0">{{ $barang->keterangan }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Harga Lelang</h6>
                                            <h4 class="text-primary fw-bold mb-0">Rp {{ number_format($barang->harga_lelang, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Tutup
                                </button>
                                <a href="#" class="btn btn-primary">
                                    <i class="fas fa-gavel me-1"></i> Ikut Lelang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection

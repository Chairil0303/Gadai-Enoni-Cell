@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Tombol Kembali ke Dashboard --}}
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5 mb-4">
            <div class="card shadow h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title"><i class="fas fa-box-open fa-lg mb-3"></i><br>Data Barang Telat</h5>
                    <p class="card-text text-muted">Lihat barang yang telah melewati masa tempo dan belum dilelang.</p>
                    <a href="{{ route('lelang.index') }}" class="btn btn-success mt-3">Lihat Barang Telat</a>
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-4">
            <div class="card shadow h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title"><i class="fas fa-gavel fa-lg mb-3"></i><br>Barang Sudah Dilelang</h5>
                    <p class="card-text text-muted">Lihat daftar barang yang telah masuk ke dalam proses lelang.</p>
                    <a href="{{ route('admin.barang-lelang') }}" class="btn btn-dark mt-3">Lihat Barang Lelang</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

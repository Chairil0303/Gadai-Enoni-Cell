@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Laporan</h1>
        <p class="text-muted">Silakan pilih jenis laporan yang ingin Anda lihat</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 mb-4 hover-shadow">
                <div class="card-body text-center">
                    <h3 class="card-title mb-3">📅 Laporan Harian</h3>
                    <p class="card-text text-muted">Lihat data aktivitas harian secara rinci.</p>
                    <a href="{{ route('admin.laporan.show', ['laporan' => 'harian']) }}" class="btn btn-primary">
                        Lihat Laporan Harian
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-lg border-0 mb-4 hover-shadow">
                <div class="card-body text-center">
                    <h3 class="card-title mb-3">📊 Laporan Bulanan</h3>
                    <p class="card-text text-muted">Ringkasan data bulanan untuk analisis jangka panjang.</p>
                    <a href="{{ route('admin.laporan.show', ['laporan' => 'bulanan']) }}" class="btn btn-secondary">
                        Lihat Laporan Bulanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

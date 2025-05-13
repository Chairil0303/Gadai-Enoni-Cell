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
                    <p class="mb-5 card-text text-muted">Lihat data aktivitas harian secara rinci.</p>

                    <form action="{{ route('admin.laporan.show', ['laporan' => 'harian']) }}" method="GET" class="row g-2 justify-content-center">
                        <div class="col-8">
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-success w-100">Lihat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-lg border-0 mb-4 hover-shadow">
                <div class="card-body text-center">
                    <h3 class="card-title mb-3">📊 Laporan Bulanan</h3>
                    <p class="mb-4 card-text text-muted">Ringkasan data bulanan untuk analisis jangka panjang.</p>

                    <!-- <form action="{{ route('admin.laporan.show', ['laporan' => 'bulanan']) }}" method="GET" class="row g-2 justify-content-center"> -->
                    <form action="{{ route('admin.laporan.filter', ['jenis' => 'bulanan']) }}" method="GET"  class="row g-2 justify-content-center">
                        <div class="col-8">
                            <input type="month" name="bulan" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-success w-100">Lihat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

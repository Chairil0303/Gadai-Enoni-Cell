@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-file-alt"></i> Menu Laporan</h4>
        </div>
        <div class="card-body bg-light">
            <div class="row g-4">
                <div class="col-md-6">
                    <a href="{{ route('admin.laporan.harian') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4 bg-success text-white text-center p-4">
                            <div class="mb-2">
                                <i class="fas fa-calendar-day fa-2x"></i>
                            </div>
                            <h5 class="fw-bold">Laporan Harian</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.laporan.bulanan') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4 bg-success text-white text-center p-4">
                            <div class="mb-2">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <h5 class="fw-bold">Laporan Bulanan</h5>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.laporan.keuangan') }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4 bg-success text-white text-center p-4">
                            <div class="mb-2">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                            <h5 class="fw-bold">Laporan Keuangan</h5>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

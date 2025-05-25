@extends('layouts.app')

@section('styles')
<style>
    .card-hover:hover {
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        transform: translateY(-5px);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    tbody tr:hover {
        background-color: #d4edda;
        transition: background-color 0.3s ease;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h4 class="mb-4 text-success"><i class="fas fa-user-cog"></i> Dashboard Staff</h4>

    {{-- Statistik Hari Ini --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-success">Transaksi Hari Ini</h6>
                    <h3>{{ $transaksiHariIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-success">Total Uang Masuk</h6>
                    <h3>Rp {{ number_format($totalMasukHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-danger shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-danger">Total Uang Keluar</h6>
                    <h3>Rp {{ number_format($totalKeluarHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Barang Mendekati Jatuh Tempo --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Barang Mendekati Jatuh Tempo (5 Hari Ke Depan)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-warning text-center">
                        <tr>
                            <th>No Bon</th>
                            <th>Nama Barang</th>
                            <th>Nasabah</th>
                            <th>Tanggal Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mendekatiTempo as $item)
                        <tr>
                            <td>{{ $item->no_bon }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ optional($item->nasabah)->nama ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tempo)->format('d-m-Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Tidak ada data barang mendekati tempo.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Grafik Transaksi --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Grafik Transaksi 7 Hari Terakhir</h5>
        </div>
        <div class="card-body">
            <canvas id="chartStaff" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartStaff').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah Transaksi',
                data: {!! json_encode($chartData) !!},
                borderColor: 'rgba(40, 167, 69, 1)',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

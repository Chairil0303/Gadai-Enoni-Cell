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

    <h4 class="mb-4 text-success"><i class="fas fa-tachometer-alt"></i> Dashboard Super Admin</h4>

    {{-- Grafik Transaksi & Pendapatan --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Grafik Transaksi & Pendapatan 7 Hari Terakhir</h5>
        </div>
        <div class="card-body">
            <canvas id="chartSuperAdmin" height="100"></canvas>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-success">Total Cabang</h6>
                    <h3>{{ $totalCabang }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-success">Total Gadai Aktif</h6>
                    <h3>{{ $totalGadaiAktif }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-success">Total Nilai Gadai</h6>
                    <h3>Rp {{ number_format($totalNilaiGadai, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-success">Pendapatan Hari Ini</h6>
                    <h3>Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi & Barang Populer --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h6 class="text-success">Transaksi Hari Ini</h6>
                    <h3>{{ $transaksiHariIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body">
                    <h6 class="text-success text-center mb-3">Barang Populer</h6>
                    <ul class="list-group list-group-flush">
                        @forelse($barangPopuler as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item->nama_barang }}
                            <span class="badge bg-success rounded-pill">{{ $item->total }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">Tidak ada data barang populer.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 5 Cabang --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-building"></i> Top 5 Cabang Berdasarkan Pendapatan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>ID Cabang</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topCabang as $cabang)
                        <tr>
                            <td>{{ $cabang->id_cabang }}</td>
                            <td>Rp {{ number_format($cabang->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">Tidak ada data cabang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartSuperAdmin').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Transaksi',
                    data: {!! json_encode($chartTransaksi) !!},
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Pendapatan',
                    data: {!! json_encode($chartPendapatan) !!},
                    borderColor: 'rgba(255, 193, 7, 1)',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            stacked: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

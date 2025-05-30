@extends('layouts.app')

@section('styles')
<style>
    /* Hover efek card */
    .card-hover:hover {
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        transform: translateY(-5px);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    /* Hover efek baris tabel */
    tbody tr:hover {
        background-color: #d4edda;
        transition: background-color 0.3s ease;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">

    <h4 class="mb-4 text-success"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h4>

    {{-- Grafik Transaksi & Pendapatan --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Grafik Transaksi & Pendapatan 7 Hari Terakhir</h5>
        </div>
        <div class="card-body">
            <canvas id="dashboardChart" height="100"></canvas>
        </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-history"></i> Aktivitas Terbaru Cabang</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-info text-center">
                        <tr>
                            <th>Nama</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aktivitasTerbaru as $aktivitas)
                        <tr>
                            <td>{{ $aktivitas->nama }}</td>
                            <td>{{ $aktivitas->deskripsi ?? $aktivitas->aksi }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($aktivitas->waktu_aktivitas)->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Tidak ada aktivitas terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Statistik Utama --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">Total Gadai Aktif</h5>
                    <h3>{{ $totalGadaiAktif }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">Total Nilai Gadai</h5>
                    <h3>Rp {{ number_format($totalNilaiGadai, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">Jumlah Barang Gadai</h5>
                    <h3>{{ $jumlahBarangGadai }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Hari Ini --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">Transaksi Hari Ini</h5>
                    <h3>{{ $transaksiHariIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-success shadow h-100 card-hover">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">Pendapatan Hari Ini</h5>
                    <h3>Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Barang Gadai Populer --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-box"></i> Barang Gadai Populer</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangPopuler as $barang)
                        <tr>
                            <td>{{ $barang->nama_barang }}</td>
                            <td class="text-center">{{ $barang->total }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">Tidak ada data barang populer.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daftar Staff Cabang --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-users-cog"></i> Daftar Staff Cabang</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staffCabang as $staff)
                        <tr>
                            <td>{{ $staff->nama }}</td>
                            <td class="text-center">{{ $staff->role }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">Tidak ada staff di cabang ini.</td>
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
    const ctx = document.getElementById('dashboardChart').getContext('2d');

    const labels = {!! json_encode($chartLabels) !!};
    const transaksiData = {!! json_encode($chartTransaksi) !!};
    const pendapatanData = {!! json_encode($chartPendapatan) !!};

    const dashboardChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Transaksi',
                    data: transaksiData,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    tension: 0.3,
                    fill: true,
                },
                {
                    label: 'Pendapatan (Rp)',
                    data: pendapatanData,
                    borderColor: 'rgba(255, 193, 7, 1)',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y1',
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
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection

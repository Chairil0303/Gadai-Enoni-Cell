@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-chart-line"></i> Laporan Transaksi</h4>
        </div>
        <div class="card-body bg-light">
            {{-- Ringkasan --}}
            <div class="mb-4">
                <p class="mb-1 fw-semibold text-success">
                    <i class="fas fa-receipt"></i> Total Transaksi: {{ $laporan->count() }}
                </p>
                <p><strong>Total Uang Masuk:</strong> Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                <p><strong>Total Uang Keluar:</strong> Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                <hr>
                <p><strong>Saldo Awal Cabang:</strong> Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
                <p><strong>Saldo Akhir Cabang:</strong> <span class="fw-bold text-primary">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span></p>
            </div>

            {{-- Ringkasan per Jenis --}}
            <div class="mb-4">
                <h5 class="fw-semibold mb-2 text-success"><i class="fas fa-tags"></i> Ringkasan Transaksi per Jenis</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($ringkasanJenis as $jenis => $data)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst(str_replace('_', ' ', $jenis)) }}
                            <span>{{ $data['count'] }} transaksi &bull; Rp {{ number_format($data['total'], 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Tabel Transaksi --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm align-middle">
                    <thead class="table-success text-center">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nasabah</th>
                            <th>Jenis Transaksi</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $item->nasabah->nama ?? '-' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $item->jenis_transaksi)) }}</td>
                                <td class="text-end">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="{{ route('superadmin.laporan.index') }}" class="btn btn-outline-secondary shadow-sm rounded-3">
                        <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

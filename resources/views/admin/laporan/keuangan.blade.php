@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-wallet"></i> Laporan Keuangan - {{ $cabang->nama_cabang }}</h4>
        </div>
        <div class="card-body bg-light">
            {{-- Ringkasan Keuangan --}}
            <div class="mb-4">
                <p><strong>Saldo Awal:</strong> Rp {{ number_format($saldo->saldo_awal, 0, ',', '.') }}</p>
                <p><strong>Kas Masuk:</strong> <span class="text-success fw-semibold">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span></p>
                <p><strong>Kas Keluar:</strong> <span class="text-danger fw-semibold">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span></p>
                <p><strong>Saldo Saat Ini:</strong> <span class="fw-bold text-primary">Rp {{ number_format($saldo->saldo_saat_ini - $totalKeluar, 0, ',', '.') }}</span></p>
            </div>

            {{-- Tabel Transaksi --}}
            @if ($transaksi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Transaksi</th>
                                <th>Arus Kas</th>
                                <th class="text-end">Jumlah</th>
                                <th>Nasabah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi as $t)
                            <tr>
                                <td>{{ $t->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $t->jenis_transaksi }}</td>
                                <td class="text-center {{ $t->arus_kas === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ ucfirst($t->arus_kas) }}
                                </td>
                                <td class="text-end">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                                <td>{{ $t->nasabah->nama ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Tidak ada transaksi yang tersedia.
                </div>
            @endif

            {{-- Tombol Kembali --}}
            <div class="mt-4 text-end">
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary shadow-sm rounded-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

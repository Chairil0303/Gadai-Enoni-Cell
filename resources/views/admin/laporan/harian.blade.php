@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-calendar-day"></i> Laporan Harian</h4>
        </div>
        <div class="card-body bg-light">
            
            {{-- Form Pilih Tanggal --}}
            <form method="GET" action="{{ route('admin.laporan.harian') }}" class="mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="tanggal" class="col-form-label fw-semibold">Pilih Tanggal:</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                               class="form-control form-control-sm" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success btn-sm shadow-sm">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Tampilkan Hasil --}}
            @isset($transaksi)
                @if ($transaksi->count() > 0)
                    {{-- Ringkasan --}}
                    <div class="mb-3">
                        <p class="fw-semibold text-success">
                            <i class="fas fa-receipt"></i> Total Transaksi: {{ $transaksi->count() }}
                        </p>
                        <p><strong>Total Uang Masuk:</strong> Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                        <p><strong>Total Uang Keluar:</strong> Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                    </div>

                    {{-- Tabel --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm align-middle">
                            <thead class="table-success text-center">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Penerima</th>
                                    <th>No Bon</th>
                                    <th>Nasabah</th>
                                    <th>Jenis Transaksi</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $item)
                                    <tr>
                                        <td>{{ $item->created_at->format('H:i') }}</td>
                                        <td>{{ $item->user->nama ?? '-' }}</td>
                                        <td>{{ $item->no_bon }}</td>
                                        <td>{{ $item->nasabah->nama ?? '-' }}</td>
                                        <td>{{ ucfirst($item->jenis_transaksi) }}</td>
                                        <td class="text-end">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Tidak ada transaksi pada tanggal ini.
                    </div>
                @endif
            @endisset

            <div class="mt-4 text-end">
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary shadow-sm rounded-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Laporan
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

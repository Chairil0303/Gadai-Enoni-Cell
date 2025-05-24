@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-calendar-alt"></i> Laporan Bulanan</h4>
        </div>
        <div class="card-body bg-light">

            {{-- Form Filter Bulan --}}
            <form method="GET" action="{{ route('admin.laporan.bulanan') }}" class="mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="bulan" class="col-form-label fw-semibold">Pilih Bulan:</label>
                    </div>
                    <div class="col-auto">
                        <input type="month" name="bulan" id="bulan" value="{{ request('bulan') }}"
                               class="form-control form-control-sm" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success btn-sm shadow-sm">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </form>

            {{-- Ringkasan dan Tabel --}}
            @isset($transaksi)
                @if ($transaksi->count() > 0)
                    {{-- Ringkasan --}}
                    <div class="mb-3">
                        <p class="fw-semibold text-success">
                            <i class="fas fa-receipt"></i> Total Transaksi: {{ $transaksi->count() }}
                        </p>
                        <p><strong>Saldo Awal Cabang Bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}:</strong> Rp {{ number_format($saldoAwalBulan, 0, ',', '.') }}</p>
                        <p><strong>Total Uang Masuk:</strong> Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                        <p><strong>Total Uang Keluar:</strong> Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                        <p><strong>Saldo Akhir Cabang Bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}:</strong> <span class="fw-bold text-primary">Rp {{ number_format($saldoAkhirBulan, 0, ',', '.') }}</span></p>
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
                                    <th>No Bon</th>
                                    <th>Nasabah</th>
                                    <th>Jenis Transaksi</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $item)
                                    <tr>
                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $item->no_bon }}</td>
                                        <td>{{ $item->nasabah->nama ?? '-' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $item->jenis_transaksi)) }}</td>
                                        <td class="text-end">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle"></i> Tidak ada transaksi pada bulan ini.
                    </div>
                @endif
            @endisset

        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">📅 Laporan Harian - {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h2>

    @if ($transaksi->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>No Bon</th>
                        <th>Nasabah</th>
                        <th>Jenis Transaksi</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $i => $trx)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $trx->no_bon }}</td>
                            <td>{{ $trx->nasabah->nama }}</td>
                            <td>{{ ucfirst($trx->jenis_transaksi) }}</td>
                            <td>{{ $trx->barangGadai->nama_barang ?? '-' }}</td>
                            <td>Rp {{ number_format($trx->jumlah_transaksi, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">Tidak ada transaksi pada tanggal ini.</div>
    @endif

    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary mt-3">← Kembali</a>
</div>
@endsection

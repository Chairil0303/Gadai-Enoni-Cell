@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📊 Laporan Bulanan - {{ $bulan }}</h2>

    @if($transaksi->isEmpty())
        <div class="alert alert-info">Tidak ada transaksi pada bulan ini.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Cabang</th>
                    <th>Jenis Transaksi</th>
                    <th>Arah</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>{{ $item->cabang->nama ?? '-' }}</td>
                        <td>{{ $item->jenis_transaksi }}</td>
                        <td>{{ ucfirst($item->arah) }}</td>
                        <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

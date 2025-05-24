@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Keuangan - {{ $cabang->nama_cabang }}</h2>

    <div class="mb-4">
        <strong>Saldo Awal:</strong> Rp{{ number_format($saldo->saldo_awal, 2, ',', '.') }}<br>
        <strong>Kas Masuk:</strong> Rp{{ number_format($totalMasuk, 2, ',', '.') }}<br>
        <strong>Kas Keluar:</strong> Rp{{ number_format($totalKeluar, 2, ',', '.') }}<br>
        <strong>Saldo Saat Ini:</strong> <span class="text-success">Rp{{ number_format($saldo->saldo_saat_ini, 2, ',', '.') }}</span>
    </div>

    <h4>Riwayat Transaksi</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis Transaksi</th>
                <th>Arus Kas</th>
                <th>Jumlah</th>
                <th>Nasabah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td>{{ $t->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $t->jenis_transaksi }}</td>
                <td class="{{ $t->arus_kas === 'masuk' ? 'text-success' : 'text-danger' }}">
                    {{ ucfirst($t->arus_kas) }}
                </td>
                <td>Rp{{ number_format($t->jumlah, 2, ',', '.') }}</td>
                <td>{{ $t->nasabah->nama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

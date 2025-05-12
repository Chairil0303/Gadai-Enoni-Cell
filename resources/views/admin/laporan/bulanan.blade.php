@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">TRANSAKSI GADAI - {{ $bulan }}</h2>

    <table class="table table-bordered text-center" style="background-color: #339933; color: white;">
        <thead>
            <tr >
                <th  class="text-white" style="background-color: #228B22;">Jenis Trx</th>
                <th  class="text-white" style="background-color: #228B22;">Jlh Trx</th>
                <th  class="text-white" style="background-color: #228B22;">Keluar</th>
                <th  class="text-white" style="background-color: #228B22;">Masuk</th>
            </tr>
        </thead>
        <tbody style="background-color: #ccffcc; color: black;">
        @php
            $grouped = $transaksi->groupBy('jenis_transaksi');
            $totalKeluar = 0;
            $totalMasuk = 0;

            $jenisTransaksiMap = [
                'terima_gadai' => 'Terima Gadai',
                'perpanjang_gadai' => 'Perpanjang Gadai',
                'tebus_gadai' => 'Tebus Gadai',
                'terima_jual' => 'Terima Jual',
                'lelangan_laku' => 'Lelangan Laku',
            ];
        @endphp

        @foreach($jenisTransaksiMap as $key => $label)
            @php
                $items = $grouped->get($key, collect());
                $jumlah = $items->count();
                $keluar = $items->where('arah', 'keluar')->sum('nominal');
                $masuk = $items->where('arah', 'masuk')->sum('nominal');
                $totalKeluar += $keluar;
                $totalMasuk += $masuk;
            @endphp
            <tr>
                <td>{{ $label }}</td>
                <td>{{ $jumlah }} Trx</td>
                <td>Rp {{ number_format($keluar, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($masuk, 0, ',', '.') }}</td>
            </tr>
        @endforeach

            <tr style="background-color: #339933; color: white; font-weight: bold;">
                <td>TOTAL</td>
                <td>{{ $transaksi->count() }} Trx</td>
                <td>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

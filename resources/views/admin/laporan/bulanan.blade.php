@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Laporan Bulanan - Transaksi Gadai</h2>
        <p class="text-muted">Rekap transaksi selama bulan berjalan</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered shadow-sm">
            <thead class="table-light text-center align-middle">
                <tr class="table-primary">
                    <th rowspan="2">Jenis Trx</th>
                    <th rowspan="2">Total Transaksi</th>
                    <th colspan="2">Keluar</th>
                    <th colspan="2">Masuk</th>
                </tr>
                <tr class="table-secondary">
                    <th>Rp</th>
                    <th></th>
                    <th>Rp</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td>Terima Gadai</td>
                    <td>15 Trx</td>
                    <td>12.500.000,-</td>
                    <td></td>
                    <td>0,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Perpanjang Gadai</td>
                    <td>8 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>1.120.000,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Tebus Gadai</td>
                    <td>14 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>9.450.000,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Terima Jual</td>
                    <td>2 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>850.000,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Lelangan Laku</td>
                    <td>1 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>300.000,-</td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="table-success fw-bold text-center">
                    <td>TOTAL</td>
                    <td>40 Trx</td>
                    <td>12.500.000,-</td>
                    <td></td>
                    <td>11.720.000,-</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

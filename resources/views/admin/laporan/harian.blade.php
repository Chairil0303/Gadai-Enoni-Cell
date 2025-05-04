@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Laporan Harian - Transaksi Gadai</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered shadow-sm">
            <thead class="table-light text-center align-middle">
                <tr class="table-primary">
                    <th rowspan="2">Jenis Trx</th>
                    <th rowspan="2">Jlh Trx</th>
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
                    <td>1 Trx</td>
                    <td>1.000.000,-</td>
                    <td></td>
                    <td>0,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Perpanjang Gadai</td>
                    <td>0 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>140.000,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Tebus Gadai</td>
                    <td>6 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>4.280.000,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Terima Jual</td>
                    <td>0 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>0,-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Lelangan Laku</td>
                    <td>0 Trx</td>
                    <td>0,-</td>
                    <td></td>
                    <td>0,-</td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="table-success fw-bold text-center">
                    <td>TOTAL</td>
                    <td>8 Trx</td>
                    <td>1.000.000,-</td>
                    <td></td>
                    <td>4.420.000,-</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

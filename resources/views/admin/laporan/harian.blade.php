@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Laporan Harian</h1>
    <table class="w-full table-auto border-collapse border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Jenis Transaksi</th>
                <th class="border p-2">Jumlah Transaksi</th>
                <th class="border p-2">Total Keluar</th>
                <th class="border p-2">Total Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td class="border p-2">{{ ucwords(str_replace('_', ' ', $row->jenis_transaksi)) }}</td>
                    <td class="border p-2">{{ $row->jumlah }} trx</td>
                    <td class="border p-2 text-red-600">
                        {{ number_format($row->total_keluar, 0, ',', '.') }}
                    </td>
                    <td class="border p-2 text-green-600">
                        {{ number_format($row->total_masuk, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center border p-4 text-gray-500">Belum ada data transaksi hari ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

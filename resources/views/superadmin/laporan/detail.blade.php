@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Laporan Transaksi</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Nasabah</th>
                    <th class="border px-4 py-2">Jenis Transaksi</th>
                    <th class="border px-4 py-2 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                        <td class="border px-4 py-2">{{ $item->nasabah->nama ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ ucfirst(str_replace('_', ' ', $item->jenis_transaksi)) }}</td>
                        <td class="border px-4 py-2 text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-center">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

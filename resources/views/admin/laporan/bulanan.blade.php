@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Laporan Bulanan</h2>

    {{-- Form Filter Bulan --}}
    <form method="GET" action="{{ route('admin.laporan.bulanan') }}" class="mb-4">
        <div class="flex items-center gap-2">
            <label for="bulan" class="font-semibold">Pilih Bulan:</label>
            <input type="month" name="bulan" id="bulan"
                value="{{ request('bulan') }}"
                class="border rounded px-2 py-1" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">
                Tampilkan
            </button>
        </div>
    </form>

    {{-- Tabel Hasil --}}
    @isset($transaksi)
        <div class="mt-6">
            <p class="mb-2 font-semibold">
                Total Transaksi: {{ $transaksi->count() }}
            </p>

            <table class="w-full text-left border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-2 py-1">Tanggal</th>
                        <th class="border px-2 py-1">No Bon</th>
                        <th class="border px-2 py-1">Nasabah</th>
                        <th class="border px-2 py-1">Jenis Transaksi</th>
                        <th class="border px-2 py-1">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi as $item)
                        <tr>
                            <td class="border px-2 py-1">{{ $item->created_at->format('d-m-Y') }}</td>
                            <td class="border px-2 py-1">{{ $item->no_bon }}</td>
                            <td class="border px-2 py-1">{{ $item->nasabah->nama ?? '-' }}</td>
                            <td class="border px-2 py-1 capitalize">{{ $item->jenis_transaksi }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-2">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endisset
</div>
@endsection

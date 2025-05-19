@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Laporan Harian</h2>

    {{-- Form Pilih Tanggal --}}
    <form method="GET" action="{{ route('admin.laporan.harian') }}" class="mb-4">
        <div class="flex items-center gap-2">
            <label for="tanggal" class="font-semibold">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal"
                value="{{ request('tanggal') }}"
                class="border rounded px-2 py-1" required>
            <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">
                Tampilkan
            </button>
        </div>
    </form>

    {{-- Tabel Hasil --}}
    @isset($transaksi)
        <div class="mt-6">
            @if ($transaksi->count() > 0)
                <p class="mb-2 font-semibold">
                    Total Transaksi: {{ $transaksi->count() }}
                </p>

                <table class="w-full text-left border-collapse border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-2 py-1">Waktu</th>
                            <th class="border px-2 py-1">Penerima</th>
                            <th class="border px-2 py-1">No Bon</th>
                            <th class="border px-2 py-1">Nasabah</th>
                            <th class="border px-2 py-1">Jenis Transaksi</th>
                            <th class="border px-2 py-1">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $item)
                            <tr>
                                <td class="border px-2 py-1">{{ $item->created_at->format('H:i') }}</td>
                                <td class="border px-2 py-1">{{ $item->user->nama ?? '-' }}</td>
                                <td class="border px-2 py-1">{{ $item->no_bon }}</td>
                                <td class="border px-2 py-1">{{ $item->nasabah->nama ?? '-' }}</td>
                                <td class="border px-2 py-1 capitalize">{{ $item->jenis_transaksi }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">Tidak ada transaksi pada tanggal ini.</p>
            @endif
        </div>
    @endisset
</div>
@endsection

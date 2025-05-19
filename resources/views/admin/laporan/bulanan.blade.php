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
            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.laporan.index') }}"
            class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600">
                ← Kembali
            </a>
        </div>
    </form>

    {{-- Tabel Hasil --}}
    @isset($transaksi)
        <div class="mt-6">
            <p class="mb-2 font-semibold">
                Total Transaksi: {{ $transaksi->count() }}
            </p>

            <p><strong>Total Uang Masuk:</strong> Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            <p><strong>Total Uang Keluar:</strong> Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>

            {{-- Ringkasan per Jenis Transaksi --}}
            <div class="mt-4">
                <h3 class="font-semibold mb-2">Ringkasan Transaksi per Jenis</h3>
                <ul class="list-disc ml-6 text-sm">
                    @foreach ($ringkasanJenis as $jenis => $data)
                        <li>{{ ucfirst(str_replace('_', ' ', $jenis)) }}: {{ $data['count'] }} transaksi, Total: Rp {{ number_format($data['total'], 0, ',', '.') }}</li>
                    @endforeach
                </ul>
            </div>

            <table class="w-full text-left border-collapse border mt-4">
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

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
                <th class="border p-2">Detail</th> <!-- Kolom detail -->
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
                    <td class="border p-2 text-center">
                        <button onclick="openModal('{{ $row->jenis_transaksi }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                            Lihat
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center border p-4 text-gray-500">Belum ada data transaksi hari ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
        <h2 class="text-xl font-bold mb-4">Detail Transaksi</h2>
        <div id="modalContent" class="space-y-2 text-sm">
            <!-- Diisi via JS -->
        </div>
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">✖</button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const transaksiData = @json($data);

    function openModal(jenis) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('modalContent');

        const trx = transaksiData.find(t => t.jenis_transaksi === jenis);
        if (!trx) return;

        content.innerHTML = `
            <p><strong>Jenis Transaksi:</strong> ${jenis.replace('_', ' ')}</p>
            <p><strong>Jumlah Transaksi:</strong> ${trx.jumlah}</p>
            <p><strong>Total Keluar:</strong> Rp ${trx.total_keluar.toLocaleString('id-ID')}</p>
            <p><strong>Total Masuk:</strong> Rp ${trx.total_masuk.toLocaleString('id-ID')}</p>
        `;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endpush

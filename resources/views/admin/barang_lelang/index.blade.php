{{-- resources/views/admin/barang_lelang/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Daftar Barang Lelang</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full border rounded bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">No Bon</th>
                    <th class="px-4 py-2 border">Nama Barang</th>
                    <th class="px-4 py-2 border">Cabang</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangLelang as $item)
                    <tr>
                        <td class="px-4 py-2 border">{{ $item->no_bon }}</td>
                        <td class="px-4 py-2 border">{{ $item->barangGadai->nama_barang }}</td>
                        <td class="px-4 py-2 border">{{ $item->cabang->nama_cabang ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            <span class="px-2 py-1 text-xs rounded bg-{{ $item->status_lelang === 'Terkonfirmasi' ? 'green' : 'yellow' }}-200 text-{{ $item->status_lelang === 'Terkonfirmasi' ? 'green' : 'yellow' }}-800">
                                {{ $item->status_lelang }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('admin.barang-lelang.edit', $item->id) }}" class="text-blue-600 hover:underline">
                                Edit / Konfirmasi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada barang lelang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

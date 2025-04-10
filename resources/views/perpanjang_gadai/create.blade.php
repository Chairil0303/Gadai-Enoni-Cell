@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-2 bg-green p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Perpanjang Gadai</h2>

    {{-- Form Proses No Bon --}}
    <form action="{{ route('perpanjang_gadai.proses') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold">No. Bon Lama</label>
            <input type="text" name="no_bon_lama" value="{{ old('no_bon_lama') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">No. Bon Baru</label>
            <input type="text" name="no_bon_baru" value="{{ old('no_bon_baru') }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Proses</button>
            <a href="{{ route('perpanjang_gadai.create') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>

    {{-- Jika ada data hasil proses --}}
    @isset($data)
        <div class="mt-8 border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Data Barang Gadai</h3>
            <p><strong>Nama Barang:</strong> {{ $data->nama_barang }}</p>
            <p><strong>Deskripsi:</strong> {{ $data->deskripsi }}</p>
            <p><strong>IMEI:</strong> {{ $data->imei }}</p>

            {{-- Form Simpan --}}
            <form action="{{ route('perpanjang_gadai.store') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="no_bon_lama" value="{{ $data->no_bon }}">
                <input type="hidden" name="no_bon_baru" value="{{ $no_bon_baru }}">

                <div class="mb-4">
                    <label class="block font-semibold">Pilih Tenor</label>
                    <select name="tenor" class="w-full border px-3 py-2 rounded" required>
                        <option value="7">7 Hari</option>
                        <option value="14">14 Hari</option>
                        <option value="30">30 Hari</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Harga Gadai Tambahan</label>
                    <input type="number" name="harga_gadai" class="w-full border px-3 py-2 rounded" required>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan Perpanjangan</button>
            </form>
        </div>
    @endisset
</div>
@endsection

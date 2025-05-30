{{-- resources/views/admin/barang_lelang/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4 max-w-3xl">
    <h1 class="text-xl font-bold mb-4">Konfirmasi Barang Lelang</h1>

    <form action="{{ route('admin.barang-lelang.update', $barangLelang->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-4 rounded shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium mb-1">Nama Barang</label>
            <input type="text" value="{{ $barangLelang->barangGadai->nama_barang }}" class="w-full p-2 border rounded bg-gray-100" disabled>
        </div>

        <div>
            <label for="deskripsi" class="block font-medium mb-1">Deskripsi Lelang</label>
            <textarea name="deskripsi" id="deskripsi" class="w-full p-2 border rounded" rows="4">{{ old('deskripsi', $barangLelang->deskripsi) }}</textarea>
        </div>

        <div>
            <label for="harga_lelang" class="block font-medium mb-1">Harga Lelang</label>
            <input type="number" name="harga_lelang" id="harga_lelang" value="{{ old('harga_lelang', $barangLelang->harga_lelang) }}" class="w-full p-2 border rounded">
        </div>

        <div>
            <label for="foto" class="block font-medium mb-1">Foto Barang</label>
            <input type="file" name="foto" id="foto" class="w-full p-2 border rounded">
        </div>

        <div class="text-right">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Konfirmasi</button>
        </div>
    </form>
</div>
@endsection

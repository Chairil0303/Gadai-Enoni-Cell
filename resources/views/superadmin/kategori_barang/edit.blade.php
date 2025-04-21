@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Edit Kategori Barang</h1>

    <form action="{{ route('superadmin.kategori-barang.update', $kategori_barang->id_kategori) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="w-full px-3 py-2 border rounded" value="{{ old('nama_kategori', $kategori_barang->nama_kategori) }}" required>
            @error('nama_kategori') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" class="w-full px-3 py-2 border rounded">{{ old('deskripsi', $kategori_barang->deskripsi) }}</textarea>
            @error('deskripsi') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-between">
            <a href="{{ route('superadmin.kategori-barang.index') }}" class="text-gray-600 hover:underline">← Kembali</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@endsection

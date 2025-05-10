@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Input Data Lelang - Barang: {{ $barang->nama_barang }} (No Bon: {{ $barang->no_bon }})</h4>

    <form action="{{ route('lelang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="barang_gadai_no_bon" value="{{ $barang->no_bon }}">

        <div class="mb-3">
            <label for="kondisi_barang" class="form-label">Kondisi Barang</label>
            <textarea name="kondisi_barang" class="form-control" rows="3" required>{{ old('kondisi_barang') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="harga_lelang" class="form-label">Harga Lelang (Opsional)</label>
            <input type="number" name="harga_lelang" class="form-control" value="{{ old('harga_lelang') }}">
        </div>

        <div class="mb-3">
            <label for="foto_barang" class="form-label">Upload Foto Barang (Opsional)</label>
            <input type="file" name="foto_barang" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

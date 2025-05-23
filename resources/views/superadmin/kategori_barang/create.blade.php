@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Tambah Kategori Barang</h4>
        </div>

        <div class="card-body bg-light">
            <form action="{{ route('superadmin.kategori-barang.store') }}" method="POST" class="px-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-success fw-semibold">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control rounded-3 shadow-sm"
                           value="{{ old('nama_kategori') }}" required>
                    @error('nama_kategori')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-success fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control rounded-3 shadow-sm" rows="4">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('superadmin.kategori-barang.index') }}" class="btn btn-outline-secondary rounded-3 shadow-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success rounded-3 shadow-sm px-4">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
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

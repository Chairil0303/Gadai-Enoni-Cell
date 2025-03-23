@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Cabang</h1>

    <form action="{{ route('superadmin.cabang.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Cabang</label>
            <input type="text" name="nama_cabang" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Kontak</label>
            <input type="text" name="kontak" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection

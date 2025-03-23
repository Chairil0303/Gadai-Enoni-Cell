@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Cabang</h1>


<form action="{{ route('superadmin.cabang.update', $cabang->id_cabang) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama Cabang</label>
        <input type="text" name="nama_cabang" value="{{ $cabang->nama_cabang }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" required>{{ $cabang->alamat }}</textarea>
    </div>

    <div class="form-group">
        <label>Kontak</label>
        <input type="text" name="kontak" value="{{ $cabang->kontak }}" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Update</button>
</form>

</div>
@endsection

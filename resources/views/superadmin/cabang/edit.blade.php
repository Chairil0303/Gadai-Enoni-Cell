@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-edit"></i> Edit Cabang</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.cabang.update', $cabang->id_cabang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="namaCabang" class="form-label"><i class="fas fa-building"></i> Nama Cabang</label>
                            <input type="text" autocomplete="off" class="form-control" id="namaCabang" name="nama_cabang" value="{{ $cabang->nama_cabang }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" required>{{ $cabang->alamat }}</textarea>
                            <div class="form-text">Pastikan alamat lengkap dan benar.</div>
                        </div>

                        <div class="mb-3">
                            <label for="kontak" class="form-label"><i class="fas fa-phone-alt"></i> Kontak</label>
                            <input type="text" autocomplete="off" class="form-control" id="kontak" name="kontak" value="{{ $cabang->kontak }}" required>
                            <div class="form-text">Gunakan format nomor yang valid.</div>
                        </div>

                        <div class="mb-3">
                            <label for="google_maps_link" class="form-label"><i class="fas fa-map-marked-alt"></i> Link Google Maps</label>
                            <input type="url" autocomplete="off" class="form-control" id="google_maps_link" name="google_maps_link" value="{{ $cabang->google_maps_link }}">
                            <div class="form-text">Tempelkan link Google Maps lokasi cabang jika ada.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('superadmin.cabang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Update Cabang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

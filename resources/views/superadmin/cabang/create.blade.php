@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="fas fa-plus-circle"></i> Tambah Cabang</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.cabang.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="namaCabang" class="form-label"><i class="fas fa-building"></i> Nama Cabang</label>
                            <input type="text"autocomplete="off" class="form-control" id="namaCabang" name="nama_cabang" placeholder="Masukkan nama cabang" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat cabang" required></textarea>
                            <div class="form-text">Pastikan alamat lengkap dan benar.</div>
                        </div>

                        <div class="mb-3">
                            <label for="kontak" class="form-label"><i class="fas fa-phone-alt"></i> Kontak</label>
                            <input type="text" autocomplete="off" class="form-control" id="kontak" name="kontak" placeholder="No Handphone" required>
                            <div class="form-text">Gunakan format nomor yang valid.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Simpan Cabang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

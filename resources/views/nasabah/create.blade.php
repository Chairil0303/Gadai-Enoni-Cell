@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-user-plus"></i> Tambah Nasabah</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.nasabah.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
    
                                <div class="mb-3">
                                    <label for="nama" class="form-label"><i class="fas fa-user"></i> Nama Lengkap</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                                </div>
        
                                <div class="mb-3">
                                    <label for="nik" class="form-label"><i class="fas fa-id-card"></i> NIK</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
                                </div>
        
                                <div class="mb-3">
                                    <label for="alamat" class="form-label"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" required></textarea>
                                </div>
        
                                <div class="mb-3">
                                    <label for="telepon" class="form-label"><i class="fas fa-phone-alt"></i> Telepon</label>
                                    <input type="text" autocomplete="off" class="form-control" id="telepon" name="telepon" placeholder="Masukkan nomor telepon" required>
                                </div>
    
                            </div>
                            <div class="col-md-6">
                                
                                <div class="mb-3">
                                    <label for="username" class="form-label"><i class="fas fa-user-circle"></i> Username</label>
                                    <input type="text" autocomplete="off" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                                </div>
        
                                <div class="mb-3">
                                    <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                                </div>
        
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="status_blacklist" name="status_blacklist">
                                    <label class="form-check-label" for="status_blacklist">Masukkan ke daftar blacklist</label>
                                </div>
        
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-save"></i> Simpan Nasabah
                                </button>
                                <a href="{{ route('superadmin.nasabah.index') }}" class="btn btn-secondary w-100 mt-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                
                            </div>    
                            
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

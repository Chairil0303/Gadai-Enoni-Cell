@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-white text-center">
                    <h4><i class="fas fa-edit"></i> Edit Nasabah</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.nasabah.update', $nasabah->id_nasabah) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                
                                <div class="mb-3">
                                    <label for="nama" class="form-label"><i class="fas fa-user"></i> Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $nasabah->nama }}" required>
                                </div>
        
                                <div class="mb-3">
                                    <label for="nik" class="form-label"><i class="fas fa-id-card"></i> NIK</label>
                                    <input type="text" class="form-control" id="nik" name="nik" value="{{ $nasabah->nik }}" required>
                                </div>
        
                                <div class="mb-3">
                                    <label for="alamat" class="form-label"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" required>{{ $nasabah->alamat }}</textarea>
                                </div>
        
                                <div class="mb-3">
                                    <label for="telepon" class="form-label"><i class="fas fa-phone-alt"></i> Telepon</label>
                                    <input type="text" class="form-control" id="telepon" name="telepon" value="{{ $nasabah->telepon }}" required>
                                </div>
        
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                <label for="status_blacklist" class="form-label"><i class="fas fa-ban"></i> Status Blacklist</label>
                                <select class="form-control" id="status_blacklist" name="status_blacklist">
                                <option value="0" {{ !$nasabah->status_blacklist ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ $nasabah->status_blacklist ? 'selected' : '' }}>Ya</option>
                                </select>
                            </div>
                                    
                            <div class="mb-3">
                                <label for="username" class="form-label"><i class="fas fa-user-circle"></i> Username</label>
                                <input type="text" class="form-control" id="username" autocomplete="off" name="username" value="{{ $nasabah->username }}" required>
                            </div>
                                    
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password (kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>


                            </div>

                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-save"></i> Update Nasabah
                        </button>
                        <a href="{{ route('superadmin.nasabah.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

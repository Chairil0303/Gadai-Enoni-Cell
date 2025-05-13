@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-user-plus"></i> Tambah Staff</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.store') }}" method="POST">
                        @csrf

                        <div class="row">

                                <div class="mb-3">
                                    <label for="nama" class="form-label"><i class="fas fa-user"></i> Nama</label>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="form-control" placeholder="Masukkan nama" required>
                                    @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label"><i class="fas fa-id-badge"></i> Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control" placeholder="Masukkan username" required>
                                    @error('username') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Masukkan email" required>
                                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                                </div>



                                <div class="d-flex justify-content-between mt-3">
                                    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm d-none d-sm-inline-block">
                                        <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm d-inline-block d-sm-none w-100">
                                        <i class="fas fa-arrow-left"></i>
                                    </a>

                                    <button type="submit" class="btn btn-primary btn-sm d-none d-sm-inline-block">
                                        <i class="fas fa-save"></i> <span class="d-none d-md-inline">Simpan Staff</span>
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm d-inline-block d-sm-none w-100">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
    });
</script>
@endif
@endpush
@endsection

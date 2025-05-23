@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-success">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h4 class="text-success mb-0"><i class="fas fa-user-edit"></i> Edit Staff</h4>
        <div></div>
    </div>

    <form action="{{ route('admin.staff.update', $staff->id_users) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $staff->nama) }}" class="form-control @error('nama') is-invalid @enderror">
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" value="{{ old('username', $staff->username) }}" class="form-control @error('username') is-invalid @enderror">
            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password (opsional)</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="mb-4">
            <label class="form-label">Cabang</label>
            <select name="id_cabang" class="form-select @error('id_cabang') is-invalid @enderror">
                <option value="">-- Pilih Cabang --</option>
                @foreach(\App\Models\Cabang::all() as $cabang)
                    <option value="{{ $cabang->id_cabang }}" {{ $staff->id_cabang == $cabang->id_cabang ? 'selected' : '' }}>
                        {{ $cabang->nama_cabang }}
                    </option>
                @endforeach
            </select>
            @error('id_cabang') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-success">
            <i class="fas fa-save"></i> Update
        </button>
    </form>
</div>
@endsection

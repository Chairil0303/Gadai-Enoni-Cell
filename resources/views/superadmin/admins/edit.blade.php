@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-edit"></i> Edit Admin</h4>
                </div>
                <div class="card-body">
                    <form id="form-admin" action="{{ route('superadmin.admins.update', $admin->id_users) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nama" class="form-label"><i class="fas fa-user"></i> Nama</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $admin->nama) }}"
                                   class="form-control" autocomplete="off" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}"
                                   class="form-control" autocomplete="off">
                        </div>

                        <div class="mb-4">
                            <label for="username" class="form-label"><i class="fas fa-user-tag"></i> Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username', $admin->username) }}"
                                   class="form-control" autocomplete="off" required>
                        </div>

                        <div class="mb-4">
                            <label for="id_cabang" class="form-label"><i class="fas fa-code-branch"></i> Cabang</label>
                            <select name="id_cabang" id="id_cabang" class="form-control">
                                <option value="">-- Pilih Cabang --</option>
                                @foreach ($cabangs as $cabang)
                                    <option value="{{ $cabang->id_cabang }}" {{ $admin->id_cabang == $cabang->id_cabang ? 'selected' : '' }}>
                                        {{ $cabang->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4" x-data="{ show: false, showConfirm: false }">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <label for="password" class="form-label"><i class="fas fa-lock"></i> Password Baru</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password"
                                       class="form-control pr-10">
                                <button type="button" @click="show = !show"
                                        class="absolute right-2 top-2 text-sm text-gray-600 focus:outline-none">
                                    <span x-text="show ? 'Sembunyikan' : 'Lihat'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4" x-data="{ showConfirm: false }">
                            @error('password_confirmation')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                                       class="form-control pr-10">
                                <button type="button" @click="showConfirm = !showConfirm"
                                        class="absolute right-2 top-2 text-sm text-gray-600 focus:outline-none">
                                    <span x-text="showConfirm ? 'Sembunyikan' : 'Lihat'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <a href="{{ route('superadmin.admins.index') }}" class="btn btn-secondary d-none d-sm-inline-block btn-sm">
                                <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                            </a>
                            <a href="{{ route('superadmin.admins.index') }}" class="btn btn-secondary d-inline-block d-sm-none btn-block btn-sm">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <button type="submit" class="btn btn-success d-none d-sm-inline-block btn-sm">
                                <i class="fas fa-save"></i> <span class="d-none d-md-inline">Simpan</span>
                            </button>
                            <button type="submit" class="btn btn-success d-inline-block d-sm-none btn-block btn-sm">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

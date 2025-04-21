@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-user-plus"></i> Tambah Admin</h4>
                </div>
                <div class="card-body">
                    <form id="form-admin" action="{{ route('superadmin.admins.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-user"></i> Nama</label>
                                    <input type="text" name="nama" class="form-control" autocomplete="off" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                    <input type="email" name="email" class="form-control" autocomplete="off">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-user-tag"></i> Username</label>
                                    <input type="text" name="username" class="form-control" autocomplete="off" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6 d-flex flex-column justify-content-between">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-code-branch"></i> Cabang</label>
                                    <select name="id_cabang" class="form-control" required>
                                        <option value="">-- Pilih Cabang --</option>
                                        @foreach($cabangs as $cabang)
                                            <option value="{{ $cabang->id_cabang }}"
                                                {{ (old('id_cabang', $idCabang ?? '') == $cabang->id_cabang) ? 'selected' : '' }}>
                                                {{ $cabang->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <a href="{{ route('superadmin.admins.index') }}" class="btn btn-secondary d-none d-sm-inline-block btn-sm">
                                        <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                    <a href="{{ route('superadmin.admins.index') }}" class="btn btn-secondary d-inline-block d-sm-none btn-block btn-sm">
                                        <i class="fas fa-arrow-left"></i>
                                    </a>
                                    <button type="button" id="btn-simpan" class="btn btn-success d-none d-sm-inline-block btn-sm">
                                        <i class="fas fa-save"></i> <span class="d-none d-md-inline">Simpan Admin</span>
                                    </button>
                                    <button type="button" id="btn-simpan" class="btn btn-success d-inline-block d-sm-none btn-block btn-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.querySelectorAll('#btn-simpan').forEach(function(btn) {
                            btn.addEventListener('click', function () {
                                Swal.fire({
                                    title: 'Simpan Data?',
                                    text: "Pastikan data admin sudah benar.",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#1a8754',
                                    cancelButtonColor: '#aaa',
                                    confirmButtonText: 'Ya, Simpan',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('form-admin').submit();
                                    }
                                });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

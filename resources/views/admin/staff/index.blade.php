@extends('layouts.app') 

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="d-inline text-success"><i class="fas fa-users-cog"></i> Daftar Staff</h4>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Staff
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0"><i class="fas fa-table"></i> Tabel Staff</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Cabang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staffs as $staff)
                        <tr>
                            <td>{{ $staff->nama }}</td>
                            <td>{{ $staff->username }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>{{ $staff->cabang->nama_cabang ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.staff.edit', $staff->id_users) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.staff.destroy', $staff->id_users) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus staff ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data staff.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="{{ route('dashboard') }}" class="mt-2 btn btn-outline-success mr-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

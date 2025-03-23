@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4><i class="fas fa-users"></i> Data Nasabah
                        <a href="{{ route('superadmin.nasabah.create') }}" class="btn btn-light btn-sm float-end">
                            <i class="fas fa-plus"></i> Tambah Nasabah
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Status Blacklist</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nasabah as $item)
                            <tr>
                                <td>{{ $item->id_nasabah }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->telepon }}</td>
                                <td>
                                    @if ($item->status_blacklist)
                                        <span class="badge bg-danger">Blacklisted</span>
                                    @else
                                        <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td>{{ $item->username }}</td>
                                <td>
                                    <a href="{{ route('superadmin.nasabah.edit', $item->id_nasabah) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('superadmin.nasabah.destroy', $item->id_nasabah) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('superadmin.cabang.store') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

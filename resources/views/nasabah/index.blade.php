@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-success"><i class="fas fa-users"></i> Data Nasabah</h4>
        {{-- <a href="{{ route('superadmin.nasabah.create') }}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Tambah Nasabah</a> --}}
    </div>

    <form method="GET" action="{{ route('superadmin.nasabah.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama/NIK/telepon..." value="{{ request('search') }}">
            <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div class="card shadow-lg">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0"><i class="fas fa-list"></i> Tabel Nasabah</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 text-center align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin() || auth()->user()->isStaf())
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nasabah as $item)
                            <tr>
                                <td>{{ $item->id_nasabah }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->telepon }}</td>
                                <td>
                                    <span class="badge {{ $item->status_blacklist ? 'bg-danger' : 'bg-success' }}">
                                        {{ $item->status_blacklist ? 'Blacklisted' : 'Aktif' }}
                                    </span>
                                </td>
                                @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin() || auth()->user()->isStaf())
                                <td>
                                    <a href="{{ route('superadmin.nasabah.edit', $item->id_nasabah) }}" class="btn btn-sm btn-outline-warning mb-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                                    <form action="{{ route('superadmin.nasabah.destroy', $item->id_nasabah) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger mb-1">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted">Belum ada data nasabah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

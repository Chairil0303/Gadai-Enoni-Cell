@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Cabang</h1>
        <a href="{{ route('superadmin.cabang.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Cabang
        </a>
    </div>

    @if (session('message'))
        <div class="alert alert-success shadow-sm">{{ session('message') }}</div>
    @endif

    <form method="GET" action="{{ route('superadmin.cabang.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" autocomplete="off" name="search" class="form-control" placeholder="Cari nama cabang..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered shadow-sm">
            <thead class="table-light">
                <tr>
                    <th>Nama Cabang</th>
                    <th>ID Cabang</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Lokasi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cabangs as $cabang)
                <tr>
                    <td>{{ $cabang->nama_cabang }}</td>
                    <td>{{ $cabang->id_cabang }}</td>
                    <td>{{ $cabang->alamat }}</td>
                    <td>{{ $cabang->kontak }}</td>
                    <td class="text-center">
                        @if($cabang->google_maps_link)
                            <a href="{{ $cabang->google_maps_link }}" target="_blank" class="text-success">
                                <i class="fas fa-map-marked-alt fa-lg"></i>
                            </a>
                        @else
                            <span class="text-muted">Tidak tersedia</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('superadmin.cabang.edit', $cabang->id_cabang) }}" class="btn btn-sm btn-success me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('superadmin.cabang.destroy', $cabang->id_cabang) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus cabang ini?')">
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
                    <td colspan="6" class="text-center text-muted">Tidak ada data cabang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $cabangs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

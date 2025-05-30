@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-users"></i> Data Nasabah</h4>
            {{-- Uncomment jika ingin tambah data --}}
            {{-- 
            <a href="{{ route('superadmin.nasabah.create') }}" class="btn btn-light btn-sm rounded-3">
                <i class="fas fa-plus"></i> Tambah Nasabah
            </a> 
            --}}
        </div>

        <div class="card-body bg-light rounded-bottom-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center shadow-sm">
                    <thead class="table-success text-dark">
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
                                @if ($item->status_blacklist)
                                    <span class="badge bg-danger">Blacklisted</span>
                                @else
                                    <span class="badge bg-success">Aktif</span>
                                @endif
                            </td>
                            @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin() || auth()->user()->isStaf())
                            <td>
                                <!-- Edit - semua role boleh -->
                                <a href="{{ route('superadmin.nasabah.edit', $item->id_nasabah) }}" class="btn btn-warning btn-sm rounded-3 me-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Hapus - hanya superadmin dan admin -->
                                @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                                <form action="{{ route('superadmin.nasabah.destroy', $item->id_nasabah) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-3">
                                        <i class="fas fa-trash"></i> Hapus
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

        <div class="card-footer text-end bg-white">
            <a href="{{ auth()->user()->isSuperadmin() ? route('dashboard.superadmin') : (auth()->user()->isAdmin() ? route('dashboard.admin') : route('dashboard.staff')) }}" class="btn btn-outline-secondary rounded-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

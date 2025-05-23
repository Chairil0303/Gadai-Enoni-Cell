@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-success"><i class="fas fa-building"></i> Daftar Cabang</h4>
        <a href="{{ route('superadmin.cabang.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Cabang
        </a>
    </div>

    @if (session('message'))
        <div class="alert alert-success shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <form method="GET" action="{{ route('superadmin.cabang.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama cabang..." value="{{ request('search') }}" autocomplete="off">
            <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div class="card shadow-lg">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0"><i class="fas fa-list"></i> Tabel Cabang</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Lokasi</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cabangs as $cabang)
                        <tr>
                            <td>{{ $cabang->nama_cabang }}</td>
                            <td>{{ $cabang->alamat }}</td>
                            <td>{{ $cabang->kontak }}</td>
                            <td class="text-center">
                                @if ($cabang->google_maps_link)
                                    <a href="{{ $cabang->google_maps_link }}" target="_blank" class="text-success" title="Lihat di Google Maps">
                                        <i class="fas fa-map-marked-alt fa-lg"></i>
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Tidak tersedia</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-success mb-1" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $cabang->id_cabang }}">
                                    <i class="fas fa-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="modalDetail{{ $cabang->id_cabang }}" tabindex="-1" aria-labelledby="modalLabel{{ $cabang->id_cabang }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content shadow">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="modalLabel{{ $cabang->id_cabang }}">Detail Cabang</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nama Cabang:</strong> {{ $cabang->nama_cabang }}</p>
                                        <p><strong>Alamat:</strong> {{ $cabang->alamat }}</p>
                                        <p><strong>Kontak:</strong> {{ $cabang->kontak }}</p>
                                        <p><strong>Google Maps:</strong>
                                            @if ($cabang->google_maps_link)
                                                <a href="{{ $cabang->google_maps_link }}" target="_blank">Lihat Lokasi</a>
                                            @else
                                                <em>Tidak tersedia</em>
                                            @endif
                                        </p>
                                        <p><strong>Dibuat pada:</strong> {{ $cabang->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <a href="{{ route('superadmin.cabang.edit', $cabang->id_cabang) }}" class="btn btn-success">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $cabang->id_cabang }}')">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>

                                        <form id="form-delete-{{ $cabang->id_cabang }}" action="{{ route('superadmin.cabang.destroy', $cabang->id_cabang) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $cabangs->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Cabang?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        });
    }
</script>
@endsection

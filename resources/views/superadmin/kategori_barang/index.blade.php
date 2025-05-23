@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-boxes"></i> Kategori Barang</h4>
            <a href="{{ route('superadmin.kategori-barang.create') }}" class="btn btn-light text-success fw-semibold shadow-sm">
                <i class="fas fa-plus-circle"></i> Tambah Kategori
            </a>
        </div>

        <div class="card-body bg-light">
            @if($kategori->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada data kategori.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategori as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_kategori }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('superadmin.kategori-barang.edit', $item->id_kategori) }}"
                                           class="btn btn-sm btn-outline-success me-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button onclick="confirmDelete('{{ route('superadmin.kategori-barang.destroy', $item->id_kategori) }}')"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary shadow-sm rounded-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Flash Success --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

{{-- Konfirmasi Hapus --}}
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endsection

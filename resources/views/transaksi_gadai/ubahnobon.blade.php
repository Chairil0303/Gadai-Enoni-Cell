@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4><i class="fas fa-box"></i> Update No Bon</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-400 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Cabang</th>
                                <th>No Bon</th>
                                <th>Kategori</th>
                                <th>Tipe Barang</th>
                                <th>Atas Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangGadai as $barang)
                            <tr>
                                <td>{{ $barang->cabang->nama_cabang }}</td>
                                <td>{{ $barang->no_bon }}</td>
                                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->nasabah->nama ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('barang_gadai.edit_nobon', $barang->no_bon) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $barang->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Barang -->
@foreach($barangGadai as $barang)
<div class="modal fade" id="detailModal{{ $barang->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $barang->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailModalLabel{{ $barang->id }}">
                    <i class="fas fa-eye"></i> Detail Barang Gadai
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <h5 class="mb-3">Informasi Bon Sekarang</h5>
                <table class="table table-borderless">
                    <tr><th>No Bon</th><td>{{ $barang->no_bon }}</td></tr>
                    <tr><th>Cabang</th><td>{{ $barang->cabang->nama_cabang ?? '-' }}</td></tr>
                    <tr><th>Nasabah</th><td>{{ $barang->nasabah->nama ?? '-' }}</td></tr>
                    <tr><th>Kategori</th><td>{{ $barang->kategori->nama_kategori ?? '-' }}</td></tr>
                    <tr><th>Nama Barang</th><td>{{ $barang->nama_barang }}</td></tr>
                    <tr><th>Tanggal Masuk</th><td>{{ $barang->created_at->format('d-m-Y') }}</td></tr>
                </table>

                <hr class="my-4">

                <h5 class="mb-3">Informasi Bon Lama</h5>
                <table class="table table-borderless">
                    <tr>
                        <th>No Bon Lama</th>
                        <td>{{ $barang->bonLama->no_bon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk Bon Lama</th>
                        <td>{{ optional($barang->bonLama)->created_at ? $barang->bonLama->created_at->format('d-m-Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td>{{ $barang->bonLama->nama_barang ?? '-' }}</td>
                    </tr>
                </table>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@endsection

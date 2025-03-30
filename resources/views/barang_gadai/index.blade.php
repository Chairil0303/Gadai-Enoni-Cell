@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4><i class="fas fa-box"></i> Data Barang Gadai
                        {{-- <a href="{{ route('barang_gadai.create') }}" class="btn btn-light btn-sm float-end">
                            <i class="fas fa-plus"></i> Tambah Barang Gadai
                        </a> --}}
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No Barang</th>
                                <th>Kategori</th>
                                <th>Tipe Barang</th>
                                <th>Atas Nama</th>
                                <th>IMEI</th>
                                <th>Deskripsi</th>
                                <th>Tenor</th>
                                <th>Tempo</th>
                                <th>Sisa Hari</th>
                                <th>Harga Gadai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangGadai as $barang)
                            <tr>
                                    <td>{{ $barang->id_barang }}</td>
                                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->nasabah->nama ?? '-' }}</td>
                                    <td>{{ $barang->imei ?? '-' }}</td>
                                    <td>{{ $barang->deskripsi }}</td>
                                    <td>{{ $barang->tenor }} hari</td>
                                    <td>{{ \Carbon\Carbon::parse($barang->tempo)->format('d, m, Y') }}</td>
                                    <td>
                                        @if($barang->telat >= 0)
                                            +{{ $barang->telat }}
                                        @else
                                            -{{ $barang->telat }}
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($barang->harga_gadai, 2, ',', '.') }}</td>
                                    <td>
                                        @if($barang->status === 'Ditebus')
                                            <span class="badge bg-success">Ditebus</span>
                                        @elseif($barang->status === 'Dilelang')
                                            <span class="badge bg-danger">Dilelang</span>
                                        @else
                                            <span class="badge bg-warning">Tergadai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('barang_gadai.edit', $barang->id_barang) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('barang_gadai.destroy', $barang->id_barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
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
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

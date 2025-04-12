@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4><i class="fas fa-box"></i> Data Barang Gadai</h4>
                </div>
                <div class="card-body">
                    <!-- @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-400 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No Bon</th>
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
                                @if (auth()->user()->isSuperadmin())
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangGadai as $barang)
                            <tr>
                                <td>{{ $barang->no_bon }}</td>
                                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->nasabah->nama ?? '-' }}</td>
                                <td>{{ $barang->imei ?? '-' }}</td>
                                <td>{{ $barang->deskripsi }}</td>
                                <td>{{ $barang->tenor }} hari</td>
                                <td>{{ \Carbon\Carbon::parse($barang->tempo)->format('d, m, Y') }}</td>
                                <td>
                                    @if($barang->telat > 0)
                                        <span style="color: red;">Telat {{ $barang->telat }} hari</span>
                                    @else
                                        <span style="color: black;">Sisa {{ $barang->sisa_hari }} hari</span>
                                    @endif
                                </td>



                                <td>Rp {{ number_format($barang->harga_gadai, 0, ',', '.') }}</td>
                                <td>
                                    @if($barang->status === 'Ditebus')
                                        <span class="badge bg-success">Ditebus</span>
                                    @elseif($barang->status === 'Dilelang')
                                        <span class="badge bg-danger">Dilelang</span>
                                    @else
                                        <span class="badge bg-warning">Tergadai</span>
                                    @endif
                                </td>
                            @if (auth()->user()->isSuperadmin())
                                <td>
                                    <a href="{{ route('barang_gadai.edit', $barang->no_bon) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('barang_gadai.destroy', $barang->no_bon) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            @endif
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
{{-- index blade barang gadai --}}
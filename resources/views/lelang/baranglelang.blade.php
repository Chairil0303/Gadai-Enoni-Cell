@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h4><i class="fas fa-gavel"></i> Data Barang yang Sudah Dilelang (Status Aktif)</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($barangLelang->isEmpty())
                <div class="alert alert-info">Belum ada data barang lelang yang aktif.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No Bon</th>
                                <th>Nama Barang</th>
                                <th>Harga Lelang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangLelang as $item)
                            <tr>
                                <td>{{ $item->barangGadai->no_bon }}</td>
                                <td>{{ $item->barangGadai->nama_barang }}</td>
                                <td>Rp {{ number_format($item->harga_lelang, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('lelang.edit', $item->barangGadai->no_bon) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

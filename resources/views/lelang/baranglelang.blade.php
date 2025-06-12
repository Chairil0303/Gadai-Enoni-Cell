@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-gavel"></i> Data Barang yang Sudah Dilelang</h4>
            <a href="{{ route('lelang.pilihan') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
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
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'no_bon']) }}" class="text-white text-decoration-none">
                                        No Bon
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_barang']) }}" class="text-white text-decoration-none">
                                        Nama Barang
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'harga_lelang']) }}" class="text-white text-decoration-none">
                                        Harga Lelang
                                    </a>
                                </th>
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
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $barangLelang->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

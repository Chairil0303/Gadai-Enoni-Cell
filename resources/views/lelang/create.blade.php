@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white">
            <h4><i class="fas fa-edit"></i> Edit Data Lelang</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('lelang.update', $lelang->barang_gadai_no_bon) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Kondisi Barang --}}
                <div class="mb-3">
                    <label for="kondisi_barang" class="form-label">Kondisi Barang</label>
                    <textarea class="form-control" id="kondisi_barang" name="kondisi_barang" required>{{ $lelang->kondisi_barang }}</textarea>
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan">{{ $lelang->keterangan }}</textarea>
                </div>

                {{-- Harga Lelang --}}
                <div class="mb-3">
                    <label for="harga_lelang" class="form-label">Harga Lelang</label>
                    <input type="number" class="form-control" id="harga_lelang" name="harga_lelang" value="{{ $lelang->harga_lelang }}" required>
                </div>

                {{-- Foto Barang Lama --}}
                <div class="mb-3">
                    <label class="form-label">Foto Barang Lama</label>
                    <div class="row">
                        @foreach (json_decode($lelang->foto_barang, true) ?? [] as $index => $foto)
                            <div class="col-md-3 mb-2 text-center">
                                <img src="{{ asset('storage/' . $foto) }}" class="img-thumbnail mb-1" width="150">
                                <form action="{{ route('lelang.hapusFoto', ['id' => $lelang->id, 'index' => $index]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus foto ini?')">Hapus</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tambah Foto Baru --}}
                <div class="mb-3">
                    <label for="foto_baru" class="form-label">Tambah Foto Barang (Opsional)</label>
                    <input type="file" class="form-control" id="foto_baru" name="foto_baru[]" accept="image/*" multiple>
                </div>

                {{-- Tombol --}}
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

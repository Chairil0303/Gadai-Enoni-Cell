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

                <div class="mb-3">
                    <label for="kondisi_barang" class="form-label">Kondisi Barang</label>
                    <textarea class="form-control" id="kondisi_barang" name="kondisi_barang" required>{{ $lelang->kondisi_barang }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan">{{ $lelang->keterangan }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="harga_lelang" class="form-label">Harga Lelang</label>
                    <input type="number" class="form-control" id="harga_lelang" name="harga_lelang" value="{{ $lelang->harga_lelang }}" required>
                </div>

               <div class="mb-3">
    <label for="foto_barang" class="form-label">Foto Barang (Opsional)</label>
    <input type="file" class="form-control" id="foto_barang" name="foto_barang[]" accept="image/*" multiple>

    @if (!empty($fotoBarang))
        <div class="mt-3">
            <h5>Foto Saat Ini:</h5>
            <div class="row">
                @foreach ($fotoBarang as $index => $foto)
                    <div class="col-md-3 text-center mb-2">
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto Barang" class="img-thumbnail mb-1" width="150">
                        <div>
                            <input type="checkbox" name="delete_foto[]" value="{{ $foto }}">
                            <label>Hapus Foto</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>


                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('lelang.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

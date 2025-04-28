@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4><i class="fas fa-edit"></i> Edit No Bon Barang Gadai</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang_gadai.update_nobon', $barangGadai->no_bon) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- No Bon -->
                        <div class="mb-3">
                            <label for="no_bon" class="form-label">No Bon</label>
                            <input type="text" id="no_bon" name="no_bon" class="form-control @error('no_bon') is-invalid @enderror" value="{{ old('no_bon', $barangGadai->no_bon) }}" required>
                            @error('no_bon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('barang_gadai.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

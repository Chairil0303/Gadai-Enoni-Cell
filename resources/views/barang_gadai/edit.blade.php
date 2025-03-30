@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-edit"></i> Edit Barang Gadai</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('barang_gadai.update', $barangGadai->id_barang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                {{-- Nasabah --}}
                                <div class="mb-3">
                                    <label for="id_nasabah" class="form-label"><i class="fas fa-user"></i> Nasabah</label>
                                    <select name="id_nasabah" class="form-control" required>
                                        @foreach($nasabah as $n)
                                            <option value="{{ $n->id_nasabah }}" {{ $n->id_nasabah == $barangGadai->id_nasabah ? 'selected' : '' }}>
                                                {{ $n->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Nama Barang --}}
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label"><i class="fas fa-box"></i> Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barangGadai->nama_barang }}" required>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label"><i class="fas fa-align-left"></i> Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ $barangGadai->deskripsi }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                {{-- Status --}}
                                <div class="mb-3">
                                    <label for="status" class="form-label"><i class="fas fa-info-circle"></i> Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="Tergadai" {{ $barangGadai->status == 'Tergadai' ? 'selected' : '' }}>Tergadai</option>
                                        <option value="Ditebus" {{ $barangGadai->status == 'Ditebus' ? 'selected' : '' }}>Ditebus</option>
                                        <option value="Dilelang" {{ $barangGadai->status == 'Dilelang' ? 'selected' : '' }}>Dilelang</option>
                                    </select>
                                </div>

                                {{-- Kategori --}}
                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label"><i class="fas fa-tags"></i> Kategori</label>
                                    <select name="id_kategori" class="form-control">
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->id_kategori }}" {{ $k->id_kategori == $barangGadai->id_kategori ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save"></i> Update Barang Gadai
                        </button>
                        <a href="{{ route('barang_gadai.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

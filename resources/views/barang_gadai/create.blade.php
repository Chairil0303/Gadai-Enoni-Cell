@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="fas fa-box"></i> Tambah Barang Gadai</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('barang_gadai.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">

                                <!-- Pilih Nasabah -->
                                <div class="mb-3">
                                    <label for="id_nasabah" class="form-label"><i class="fas fa-user"></i> Nasabah</label>
                                    <select name="id_nasabah" class="form-control" required>
                                        <option value="">Pilih Nasabah</option>
                                        @foreach($nasabah as $n)
                                            <option value="{{ $n->id_nasabah }}">{{ $n->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nama Barang -->
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label"><i class="fas fa-tag"></i> Nama Barang</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required>
                                </div>

                                <!-- Deskripsi Barang -->
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label"><i class="fas fa-align-left"></i> Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi barang"></textarea>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <!-- Status Barang -->
                                <div class="mb-3">
                                    <label for="status" class="form-label"><i class="fas fa-info-circle"></i> Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="Tergadai">Tergadai</option>
                                        <option value="Ditebus">Ditebus</option>
                                        <option value="Dilelang">Dilelang</option>
                                    </select>
                                </div>

                                <!-- Kategori Barang -->
                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label"><i class="fas fa-list"></i> Kategori</label>
                                    <select name="id_kategori" class="form-control">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tombol Aksi -->
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save"></i> Simpan Barang
                                </button>
                                <a href="{{ route('barang_gadai.index') }}" class="btn btn-secondary w-100 mt-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

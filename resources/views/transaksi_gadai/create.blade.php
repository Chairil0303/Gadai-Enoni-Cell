@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-hand-holding-usd"></i> Terima Gadai</h4>
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

                    <form action="{{ route('gadai.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Kolom Form Nasabah --}}
                            <div class="col-md-6">
                                <h5 class="text-success">Form Tambah Nasabah</h5>
                                <hr>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" required placeholder="Masukkan Nama">
                                </div>

                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" name="nik" class="form-control" required placeholder="Masukkan NIK">
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required placeholder="Masukkan Alamat"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" name="telepon" class="form-control" required placeholder="Masukkan No Telepon">
                                </div>
                            </div>

                            {{-- Kolom Form Barang Gadai --}}
                            <div class="col-md-6">
                                <h5 class="text-success"><i class="fas fa-box"></i> Data Barang Gadai</h5>
                                <hr>
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" required placeholder="Masukkan Nama Barang">
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" placeholder="Masukkan Deskripsi"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="imei" class="form-label">IMEI</label>
                                    <input type="text" name="imei" class="form-control" placeholder="Masukkan IMEI">
                                </div>

                                <div class="mb-3">
                                    <label for="tenor" class="form-label">Tenor</label>
                                    <input type="text" name="tenor" class="form-control" placeholder="Masukan Tenor 7/14/30 hari">
                                </div>
                                {{-- gua ubah jadi field biasa biar jadi int --}}
                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label"><i class="fas fa-list"></i> Kategori</label>
                                    <select name="id_kategori" class="form-control" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori_barang as $k)
                                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="harga_gadai" class="form-label">Harga Gadai</label>
                                    <input type="number" name="harga_gadai" class="form-control" required placeholder="Masukkan Harga Gadai">
                                </div>

                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


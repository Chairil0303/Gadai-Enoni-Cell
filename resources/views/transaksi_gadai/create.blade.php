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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="container">
                                <h5 class="text-success">Form Tambah Nasabah</h5>
                                <hr>
                                <form action="{{ route('nasabah.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" autocomplete="off" placeholder="Masukan Nama Anda" name="nama" class="form-control" required>
                                    </div>
    
                                    <div class="mb-3">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" autocomplete="off" placeholder="Masukan Nik Anda" name="nik" class="form-control" required>
                                    </div>
    
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" autocomplete="off" placeholder="Masukan Alamat anda" class="form-control" rows="3" required></textarea>
                                    </div>
    
                                    <div class="mb-3">
                                        <label for="telepon" class="form-label">Nomor Telepon</label>
                                        <input type="text" name="telepon" autocomplete="off" placeholder="Masukan No Telp anda" class="form-control" required>
                                    </div>
    
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('barang_gadai.store') }}" method="POST">
                            @csrf
    
                            
    
                            <h5 class="text-success"><i class="fas fa-box"></i> Data Barang Gadai</h5>
                            <hr>
                            <div class="mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi barang"></textarea>
                            </div>
    
                            <div class="mb-3">
                                <label for="imei" class="form-label">IMEI (Jika ada)</label>
                                <input type="text" class="form-control" id="imei" name="imei" placeholder="Masukkan IMEI">
                            </div>
    
                            <div class="mb-3">
                                <label for="tenor" class="form-label">Tenor</label>
                                <select name="tenor" id="tenor" class="form-control" required>
                                    <option value="7">7 Hari</option>
                                    <option value="14">14 Hari</option>
                                    <option value="30">30 Hari</option>
                                </select>
                            </div>
    
                            <div class="mb-3">
                                <label for="harga_gadai" class="form-label">Harga Gadai</label>
                                <input type="number" class="form-control" id="harga_gadai" name="harga_gadai" placeholder="Masukkan harga gadai" required>
                            </div>
    
                            @if(isset($kategori) && count($kategori) > 0)
                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label">Kategori Barang</label>
                                    {{ dd($kategori) }}
                                    <select name="id_kategori" class="form-control" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <p>Tidak ada kategori tersedia.</p>
                            @endif
    
    
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> Simpan Gadai</button>
                            <a href="{{ route('barang_gadai.index') }}" class="btn btn-secondary w-100 mt-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </form>
                        </div>
                    </div>
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let tenorSelect = document.getElementById("tenor");
        let tempoInput = document.getElementById("tempo");

        tenorSelect.addEventListener("change", function () {
            let tenorValue = parseInt(tenorSelect.value); 
            let today = new Date(); 
            today.setDate(today.getDate() + tenorValue); 

            let formattedDate = today.toISOString().split("T")[0]; 
            tempoInput.value = formattedDate; 
        });
    });
</script>

@endsection

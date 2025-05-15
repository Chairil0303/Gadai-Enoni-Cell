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

                    <form action="{{ route('gadai.preview') }}" method="POST" id="formGadai">
                        @csrf
                        <div class="row">
                            {{-- Kolom Form Nasabah --}}
                            <div class="col-md-6">
                                <h5 class="text-success">Form Tambah Nasabah</h5>
                                <hr>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" autocomplete="off" name="nama" class="form-control" required placeholder="Masukkan Nama">
                                </div>

                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" autocomplete="off" name="nik" class="form-control" required placeholder="Masukkan NIK" maxlength="16" oninput="this.value = this.value.replace(/\D/g, '')">
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea name="alamat" autocomplete="off" class="form-control" rows="3" required placeholder="Masukkan Alamat"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" autocomplete="off" name="telepon" class="form-control" required placeholder="Masukkan No Telepon" maxlength="15" oninput="this.value = this.value.replace(/\D/g, '')">
                                </div>
                            </div>

                            {{-- Kolom Form Barang Gadai --}}
                            <div class="col-md-6">
                                <h5 class="text-success"><i class="fas fa-box"></i> Data Barang Gadai</h5>
                                <hr>
                                <div class="mb-3">
                                    <label for="no_bon" class="form-label">No. Bon</label>
                                    <input type="text" name="no_bon" class="form-control" required placeholder="Masukkan No. Bon">
                                </div>
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" autocomplete="off" name="nama_barang" class="form-control" required placeholder="Masukkan Nama Barang">
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" autocomplete="off" class="form-control" placeholder="Masukkan Deskripsi"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="imei" class="form-label">IMEI</label>
                                    <input type="text" name="imei" autocomplete="off" class="form-control" placeholder="Masukkan IMEI" oninput="this.value = this.value.replace(/\D/g, '')">
                                </div>

                                <div class="mb-3">
                                    <label for="tenor" class="form-label">Tenor</label>
                                    <select name="tenor" class="form-control" required>
                                        @foreach($bunga_tenors as $bunga)
                                            <option value="{{ $bunga->tenor }}">{{ $bunga->tenor }} hari (Bunga {{ $bunga->bunga_percent }}%)</option>
                                        @endforeach
                                    </select>
                                </div>

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
                                    <input type="text" autocomplete="off" name="harga_gadai" id="harga_gadai" class="form-control" required placeholder="Masukkan Harga Gadai" oninput="formatHargaGadai()">
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('barang_gadai.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Lanjutkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Format angka --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const inputHargaGadai = document.getElementById('harga_gadai');
    const form = document.getElementById('formGadai');

    function formatHargaGadai() {
        let value = inputHargaGadai.value.replace(/[^\d]/g, '');
        let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        inputHargaGadai.value = formattedValue;
    }

    inputHargaGadai.addEventListener('input', formatHargaGadai);

    form.addEventListener('submit', function () {
        // Hilangkan titik saat submit supaya validator numeric bisa jalan
        inputHargaGadai.value = inputHargaGadai.value.replace(/\./g, '');
    });
});

</script>
@endsection

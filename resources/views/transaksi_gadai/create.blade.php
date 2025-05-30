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

                    {{-- Validasi Error --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('gadai.preview') }}" method="POST" id="formGadai" autocomplete="off">
                        @csrf
                        <div class="row">
                            {{-- Form Nasabah --}}
                            <div class="col-md-6">
                                <h5 class="text-success">Form Tambah Nasabah</h5>
                                <hr>

                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $old['nama'] ?? '') }}" required placeholder="Masukkan Nama" autocomplete="off">
                                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" name="nik" maxlength="16" autocomplete="off"
                                        class="form-control @error('nik') is-invalid @enderror"
                                        value="{{ old('nik', $old['nik'] ?? '') }}" required
                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                        pattern="\d{16}"
                                        title="NIK harus terdiri dari 16 digit angka"
                                        placeholder="Masukkan NIK">
                                    @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea name="alamat" rows="3"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        required placeholder="Masukkan Alamat" autocomplete="off">{{ old('alamat', $old['alamat'] ?? '') }}</textarea>
                                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" name="telepon" maxlength="13" autocomplete="off"
                                        class="form-control @error('telepon') is-invalid @enderror"
                                        value="{{ old('telepon', $old['telepon'] ?? '') }}" required
                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                        pattern="\d{12,13}"
                                        title="Nomor telepon harus 12 atau 13 digit"
                                        placeholder="Masukkan No Telepon">
                                    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Form Barang Gadai --}}
                            <div class="col-md-6">
                                <h5 class="text-success"><i class="fas fa-box"></i> Data Barang Gadai</h5>
                                <hr>

                                <div class="mb-3">
                                    <label for="no_bon" class="form-label">No. Bon</label>
                                    <input type="text" name="no_bon"
                                        class="form-control @error('no_bon') is-invalid @enderror"
                                        value="{{ old('no_bon', $old['no_bon'] ?? '') }}" required placeholder="Masukkan No. Bon" autocomplete="off">
                                    @error('no_bon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_barang"
                                        class="form-control @error('nama_barang') is-invalid @enderror"
                                        value="{{ old('nama_barang', $old['nama_barang'] ?? '') }}" required
                                        placeholder="Masukkan Nama Barang" autocomplete="off">
                                    @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control"
                                        placeholder="Masukkan Deskripsi" autocomplete="off">{{ old('deskripsi', $old['deskripsi'] ?? '') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="imei" class="form-label">Nomor Seri</label>
                                    <input type="text" name="imei" autocomplete="off"
                                        class="form-control"
                                        value="{{ old('imei', $old['imei'] ?? '') }}"
                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                        placeholder="Masukkan Nomor Seri">
                                </div>

                                <div class="mb-3">
                                    <label for="tenor" class="form-label">Tenor</label>
                                    <select name="tenor" class="form-control @error('tenor') is-invalid @enderror" required autocomplete="off">
                                        <option value="">Pilih Tenor</option>
                                        @foreach($bunga_tenors as $bunga)
                                            <option value="{{ $bunga->tenor }}"
                                                {{ old('tenor', $old['tenor'] ?? '') == $bunga->tenor ? 'selected' : '' }}>
                                                {{ $bunga->tenor }} hari (Bunga {{ $bunga->bunga_percent }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tenor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select name="id_kategori" class="form-control @error('id_kategori') is-invalid @enderror" required autocomplete="off">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori_barang as $k)
                                            <option value="{{ $k->id_kategori }}"
                                                {{ old('id_kategori', $old['id_kategori'] ?? '') == $k->id_kategori ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="harga_gadai" class="form-label">Harga Gadai</label>
                                    <input type="text" name="harga_gadai" id="harga_gadai"
                                        class="form-control @error('harga_gadai') is-invalid @enderror"
                                        value="{{ old('harga_gadai', $old['harga_gadai'] ?? '') }}"
                                        placeholder="Masukkan Harga Gadai" required
                                        oninput="formatHargaGadai()" autocomplete="off">
                                    @error('harga_gadai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Tombol --}}
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

{{-- Script Format Harga --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputHargaGadai = document.getElementById('harga_gadai');
    const form = document.getElementById('formGadai');

    function formatHargaGadai() {
        let value = inputHargaGadai.value.replace(/[^\d]/g, '');
        let formatted = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        inputHargaGadai.value = formatted;
    }

    inputHargaGadai.addEventListener('input', formatHargaGadai);

    form.addEventListener('submit', function () {
        inputHargaGadai.value = inputHargaGadai.value.replace(/\./g, '');
    });
});
</script>
@endsection

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

                    <form action="{{ route('gadai.store') }}" method="POST" id="formGadai">
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

                        <!-- <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> Simpan</button> -->
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header Modal (Hijau) -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Penyimpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Body Modal -->
            <div class="modal-body">
                Apakah Anda yakin ingin menyimpan data ini? Pastikan data yang Anda masukkan sudah benar.
            </div>
            <!-- Footer Modal (Tombol Hijau) -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <!-- <button type="button" class="btn btn-success" id="konfirmasiSubmit">Simpan</button> -->
                <button type="button" class="btn btn-success" id="konfirmasiSubmit">Simpan</button>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formGadai');
        const inputHargaGadai = document.getElementById('harga_gadai');
        const konfirmasiButton = document.getElementById('konfirmasiSubmit');

        // Fungsi untuk menambahkan titik pemisah ribuan
        function formatHargaGadai() {
            let value = inputHargaGadai.value.replace(/[^\d]/g, ''); // Menghapus semua karakter selain angka
            let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Menambahkan titik pemisah setiap 3 digit
            inputHargaGadai.value = formattedValue;
        }

        // Tambahkan event listener untuk input harga_gadai
        inputHargaGadai.addEventListener('input', formatHargaGadai);

        // Event listener untuk tombol konfirmasi
        konfirmasiButton.addEventListener('click', function () {
            let rawValue = inputHargaGadai.value.replace(/\./g, ''); // Menghapus titik sebelum mengirim ke server

            // Memperbarui nilai input tanpa titik
            inputHargaGadai.value = rawValue;

            // Submit form setelah pembersihan data
            form.submit(); // Submit form ketika tombol "Simpan" di modal ditekan
        });
    });
</script>



@endsection
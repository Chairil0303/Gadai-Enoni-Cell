@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-success">
        <div class="card-header bg-success text-white text-center">
            <h4><i class="fas fa-check-circle"></i> Konfirmasi Data Gadai</h4>
        </div>
        <div class="card-body">
            <h5 class="text-success mb-3">Data Nasabah</h5>
            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Nama:</strong> <span>{{ $data['nama'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>NIK:</strong> <span>{{ $data['nik'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Alamat:</strong> <span>{{ $data['alamat'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Telepon:</strong> <span>{{ $data['telepon'] }}</span>
                </li>
            </ul>

            <h5 class="text-success mb-3">Data Barang Gadai</h5>
            <ul class="list-group mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>No Bon:</strong> <span>{{ $data['no_bon'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Nama Barang:</strong> <span>{{ $data['nama_barang'] }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Deskripsi:</strong> <span>{{ $data['deskripsi'] ?? '-' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>IMEI:</strong> <span>{{ $data['imei'] ?? '-' }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Kategori:</strong> <span>{{ $kategori_barang->nama_kategori }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Harga Gadai:</strong> <span>Rp {{ number_format($data['harga_gadai'], 0, ',', '.') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Tenor:</strong> <span>{{ $data['tenor'] }} hari</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Bunga:</strong> <span>{{ $bunga->bunga_percent }}%</span>
                </li>
            </ul>

            <div class="d-flex justify-content-between">
                <a href="{{ route('gadai.create') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi">
                    <i class="fas fa-check"></i> Terima Gadai
                </button>
            </div>

            <!-- Modal Konfirmasi -->
            <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-success">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="modalKonfirmasiLabel"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Penyimpanan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menyimpan data ini? Pastikan data yang Anda masukkan sudah benar.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('gadai.store') }}" method="POST" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="btn btn-success">Ya, Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        </div>
    </div>
</div>
@endsection

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
                <li class="list-group-item">
                    <strong>Nama:</strong><br>
                    {{ $data['nama'] }}
                </li>
                <li class="list-group-item">
                    <strong>NIK:</strong><br>
                    {{ $data['nik'] }}
                </li>
                <li class="list-group-item">
                    <strong>Alamat:</strong><br>
                    {{ $data['alamat'] }}
                </li>
                <li class="list-group-item">
                    <strong>Telepon:</strong><br>
                    {{ $data['telepon'] }}
                </li>
            </ul>

            <h5 class="text-success mb-3">Data Barang Gadai</h5>
            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>No Bon:</strong><br>
                    {{ $data['no_bon'] }}
                </li>
                <li class="list-group-item">
                    <strong>Nama Barang:</strong><br>
                    {{ $data['nama_barang'] }}
                </li>
                <li class="list-group-item">
                    <strong>Deskripsi:</strong><br>
                    {{ $data['deskripsi'] ?? '-' }}
                </li>
                <li class="list-group-item">
                    <strong>IMEI:</strong><br>
                    {{ $data['imei'] ?? '-' }}
                </li>
                <li class="list-group-item">
                    <strong>Kategori:</strong><br>
                    {{ $kategori_barang->nama_kategori }}
                </li>
                <li class="list-group-item">
                    <strong>Harga Gadai:</strong><br>
                    Rp {{ number_format($data['harga_gadai'], 0, ',', '.') }}
                </li>
                <li class="list-group-item">
                    <strong>Tenor:</strong><br>
                    {{ $data['tenor'] }} hari
                </li>
                <li class="list-group-item">
                    <strong>Bunga:</strong><br>
                    {{ $bunga->bunga_percent }}%
                </li>
            </ul>

            <h5 class="text-success mb-3">Perhitungan Gadai</h5>
            @php
                use Carbon\Carbon;

                $pokok = $data['harga_gadai'];
                $bunga_percent = $bunga->bunga_percent;
                $tenor = $data['tenor'];
                $total_bunga = ($pokok * $bunga_percent / 100);
                $total_tebus = $pokok + $total_bunga;
                $denda_per_hari = 5000; // contoh tetap

                $tanggal_tebus = Carbon::now()->addDays((int) $tenor)->translatedFormat('d F Y');
            @endphp

            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>Pokok Pinjaman:</strong><br>
                    Rp {{ number_format($pokok, 0, ',', '.') }}
                </li>
                <li class="list-group-item">
                    <strong>Bunga ({{ $bunga_percent }}%):</strong><br>
                    Rp {{ number_format($total_bunga, 0, ',', '.') }}
                </li>
                <li class="list-group-item">
                    <strong>Total Tebus (Pokok + Bunga):</strong><br>
                    <span class="fw-bold text-danger">Rp {{ number_format($total_tebus, 0, ',', '.') }}</span>
                </li>
                <li class="list-group-item">
                    <strong>Denda Per Hari (Jika Telat):</strong><br>
                    Rp {{ number_format($denda_per_hari, 0, ',', '.') }}
                </li>
                <li class="list-group-item">
                    <strong>Tanggal Jatuh Tempo:</strong><br>
                    <span class="text-primary fw-semibold">{{ $tanggal_tebus }}</span>
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

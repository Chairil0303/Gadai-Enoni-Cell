@extends('layouts.app')

@section('content')
<div class="container mt-4">

    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-check-circle"></i> Konfirmasi Tebus Gadai</h4>
        </div>

        <div class="card-body bg-light">

            <!-- Data Nasabah -->
            <div class="mb-4">
                <h5 class="text-success fw-bold"><i class="fas fa-user"></i> Data Nasabah</h5>
                <table class="table table-striped table-bordered rounded-3 overflow-hidden shadow-sm">
                    <tbody>
                        <tr><th class="bg-success-subtle">Nama Nasabah</th><td>{{ $barangGadai->nasabah->nama }}</td></tr>
                        <tr><th class="bg-success-subtle">NIK</th><td>{{ $barangGadai->nasabah->nik }}</td></tr>
                        <tr><th class="bg-success-subtle">Alamat</th><td>{{ $barangGadai->nasabah->alamat }}</td></tr>
                        <tr><th class="bg-success-subtle">No Telp</th><td>{{ $barangGadai->nasabah->telepon }}</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Data Barang Gadai -->
            <div class="mb-4">
                <h5 class="text-success fw-bold"><i class="fas fa-box"></i> Data Barang Gadai</h5>
                <table class="table table-striped table-bordered rounded-3 overflow-hidden shadow-sm">
                    <tbody>
                        <tr><th class="bg-success-subtle">Nama Barang</th><td>{{ $barangGadai->nama_barang }}</td></tr>
                        <tr><th class="bg-success-subtle">No Bon</th><td>{{ $barangGadai->no_bon }}</td></tr>
                        <tr><th class="bg-success-subtle">Harga Gadai</th><td>Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</td></tr>
                        <tr><th class="bg-success-subtle">Tenor</th><td>{{ $barangGadai->bungaTenor->tenor }} hari</td></tr>
                        <tr><th class="bg-success-subtle">Jatuh Tempo</th><td>{{ $barangGadai->tempo }}</td></tr>
                        <tr><th class="bg-success-subtle">Bunga</th><td>{{ $bungaPersen }}% (Rp {{ number_format($bunga, 0, ',', '.') }})</td></tr>
                        <tr><th class="bg-success-subtle">Telat</th><td>{{ $barangGadai->telat }} hari</td></tr>
                        <tr><th class="bg-success-subtle">Denda</th><td>Rp {{ number_format($denda, 0, ',', '.') }}</td></tr>
                        <tr><th class="bg-success-subtle">Total Tebus</th><td><strong class="text-danger">Rp {{ number_format($totalTebus, 0, ',', '.') }}</strong></td></tr>
                        <tr><th class="bg-success-subtle">Penerima Tebusan</th><td>{{ auth()->user()->name }}</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tombol Aksi -->
            <form id="tebusForm" action="{{ route('admin.tebus.proses', $barangGadai->no_bon) }}" method="POST">
                @csrf
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.tebus.index') }}" class="btn btn-outline-danger rounded-3 shadow-sm px-4">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-success rounded-3 shadow-sm px-4" id="adminConfirmTebusBtnFix">
                        <i class="fas fa-check"></i> Tebus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('adminConfirmTebusBtnFix').addEventListener('click', function () {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Barang akan ditebus dan statusnya akan berubah!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tebus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('tebusForm').submit();
            }
        });
    });
</script>
@endsection

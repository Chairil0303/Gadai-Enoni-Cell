@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Konfirmasi Tebus Gadai</h2>
    <br>

    <!-- Tabel Data Nasabah -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th colspan="2">Data Nasabah</th></tr>
        </thead>
        <tbody>
            <tr><th>Nama Nasabah</th><td>{{ $barangGadai->nasabah->nama }}</td></tr>
            <tr><th>NIK</th><td>{{ $barangGadai->nasabah->nik }}</td></tr>
            <tr><th>Alamat</th><td>{{ $barangGadai->nasabah->alamat }}</td></tr>
            <tr><th>No Telp</th><td>{{ $barangGadai->nasabah->telepon }}</td></tr>
        </tbody>
    </table>

    <!-- Tabel Data Barang Gadai -->
    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr><th colspan="2">Data Barang Gadai</th></tr>
        </thead>
        <tbody>
            <tr><th>Nama Barang</th><td>{{ $barangGadai->nama_barang }}</td></tr>
            <tr><th>No Bon</th><td>{{ $barangGadai->no_bon }}</td></tr>
            <tr><th>Harga Gadai</th><td>Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</td></tr>
            <tr><th>Tenor</th><td>{{ $barangGadai->bungaTenor->tenor }} hari</td></tr>
            <tr><th>Jatuh Tempo</th><td>{{ $barangGadai->tempo }}</td></tr>
            <tr><th>Bunga</th><td>{{ $bungaPersen }}% (Rp {{ number_format($bunga, 0, ',', '.') }})</td></tr>
            <tr><th>Telat</th><td>{{ $barangGadai->telat }} hari</td></tr>
            <tr><th>Denda</th><td>Rp {{ number_format($denda, 0, ',', '.') }}</td></tr>
            <tr><th>Total Tebus</th><td>Rp {{ number_format($totalTebus, 0, ',', '.') }}</td></tr>
            <tr><th>Penerima Tebusan</th><td>{{ auth()->user()->name }}</td></tr>
        </tbody>
    </table>

    <!-- Tombol Aksi -->
    <div class="mt-4">
        <form id="tebusForm" action="{{ route('admin.tebus.proses', $barangGadai->no_bon) }}" method="POST">
            @csrf
            <button type="button" class="btn btn-success" id="adminConfirmTebusBtnFix">Tebus</button>
            <a href="{{ route('admin.tebus.index') }}" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
        document.getElementById('adminConfirmTebusBtnFix').addEventListener('click', function() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menebus barang ini dan statusnya akan berubah menjadi Ditebus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
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

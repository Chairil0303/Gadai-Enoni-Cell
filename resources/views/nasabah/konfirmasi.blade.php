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
            <tr><th>Tenor</th><td>{{ $barangGadai->tenor }} hari</td></tr>
            <tr><th>Jatuh Tempo</th><td>{{ $barangGadai->tempo }}</td></tr>
            <tr><th>Bunga</th><td>{{ $bungaPersen }}% (Rp {{ number_format($bunga, 0, ',', '.') }})</td></tr>
            <tr><th>Telat</th><td>{{ $barangGadai->telat }} hari</td></tr>
            <tr><th>Denda</th><td>Rp {{ number_format($denda, 0, ',', '.') }}</td></tr>
            <tr><th>Total Tebus</th><td>Rp {{ number_format($totalTebus, 0, ',', '.') }}</td></tr>
            <tr><th>Penerima Tebusan</th><td>{{ auth()->user()->name }}</td></tr>
        </tbody>
    </table>

        <!-- Button untuk tebus -->
    <div class="mt-4 flex justify-end">
        <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
        <input type="hidden" id="total-tebus-{{ $barangGadai->no_bon }}" value="{{ $totalTebus }}">
        <input type="hidden" id="denda-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->denda }}">
        <button onclick="payWithMidtrans('{{ $barangGadai->no_bon }}')" class="bg-green-500 text-white px-4 py-2 rounded">
            Tebus Sekarang
        </button>
        <button onclick="window.location.href='{{ route('profile') }}'" class="btn btn-danger">Cancel</button>
    </div>


    <!-- Tombol Aksi -->
    {{-- <div class="mt-4">
        <form id="tebusForm" action="{{ route('tebus.tebus', $barangGadai->no_bon) }}" method="POST">
            @csrf
            <button type="button" class="btn btn-success" id="confirmTebusBtn">Tebus</button>
            <a href="{{ route('profile') }}" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</div> --}}

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// script midtrans
function payWithMidtrans(noBon) {
    var noBonElement = document.getElementById("no-bon-" + noBon);
    var totalTebusElement = document.getElementById("total-tebus-" + noBon);

    if (!noBonElement || !totalTebusElement) {
        console.error('Elemen tidak ditemukan untuk barang dengan no_bon: ' + noBon);
        return;
    }

    var amount = totalTebusElement.value;

    fetch('/nasabah/process-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            no_bon: noBon,
            payment_method: 'bank_transfer',
            amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    console.log(result);
                    // bisa redirect ke halaman sukses
                    window.location.href = '/nasabah/dashboard';
                },
                onPending: function(result) {
                    alert("Pembayaran sedang diproses.");
                    console.log(result);
                },
                onError: function(result) {
                    alert("Pembayaran gagal.");
                    console.log(result);
                },
                onClose: function() {
                    alert("Anda menutup popup pembayaran tanpa menyelesaikannya.");
                }
            });
        } else {
            alert('Terjadi kesalahan: Snap token tidak ditemukan.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pembayaran.');
    });
}


    document.getElementById('confirmTebusBtn').addEventListener('click', function() {
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
                // Submit form jika konfirmasi
                document.getElementById('tebusForm').submit();
            }
        });
    });
</script>

@endsection

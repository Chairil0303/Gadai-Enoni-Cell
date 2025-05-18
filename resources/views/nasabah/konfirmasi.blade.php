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
           <tr>
                <th>
                    Bunga
                    <i class="bi bi-info-circle d-none d-md-inline" data-bs-toggle="tooltip" title="Bunga dihitung dari persentase tenor: {{ $bungaPersen }}% x {{ $barangGadai->harga_gadai }}"></i>
                    <div class="d-md-none small text-muted">
                        Bunga dihitung dari persentase tenor: {{ $bungaPersen }}% x Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}
                    </div>
                </th>
                <td>{{ $bungaPersen }}% (Rp {{ number_format($bunga, 0, ',', '.') }})</td>
            </tr>
            <tr><th>Telat</th><td>{{ $barangGadai->telat }} hari</td></tr>
            <tr>
                <th>
                Denda
                <i class="bi bi-info-circle d-none d-md-inline"
                data-bs-toggle="tooltip"
                title="Denda dihitung: {{ $barangGadai->telat }} hari × 1% × Rp{{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} = Rp{{ number_format($denda, 0, ',', '.') }}">
                </i>
                <div class="d-md-none small text-muted">
                    Denda dihitung: {{ $barangGadai->telat }} hari × 1% × Rp{{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} = Rp{{ number_format($denda, 0, ',', '.') }}
                </div>
            </th>
            <td>Rp {{ number_format($denda, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <th>
                    Total Tebus
                    <i class="bi bi-info-circle d-none d-md-inline" data-bs-toggle="tooltip" title="Total = Harga Gadai + Bunga + Denda (jika telat)"></i>
                    <div class="d-md-none small text-muted">
                        Total = Harga Gadai + Bunga + Denda (jika telat)
                    </div>
                </th>
                <td>Rp {{ number_format($totalTebus, 0, ',', '.') }}</td>
            </tr>
    

        </tbody>
    </table>

       <!-- Button untuk Tebus -->
        <div class="mt-4 flex justify-end">
            <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
            <input type="hidden" id="total-tebus-{{ $barangGadai->no_bon }}" value="{{ $totalTebus }}">
            <input type="hidden" id="denda-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->denda }}">

            <!-- Tombol Tebus -->
            <button id="confirmTebusBtn" class="bg-green-500 text-white px-4 py-2 rounded">
                Tebus Sekarang
            </button>


            <div id="continue-payment-container"></div>

            <button onclick="window.location.href='{{ route('profile') }}'" class="btn btn-danger">Cancel</button>
        </div>



<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>// script midtrans
let latestSnapToken = null; // Global variable

function payWithMidtrans(noBon, paymentType = 'bank_transfer') {
    const noBonElement = document.getElementById("no-bon-" + noBon);
    const totalTebusElement = document.getElementById("total-tebus-" + noBon);

    if (!noBonElement || !totalTebusElement) {
        console.error('Elemen tidak ditemukan untuk barang dengan no_bon: ' + noBon);
        return;
    }

    const amount = totalTebusElement.value;

    fetch('/nasabah/process-tebus-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            no_bon: noBon,
            payment_type: paymentType,
            amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            latestSnapToken = data.snap_token;

            localStorage.setItem('pending_payment', JSON.stringify({
                snap_token: data.snap_token,
                order_id: data.order_id
            }));

            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil',
                        text: 'Pembayaran Anda telah berhasil diproses.',
                    });
                    localStorage.removeItem('pending_payment');
                    window.location.href = '/nasabah/dashboard';
                },
                onPending: function(result) {
                    swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran Anda sedang diproses.',
                    });
                },
                onError: function(result) {
                    swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan saat memproses pembayaran.',
                    });
                },
                onClose: function() {
                    swal.fire({
                        icon: 'warning',
                        title: 'Apakah Anda yakin ingin membatalkan pembayaran?',
                        text: 'Jika Anda lanjut, transaksi akan dibatalkan.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, batalkan',
                        cancelButtonText: 'Tidak, lanjutkan pembayaran'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const stored = JSON.parse(localStorage.getItem('pending_payment'));

                            fetch('/nasabah/cancel-payment', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    order_id: stored ? stored.order_id : data.order_id
                                })
                            })
                            .then(res => res.json())
                            .then(cancelResp => {
                                localStorage.removeItem('pending_payment');
                                swal.fire({
                                    icon: 'info',
                                    title: 'Pembayaran Dibatalkan',
                                    text: 'Pembayaran Anda telah dibatalkan.',
                                });
                            });
                        } else {
                            showContinueButton();
                        }
                    });
                }
            });
        } else {
            swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: 'Terjadi kesalahan saat memproses pembayaran.',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pembayaran.');
    });
}


    function showContinueButton() {
        const container = document.getElementById('continue-payment-container');
        container.innerHTML = `
            <button onclick="resumeSnap()" class="bg-blue-500 text-white px-4 py-2 rounded">
                Lanjutkan Pembayaran
            </button>
        `;
    }

    function resumeSnap() {
        const stored = localStorage.getItem('pending_payment');
        const payment = stored ? JSON.parse(stored) : null;
        const snapToken = latestSnapToken || (payment && payment.snap_token);

        if (snapToken) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil',
                        text: 'Pembayaran Anda telah berhasil diproses.',
                    });
                    localStorage.removeItem('pending_payment');
                    window.location.href = '/nasabah/dashboard';
                },
                onPending: function(result) {
                    swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran Anda sedang diproses.',
                    });
                },
                onError: function(result) {
                    swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan saat memproses pembayaran.',
                    });
                },
                onClose: function() {
                    swal.fire({
                        icon: 'warning',
                        title: 'Apakah Anda yakin ingin membatalkan pembayaran?',
                        text: 'Jika Anda lanjut, transaksi akan dibatalkan.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, batalkan',
                        cancelButtonText: 'Tidak, lanjutkan pembayaran'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const stored = JSON.parse(localStorage.getItem('pending_payment'));

                            fetch('/nasabah/cancel-payment', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    order_id: stored ? stored.order_id : data.order_id
                                })
                            })
                            .then(res => res.json())
                            .then(cancelResp => {
                                localStorage.removeItem('pending_payment');
                                swal.fire({
                                    icon: 'info',
                                    title: 'Pembayaran Dibatalkan',
                                    text: 'Pembayaran Anda telah dibatalkan.',
                                });
                            });
                        } else {
                            showContinueButton();
                        }
                    });
                }
            });
        }
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
                // Jika pengguna mengkonfirmasi, panggil fungsi untuk memproses pembayaran
                payWithMidtrans(document.getElementById('no-bon-{{ $barangGadai->no_bon }}').value, 'tebus');
            }
        });
    });

    document.getElementById('confirmPerpanjangBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan memperpanjang barang ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Perpanjang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengkonfirmasi, panggil fungsi untuk memproses pembayaran perpanjang
                payWithMidtrans(document.getElementById('no-bon-{{ $barangGadai->no_bon }}').value, 'perpanjang');
            }
        });
    });

     document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    </script>

@endsection

{{-- konfirmasi blade --}}

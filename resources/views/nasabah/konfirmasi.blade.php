@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-gradient">Konfirmasi Tebus Gadai</h2>
                <p class="text-muted">Silakan periksa detail transaksi Anda</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle fs-1 me-3"></i>
                        <div>
                            <h3 class="mb-0">Data Nasabah</h3>
                            <small>Informasi peminjam</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Tabel Data Nasabah -->
                    <div class="table-responsive mb-4">
                        <table class="table table-hover align-middle">
                            <tbody>
                                <tr>
                                    <th class="w-25 text-muted">Nama Nasabah</th>
                                    <td class="fw-semibold">{{ $barangGadai->nasabah->nama }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">NIK</th>
                                    <td class="fw-semibold">{{ $barangGadai->nasabah->nik }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Alamat</th>
                                    <td class="fw-semibold">{{ $barangGadai->nasabah->alamat }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">No Telp</th>
                                    <td class="fw-semibold">{{ $barangGadai->nasabah->telepon }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Divider -->
                    <hr class="my-4">

                    <!-- Tabel Data Barang Gadai -->
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-box-seam fs-1 text-primary me-3"></i>
                        <div>
                            <h3 class="mb-0">Data Barang Gadai</h3>
                            <small class="text-muted">Detail barang yang akan ditebus</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <tbody>
                                <tr>
                                    <th class="w-25 text-muted">Nama Barang</th>
                                    <td class="fw-semibold">{{ $barangGadai->nama_barang }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">No Bon</th>
                                    <td class="fw-semibold">{{ $barangGadai->no_bon }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Harga Gadai</th>
                                    <td class="fw-semibold text-primary">Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tenor</th>
                                    <td class="fw-semibold">{{ $barangGadai->bungaTenor->tenor }} hari</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Jatuh Tempo</th>
                                    <td class="fw-semibold">{{ $barangGadai->tempo }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">
                                        Bunga
                                        <i class="bi bi-info-circle-fill text-primary ms-1" data-bs-toggle="tooltip" title="Bunga dihitung dari persentase tenor: {{ $bungaPersen }}% x {{ $barangGadai->harga_gadai }}"></i>
                                        <div class="d-md-none small text-muted">
                                            Bunga dihitung dari persentase tenor: {{ $bungaPersen }}% x Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}
                                        </div>
                                    </th>
                                    <td class="fw-semibold">{{ $bungaPersen }}% (Rp {{ number_format($bunga, 0, ',', '.') }})</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Telat</th>
                                    <td class="fw-semibold">{{ $barangGadai->telat }} hari</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">
                                        Denda
                                        <i class="bi bi-info-circle-fill text-primary ms-1" data-bs-toggle="tooltip" title="Denda dihitung: {{ $barangGadai->telat }} hari × 1% × Rp{{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} = Rp{{ number_format($denda, 0, ',', '.') }}"></i>
                                        <div class="d-md-none small text-muted">
                                            Denda dihitung: {{ $barangGadai->telat }} hari × 1% × Rp{{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} = Rp{{ number_format($denda, 0, ',', '.') }}
                                        </div>
                                    </th>
                                    <td class="fw-semibold text-danger">Rp {{ number_format($denda, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Section -->
                    <div class="bg-light rounded-4 p-4 mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">Total Tebus</h4>
                                <small class="text-muted">Harga Gadai + Bunga + Denda (jika telat)</small>
                            </div>
                            <h3 class="text-primary mb-0">Rp {{ number_format($totalTebus, 0, ',', '.') }}</h3>
                        </div>
                    </div>

                    <!-- Button untuk Tebus -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
                        <input type="hidden" id="total-tebus-{{ $barangGadai->no_bon }}" value="{{ $totalTebus }}">
                        <input type="hidden" id="denda-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->denda }}">

                        <button onclick="window.location.href='{{ route('profile') }}'" class="btn btn-outline-danger rounded-pill px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </button>

                        <div class="d-flex gap-3">
                            <div id="continue-payment-container"></div>
                            <button id="confirmTebusBtn" class="btn btn-success rounded-pill px-4">
                                <i class="bi bi-check-circle me-2"></i>Tebus Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
}

.text-gradient {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.table th {
    font-weight: 500;
}

.btn {
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

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

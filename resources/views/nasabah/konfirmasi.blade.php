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
                            <!-- Container untuk tombol lanjutkan pembayaran -->
                            <div id="continue-payment-container"></div>

                            <!-- Container untuk tombol buat pembayaran baru -->
                            <div id="new-payment-container"></div>

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

<!-- Modal untuk Pembayaran Pending -->
<div class="modal fade" id="pendingPaymentModal" tabindex="-1" aria-labelledby="pendingPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pendingPaymentModalLabel">Pembayaran Pending Ditemukan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pending-payment-content">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="resume-payment-btn">Lanjutkan Pembayaran</button>
                <button type="button" class="btn btn-success" id="new-payment-btn">Buat Pembayaran Baru</button>
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

<script>
let latestSnapToken = null;

function payWithMidtrans(noBon, paymentType = 'bank_transfer') {
    const noBonElement = document.getElementById("no-bon-" + noBon);
    const totalTebusElement = document.getElementById("total-tebus-" + noBon);

    if (!noBonElement || !totalTebusElement) {
        console.error('Elemen tidak ditemukan untuk barang dengan no_bon: ' + noBon);
        return;
    }

    const amount = totalTebusElement.value;

    // Tampilkan loading
    Swal.fire({
        title: 'Memproses Pembayaran',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

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
        Swal.close();

        if (data.snap_token) {
            // Pembayaran baru berhasil dibuat
            latestSnapToken = data.snap_token;
            localStorage.setItem('pending_payment', JSON.stringify({
                snap_token: data.snap_token,
                order_id: data.order_id
            }));

            // Tampilkan popup Midtrans
            showMidtransPopup(data.snap_token, data.order_id);
        } else if (data.status === 'resumable') {
            // Ada pembayaran pending yang bisa dilanjutkan
            showPendingPaymentModal(data, 'resumable');
        } else if (data.status === 'pending_uncheckable') {
            // Ada pembayaran pending tapi tidak bisa dicek statusnya
            showPendingPaymentModal(data, 'uncheckable');
        } else if (data.status === 'completed') {
            // Pembayaran sudah berhasil diproses
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil',
                text: 'Pembayaran Anda telah berhasil diproses.',
            }).then(() => {
                window.location.href = '/nasabah/dashboard';
            });
        } else {
            // Error lainnya
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: data.message || 'Terjadi kesalahan saat memproses pembayaran.',
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'
        });
    });
}

function showMidtransPopup(snapToken, orderId) {
    if (typeof snap === 'undefined') {
        console.error('Midtrans Snap belum diinisialisasi');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat memuat pembayaran. Silakan refresh halaman dan coba lagi.'
        });
        return;
    }

    try {
        snap.pay(snapToken, {
            onSuccess: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil',
                    text: 'Pembayaran Anda telah berhasil diproses.',
                });
                localStorage.removeItem('pending_payment');
                window.location.href = '/nasabah/dashboard';
            },
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Pending',
                    text: 'Pembayaran Anda sedang diproses.',
                });
            },
            onError: function(result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan saat memproses pembayaran.',
                });
            },
            onClose: function() {
                handlePaymentCancel(orderId);
            }
        });
    } catch (error) {
        console.error('Error saat memanggil snap.pay:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat memuat pembayaran. Silakan refresh halaman dan coba lagi.'
        });
    }
}

function showPendingPaymentModal(data, type) {
    const modal = new bootstrap.Modal(document.getElementById('pendingPaymentModal'));
    const content = document.getElementById('pending-payment-content');
    const resumeBtn = document.getElementById('resume-payment-btn');
    const newPaymentBtn = document.getElementById('new-payment-btn');

    if (type === 'resumable') {
        content.innerHTML = `
            <div class="alert alert-info">
                <h6><i class="bi bi-info-circle me-2"></i>Pembayaran Pending Ditemukan</h6>
                <p class="mb-2">Anda memiliki pembayaran yang belum selesai untuk barang ini.</p>
                <ul class="mb-0">
                    <li>Order ID: <strong>${data.order_id}</strong></li>
                    <li>Jumlah: <strong>Rp ${numberFormat(data.data.jumlah_pembayaran)}</strong></li>
                    <li>Dibuat: <strong>${new Date(data.data.created_at).toLocaleString('id-ID')}</strong></li>
                </ul>
            </div>
            <p class="text-muted">Silakan pilih untuk melanjutkan pembayaran yang ada atau buat pembayaran baru.</p>
        `;

        resumeBtn.style.display = 'inline-block';
        newPaymentBtn.style.display = 'inline-block';

        resumeBtn.onclick = () => {
            modal.hide();
            resumePayment(data.data.snap_token, data.order_id);
        };

        newPaymentBtn.onclick = () => {
            modal.hide();
            createNewPayment();
        };
    } else if (type === 'uncheckable') {
        content.innerHTML = `
            <div class="alert alert-warning">
                <h6><i class="bi bi-exclamation-triangle me-2"></i>Status Pembayaran Tidak Dapat Dicek</h6>
                <p class="mb-2">Ada pembayaran pending yang tidak dapat dicek statusnya.</p>
                <ul class="mb-0">
                    <li>Order ID: <strong>${data.order_id}</strong></li>
                </ul>
            </div>
            <p class="text-muted">Silakan pilih untuk melanjutkan pembayaran yang ada atau buat pembayaran baru.</p>
        `;

        resumeBtn.style.display = 'inline-block';
        newPaymentBtn.style.display = 'inline-block';

        resumeBtn.onclick = () => {
            modal.hide();
            checkResumablePayment();
        };

        newPaymentBtn.onclick = () => {
            modal.hide();
            createNewPayment();
        };
    }

    modal.show();
}

function resumePayment(snapToken, orderId) {
    if (typeof snap === 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Midtrans Snap belum diinisialisasi.'
        });
        return;
    }

    try {
        snap.pay(snapToken, {
            onSuccess: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil',
                    text: 'Pembayaran Anda telah berhasil diproses.',
                });
                localStorage.removeItem('pending_payment');
                window.location.href = '/nasabah/dashboard';
            },
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Pending',
                    text: 'Pembayaran Anda sedang diproses.',
                });
            },
            onError: function(result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan saat memproses pembayaran.',
                });
            },
            onClose: function() {
                handlePaymentCancel(orderId);
            }
        });
    } catch (error) {
        console.error('Error saat memanggil snap.pay:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat memuat pembayaran.'
        });
    }
}

function checkResumablePayment() {
    const noBon = document.getElementById('no-bon-{{ $barangGadai->no_bon }}').value;

    Swal.fire({
        title: 'Mengecek Pembayaran',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('/nasabah/check-resumable-payment', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ no_bon: noBon })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.status === 'resumable') {
            resumePayment(data.data.snap_token, data.data.order_id);
        } else if (data.status === 'completed') {
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil',
                text: 'Pembayaran Anda telah berhasil diproses.',
            }).then(() => {
                window.location.href = '/nasabah/dashboard';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Tidak Dapat Dilanjutkan',
                text: data.message || 'Pembayaran tidak dapat dilanjutkan.',
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengecek pembayaran.'
        });
    });
}

function createNewPayment() {
    const noBon = document.getElementById('no-bon-{{ $barangGadai->no_bon }}').value;

    Swal.fire({
        title: 'Membuat Pembayaran Baru',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('/nasabah/create-new-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ no_bon: noBon })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.snap_token) {
            latestSnapToken = data.snap_token;
            localStorage.setItem('pending_payment', JSON.stringify({
                snap_token: data.snap_token,
                order_id: data.order_id
            }));
            showMidtransPopup(data.snap_token, data.order_id);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Gagal',
                text: data.message || 'Terjadi kesalahan saat membuat pembayaran baru.',
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat membuat pembayaran baru.'
        });
    });
}

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
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

function handlePaymentCancel(orderId) {
    Swal.fire({
        icon: 'warning',
        title: 'Batalkan Pembayaran?',
        text: 'Transaksi akan dibatalkan jika Anda keluar.',
        showCancelButton: true,
        confirmButtonText: 'Batalkan',
        cancelButtonText: 'Lanjutkan Pembayaran'
    }).then(result => {
        if (result.isConfirmed) {
            // Batalkan pembayaran
            fetch('/nasabah/cancel-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ order_id: orderId })
            }).then(() => {
                localStorage.removeItem('pending_payment');
                Swal.fire('Dibatalkan', 'Pembayaran telah dibatalkan.', 'info');
            });
        } else {
            // Lanjutkan pembayaran
            showContinueButton();
        }
    });
}
</script>

@endsection

{{-- konfirmasi blade --}}

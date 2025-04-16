@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">Konfirmasi Perpanjang Gadai</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card Data Nasabah -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="bg-dark text-white px-4 py-2 rounded-t-md">
                <h3 class="text-lg font-semibold m-0">Data Nasabah</h3>
            </div>
            <div class="p-4">
                <p><strong>Nama Nasabah:</strong> {{ $barangGadai->nasabah->nama }}</p>
                <p><strong>NIK:</strong> {{ $barangGadai->nasabah->nik }}</p>
                <p><strong>Alamat:</strong> {{ $barangGadai->nasabah->alamat }}</p>
                <p><strong>No Telp:</strong> {{ $barangGadai->nasabah->telepon }}</p>
            </div>
        </div>

        <!-- Card Data Barang Gadai -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="bg-dark text-white px-4 py-2 rounded-t-md">
                <h3 class="text-lg font-semibold m-0">Data Barang Gadai</h3>
            </div>
            <div class="p-4">
                <p><strong>Nama Barang:</strong> {{ $barangGadai->nama_barang }}</p>
                <p><strong>No Bon:</strong> {{ $barangGadai->no_bon }}</p>
                <p><strong>Harga Gadai:</strong> Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</p>
                <p><strong>Tenor:</strong> {{ $barangGadai->tenor }} hari</p>
                <p><strong>Jatuh Tempo:</strong> {{ $barangGadai->tempo }}</p>
                <p><strong>Bunga:</strong> {{ $bungaPersen }}% (Rp {{ number_format($bunga, 0, ',', '.') }})</p>
                <p><strong>Telat:</strong> {{ $barangGadai->telat }} hari</p>
                <p><strong>Denda:</strong> Rp {{ number_format($denda, 0, ',', '.') }}</p>
                <p><strong>Total Perpanjang:</strong> <span class="text-success fw-bold">Rp {{ number_format($totalPerpanjang, 0, ',', '.') }}</span></p>
                <p><strong>Penerima Tebusan:</strong> {{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

       <!-- Button untuk Tebus -->
<div class="mt-4 flex justify-end">
    <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
    <input type="hidden" id="total-tebus-{{ $barangGadai->no_bon }}" value="{{ $totalTebus }}">
    <input type="hidden" id="denda-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->denda }}">

    <!-- Tombol Tebus -->
    <button id="confirmTebusBtn" class="bg-green-500 text-white px-4 py-2 rounded">
        Tebus Sekarang
    </button>

    <!-- Tombol Perpanjang -->
    <button id="confirmPerpanjangBtn" class="bg-yellow-500 text-white px-4 py-2 rounded ml-2">
        Perpanjang
    </button>

    <div id="continue-payment-container"></div>

    <button onclick="window.location.href='{{ route('profile') }}'" class="btn btn-danger">Cancel</button>
</div>




<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>// script midtrans
let latestSnapToken = null; // Global variable

function payWithMidtrans(noBon, paymentType) {
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
            payment_type: paymentType,  // Kirim payment_type yang dipilih
            amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            latestSnapToken = data.snap_token;

            // Simpan snap_token & order_id di localStorage
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
</script>

@endsection

{{-- pepanjang gadai --}}

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
        <!-- Hidden Inputs -->
        <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
        <input type="hidden" id="total-Perpanjang-{{ $barangGadai->no_bon }}" value="{{ $totalPerpanjang }}">

        <!-- Tombol Perpanjang -->
        <button id="confirmPerpanjangBtn" data-no-bon="{{ $barangGadai->no_bon }}" class="bg-green-500 text-white px-4 py-2 rounded">
            Bayar & Perpanjang
        </button>

        <div id="continue-payment-container"></div>

<!-- Snap.js -->
<script type="text/javascript"
    src="https://app.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let latestSnapToken = null;

    function payPerpanjang(noBon) {
        const totalElement = document.getElementById("total-Perpanjang-" + noBon);
        const amount = totalElement.value;

        fetch('/nasabah/process-perpanjang-payment', {
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
        latestSnapToken = data.snap_token;

        localStorage.setItem('pending_perpanjang', JSON.stringify({
            snap_token: data.snap_token,
            order_id: data.order_id
        }));

        snap.pay(data.snap_token, {
            onSuccess: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil',
                    html: `
                        Barang atas nama <b>${data.detail.nama_nasabah}</b> berhasil diperpanjang.<br>
                        Tenor: ${data.detail.tenor} hari<br>
                        Total dibayar: Rp ${data.total_perpanjang.toLocaleString()}
                    `
                });
                localStorage.removeItem('pending_perpanjang');
                window.location.href = '/nasabah/dashboard';
            },
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Pending',
                    text: 'Pembayaran sedang diproses.',
                });
            },
            onError: function(result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan saat pembayaran.',
                });
            },
            onClose: function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batalkan Pembayaran?',
                    text: 'Yakin ingin membatalkan transaksi ini?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tidak, Lanjutkan'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const stored = JSON.parse(localStorage.getItem('pending_perpanjang'));
                        fetch('/nasabah/cancel-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                order_id: stored ? stored.order_id : data.order_id
                            })
                        }).then(() => {
                            localStorage.removeItem('pending_perpanjang');
                            Swal.fire({
                                icon: 'info',
                                title: 'Dibatalkan',
                                text: 'Pembayaran telah dibatalkan.',
                            });
                        });
                    } else {
                        showContinueButton();
                    }
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal mendapatkan Snap Token.',
        });
    }
})

        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memproses pembayaran.');
        });
    }

    function showContinueButton() {
        const container = document.getElementById('continue-payment-container');
        container.innerHTML = `
            <button onclick="resumePerpanjang()" class="bg-blue-500 text-white px-4 py-2 rounded">
                Lanjutkan Pembayaran
            </button>
        `;
    }

    function resumePerpanjang() {
        const stored = localStorage.getItem('pending_perpanjang');
        const payment = stored ? JSON.parse(stored) : null;
        const snapToken = latestSnapToken || (payment && payment.snap_token);

        if (snapToken) {
            snap.pay(snapToken, {
                onSuccess: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Pembayaran berhasil diproses.',
                    });
                    localStorage.removeItem('pending_perpanjang');
                    window.location.href = '/nasabah/dashboard';
                },
                onPending: function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pending',
                        text: 'Pembayaran sedang diproses.',
                    });
                },
                onError: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan pembayaran.',
                    });
                },
                onClose: function() {
                    showContinueButton();
                }
            });
        }
    }

    // Cek pembayaran pending di page load
    document.addEventListener('DOMContentLoaded', function () {
        const stored = localStorage.getItem('pending_perpanjang');
        const payment = stored ? JSON.parse(stored) : null;

        if (payment && payment.order_id) {
            fetch('/nasabah/validate-pending-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order_id: payment.order_id })
            })
            .then(res => res.json())
            .then(data => {
                if (data.is_pending) {
                    showContinueButton();
                } else {
                    localStorage.removeItem('pending_perpanjang');
                }
            });
        }
    });

    // Event tombol "Bayar & Perpanjang"
    document.getElementById('confirmPerpanjangBtn').addEventListener('click', function () {
        Swal.fire({
            title: 'Konfirmasi Perpanjangan',
            text: "Anda akan membayar bunga untuk memperpanjang tenor barang ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const noBon = document.getElementById('no-bon-{{ $barangGadai->no_bon }}').value;
                payPerpanjang(noBon);
            }
        });
    });

    document.getElementById('confirmPerpanjangBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Konfirmasi Perpanjangan',
        text: "Anda akan membayar bunga untuk memperpanjang tenor barang ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const noBon = this.getAttribute('data-no-bon'); // FIX DI SINI
            payPerpanjang(noBon);
        }
    });
});

</script>


@endsection

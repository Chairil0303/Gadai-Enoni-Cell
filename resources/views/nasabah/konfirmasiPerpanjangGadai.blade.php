@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800">Konfirmasi Perpanjangan Gadai</h2>
        <p class="text-gray-500 mt-2">Pastikan data berikut sudah benar sebelum melanjutkan.</p>
    </div>

    <!-- Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card Data Nasabah -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-blue-500 text-white p-2 rounded-full">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="text-xl font-semibold ml-3">Data Nasabah</h3>
            </div>
            <div class="text-gray-700 space-y-2">
                <p><span class="font-medium">Nama:</span> {{ $barangGadai->nasabah->nama }}</p>
                <p><span class="font-medium">NIK:</span> {{ $barangGadai->nasabah->nik }}</p>
                <p><span class="font-medium">Alamat:</span> {{ $barangGadai->nasabah->alamat }}</p>
                <p><span class="font-medium">No Telp:</span> {{ $barangGadai->nasabah->telepon }}</p>
            </div>
        </div>

        <!-- Card Data Barang Gadai -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="bg-green-500 text-white p-2 rounded-full">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="text-xl font-semibold ml-3">Detail Barang Gadai</h3>
            </div>
            <div class="text-gray-700 space-y-2">
                <p><span class="font-medium">Nama Barang:</span> {{ $barangGadai->nama_barang }}</p>
                <p><span class="font-medium">No Bon:</span> <em>Menunggu Admin</em></p>
                <hr class="my-2">
                <p><span class="font-medium">Harga Gadai Saat Ini:</span> Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</p>
                @if ($cicilan > 0)
                    <p><span class="font-medium">Cicilan Dibayarkan:</span> Rp {{ number_format($cicilan, 0, ',', '.') }}</p>
                @endif
                <p>
                    <span class="font-medium">Harga Gadai Baru:</span>
                    <i class="bi bi-info-circle d-none d-md-inline"
                    data-bs-toggle="tooltip"
                    title="Harga Gadai Lama Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} - Cicilan Rp {{ number_format($cicilan, 0, ',', '.') }}">
                    </i>
                    Rp {{ number_format($barangGadai->harga_gadai - $cicilan, 0, ',', '.') }}
                    <div class="d-md-none text-sm text-gray-500">
                        Harga Gadai Lama Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} - Cicilan Rp {{ number_format($cicilan, 0, ',', '.') }}
                    </div>
                </p>

                {{-- <p><span class="font-medium">Harga Gadai Baru:</span> Rp {{ number_format($barangGadai->harga_gadai - $cicilan, 0, ',', '.') }}</p> --}}
                <p><span class="font-medium">Tenor Lama / Baru:</span> {{ $tenors }} hari → {{ $tenor }} hari</p>
                <p><span class="font-medium">Jatuh Tempo Baru:</span> {{ $tempobaru }}</p>
                <p>
                    <span class="font-medium">Bunga:</span>
                     <i class="bi bi-info-circle d-none d-md-inline"
                    data-bs-toggle="tooltip"
                    title="Bunga {{ $bungaTenorBaru->bunga_percent }}% x Harga Gadai Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}"></i>
                    {{ $bungaTenorBaru->bunga_percent }}% (Rp {{ number_format($bunga_persen_baru, 0, ',', '.') }})

                    <div class="d-md-none small text-muted">
                        Bunga {{ $bungaTenorBaru->bunga_percent }}% x Harga Gadai Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}
                    </div>

                </p>

                {{-- <p><span class="font-medium">Bunga:</span> {{ $bungaTenorBaru->bunga_percent }}% (Rp {{ number_format($bunga_persen_baru, 0, ',', '.') }})</p> --}}
                <p><span class="font-medium">Telat:</span> {{ $barangGadai->telat }} hari</p>
                <p>
                    <span class="font-medium">Denda:</span>
                    <i class="bi bi-info-circle d-none d-md-inline"
                    data-bs-toggle="tooltip"
                    title="Denda = {{ $barangGadai->telat }} hari × 1% × Rp{{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} = Rp{{ number_format($denda, 0, ',', '.') }}"></i>
                    Rp {{ number_format($denda, 0, ',', '.') }}
                    <div class="d-md-none text-sm text-gray-500">
                        Denda = {{ $barangGadai->telat }} hari × 1% × Rp{{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} = Rp{{ number_format($denda, 0, ',', '.') }}
                    </div>
                </p>


                {{-- <p><span class="font-medium">Denda:</span> Rp {{ number_format($denda, 0, ',', '.') }}</p> --}}
                <div class="bg-gray-100 p-3 rounded-lg mt-2">
                <p class="text-lg font-bold text-green-600">
                    Total Perpanjangan: <i class="bi bi-info-circle d-none d-md-inline"
                    data-bs-toggle="tooltip"
                    title="Total = Rp {{ number_format($bunga_persen_baru, 0, ',', '.') }} (bunga) + Rp {{ number_format($denda, 0, ',', '.') }} (denda){{ $cicilan > 0 ? ' + Rp ' . number_format($cicilan, 0, ',', '.') . ' (cicilan)' : '' }}"></i>
                     Rp {{ number_format($totalPerpanjang + $cicilan, 0, ',', '.') }}
                    <div class="d-md-none text-sm text-gray-500">
                        Total = Rp {{ number_format($bunga_persen_baru, 0, ',', '.') }} (bunga) + Rp {{ number_format($denda, 0, ',', '.') }} (denda){{ $cicilan > 0 ? ' + Rp ' . number_format($cicilan, 0, ',', '.') . ' (cicilan)' : '' }}
                    </div>
                </p>


                {{-- <p class="text-lg font-bold text-green-600">Total Perpanjangan: Rp {{ number_format($totalPerpanjang + $cicilan, 0, ',', '.') }}</p> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi Tombol -->
    <div class="mt-8 flex justify-center space-x-4">
        <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
        <input type="hidden" id="total-perpanjang-{{ $barangGadai->no_bon }}" value="{{ $totalPerpanjang }}">
        <input type="hidden" id="denda-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->denda }}">

        <button id="confirmPerpanjangBtn" class="px-6 py-3 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 transition">
            <i class="fas fa-sync-alt mr-2"></i> Perpanjang
        </button>
        <button onclick="window.location.href='{{ route('profile') }}'" class="px-6 py-3 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
            <i class="fas fa-times mr-2"></i> Batal
        </button>
    </div>

    <!-- Container Lanjutkan Pembayaran -->
    <div id="continue-payment-container" class="mt-4 text-center"></div>

</div>

<!-- SweetAlert2 & Midtrans Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    let latestSnapToken = null;

    function payForPerpanjang(noBon) {
        const totalPerpanjang = document.getElementById("total-perpanjang-" + noBon).value;

        fetch('/nasabah/process-perpanjang-payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                no_bon: noBon,
                amount: totalPerpanjang,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                latestSnapToken = data.snap_token;
                localStorage.setItem('pending_payment', JSON.stringify({ snap_token: data.snap_token, order_id: data.order_id }));

                snap.pay(data.snap_token, {
                    onSuccess: () => {
                        Swal.fire('Berhasil!', 'Perpanjangan berhasil dibayar.', 'success');
                        localStorage.removeItem('pending_payment');
                        window.location.href = '/nasabah/dashboard';
                    },
                    onPending: () => {
                        Swal.fire('Pending', 'Pembayaran sedang diproses.', 'info');
                    },
                    onError: () => {
                        Swal.fire('Gagal', 'Terjadi kesalahan pembayaran.', 'error');
                    },
                    onClose: () => handlePaymentCancel(data.order_id)
                });
            } else {
                Swal.fire('Gagal', 'Tidak dapat memproses pembayaran.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan di server.', 'error');
        });
    }

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
                fetch('/nasabah/cancel-payment', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ order_id: orderId })
                }).then(() => {
                    localStorage.removeItem('pending_payment');
                    Swal.fire('Dibatalkan', 'Pembayaran telah dibatalkan.', 'info');
                });
            } else {
                showContinueButton();
            }
        });
    }

    function showContinueButton() {
        const container = document.getElementById('continue-payment-container');
        container.innerHTML = `
            <button onclick="resumeSnap()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                Lanjutkan Pembayaran
            </button>
        `;
    }

    function resumeSnap() {
        const stored = JSON.parse(localStorage.getItem('pending_payment'));
        if (stored && stored.snap_token) {
            snap.pay(stored.snap_token, {
                onSuccess: () => {
                    localStorage.removeItem('pending_payment');
                    Swal.fire('Berhasil!', 'Perpanjangan berhasil dibayar.', 'success');
                    window.location.href = '/nasabah/dashboard';
                }
            });
        }
    }

    document.getElementById('confirmPerpanjangBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Konfirmasi Perpanjangan',
            text: 'Apakah Anda yakin ingin memperpanjang barang ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Perpanjang',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                const noBon = "{{ $barangGadai->no_bon }}";
                payForPerpanjang(noBon);
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

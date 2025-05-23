@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="text-center mb-10">
        <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600 mb-3">Konfirmasi Perpanjangan Gadai</h2>
        <p class="text-gray-600 text-lg">Pastikan data berikut sudah benar sebelum melanjutkan.</p>
    </div>

    <!-- Single Column Layout -->
    <div class="space-y-8">
        <!-- Data Nasabah Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transform hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold ml-4 bg-gradient-to-r from-blue-600 to-blue-800 text-transparent bg-clip-text">Data Nasabah</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                    <i class="fas fa-user-circle text-blue-500 text-xl mr-3"></i>
                    <p><span class="font-semibold text-gray-700">Nama:</span> <span class="text-gray-800">{{ $barangGadai->nasabah->nama }}</span></p>
                </div>
                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                    <i class="fas fa-id-card text-blue-500 text-xl mr-3"></i>
                    <p><span class="font-semibold text-gray-700">NIK:</span> <span class="text-gray-800">{{ $barangGadai->nasabah->nik }}</span></p>
                </div>
                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                    <i class="fas fa-map-marker-alt text-blue-500 text-xl mr-3"></i>
                    <p><span class="font-semibold text-gray-700">Alamat:</span> <span class="text-gray-800">{{ $barangGadai->nasabah->alamat }}</span></p>
                </div>
                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                    <i class="fas fa-phone text-blue-500 text-xl mr-3"></i>
                    <p><span class="font-semibold text-gray-700">No Telp:</span> <span class="text-gray-800">{{ $barangGadai->nasabah->telepon }}</span></p>
                </div>
            </div>
        </div>

        <!-- Detail Barang Gadai Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transform hover:scale-[1.01] transition-all duration-300">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fas fa-box-open text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold ml-4 bg-gradient-to-r from-green-600 to-green-800 text-transparent bg-clip-text">Detail Barang Gadai</h3>
            </div>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-box text-green-500 text-xl mr-3"></i>
                        <p><span class="font-semibold text-gray-700">Nama Barang:</span> <span class="text-gray-800">{{ $barangGadai->nama_barang }}</span></p>
                    </div>
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-receipt text-green-500 text-xl mr-3"></i>
                        <p><span class="font-semibold text-gray-700">No Bon:</span> <span class="text-gray-800"><em>Menunggu Admin</em></span></p>
                    </div>
                </div>

                <div class="border-t border-gray-200 my-6"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-tag text-green-500 text-xl mr-3"></i>
                        <p><span class="font-semibold text-gray-700">Harga Gadai Saat Ini:</span> <span class="text-gray-800">Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</span></p>
                    </div>
                    @if ($cicilan > 0)
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-money-bill-wave text-green-500 text-xl mr-3"></i>
                        <p><span class="font-semibold text-gray-700">Cicilan Dibayarkan:</span> <span class="text-gray-800">Rp {{ number_format($cicilan, 0, ',', '.') }}</span></p>
                    </div>
                    @endif
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-calculator text-green-500 text-xl mr-3"></i>
                        <p>
                            <span class="font-semibold text-gray-700">Harga Gadai Baru:</span>
                            <i class="bi bi-info-circle d-none d-md-inline ml-1 text-green-500"
                            data-bs-toggle="tooltip"
                            title="Harga Gadai Lama Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} - Cicilan Rp {{ number_format($cicilan, 0, ',', '.') }}">
                            </i>
                            <span class="text-gray-800">Rp {{ number_format($barangGadai->harga_gadai - $cicilan, 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-calendar-alt text-green-500 text-xl mr-3"></i>
                        <p><span class="font-semibold text-gray-700">Tenor:</span> <span class="text-gray-800">{{ $tenors }} hari → {{ $tenor }} hari</span></p>
                    </div>
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-clock text-green-500 text-xl mr-3"></i>
                        <p><span class="font-semibold text-gray-700">Jatuh Tempo Baru:</span> <span class="text-gray-800">{{ $tempobaru }}</span></p>
                    </div>
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-percentage text-green-500 text-xl mr-3"></i>
                        <p>
                            <span class="font-semibold text-gray-700">Bunga:</span>
                            <i class="bi bi-info-circle d-none d-md-inline ml-1 text-green-500"
                            data-bs-toggle="tooltip"
                            title="Bunga {{ $bungaTenorBaru->bunga_percent }}% x Harga Gadai Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}">
                            </i>
                            <span class="text-gray-800">{{ $bungaTenorBaru->bunga_percent }}% (Rp {{ number_format($bunga_persen_baru, 0, ',', '.') }})</span>
                        </p>
                    </div>
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-exclamation-circle text-green-500 text-xl mr-3"></i>
                        <p><span class="font-semibold text-gray-700">Telat:</span> <span class="text-gray-800">{{ $barangGadai->telat }} hari</span></p>
                    </div>
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-white rounded-xl hover:shadow-md transition-all duration-300">
                        <i class="fas fa-money-bill text-green-500 text-xl mr-3"></i>
                        <p>
                            <span class="font-semibold text-gray-700">Denda:</span>
                            <i class="bi bi-info-circle d-none d-md-inline ml-1 text-green-500"
                            data-bs-toggle="tooltip"
                            title="Denda = {{ $barangGadai->telat }} hari × 1% × Rp{{ number_format($barangGadai->harga_gadai, 0, ',', '.') }} = Rp{{ number_format($denda, 0, ',', '.') }}">
                            </i>
                            <span class="text-gray-800">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl mt-6 shadow-inner">
                    <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-green-800">
                        Total Perpanjangan:
                        <i class="bi bi-info-circle d-none d-md-inline ml-1"
                        data-bs-toggle="tooltip"
                        title="Total = Rp {{ number_format($bunga_persen_baru, 0, ',', '.') }} (bunga) + Rp {{ number_format($denda, 0, ',', '.') }} (denda){{ $cicilan > 0 ? ' + Rp ' . number_format($cicilan, 0, ',', '.') . ' (cicilan)' : '' }}">
                        </i>
                        Rp {{ number_format($totalPerpanjang + $cicilan, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi Tombol -->
    <div class="mt-10 flex justify-center space-x-4">
        <input type="hidden" id="no-bon-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->no_bon }}">
        <input type="hidden" id="total-perpanjang-{{ $barangGadai->no_bon }}" value="{{ $totalPerpanjang }}">
        <input type="hidden" id="denda-{{ $barangGadai->no_bon }}" value="{{ $barangGadai->denda }}">

        <button id="confirmPerpanjangBtn" class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-102 transition-all duration-300 font-medium text-base">
            <i class="fas fa-sync-alt mr-2"></i> Perpanjang
        </button>
        <button onclick="window.location.href='{{ route('profile') }}'" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-md hover:shadow-lg transform hover:scale-102 transition-all duration-300 font-medium text-base">
            <i class="fas fa-times mr-2"></i> Batal
        </button>
    </div>

    <!-- Container Lanjutkan Pembayaran -->
    <div id="continue-payment-container" class="mt-6 text-center"></div>
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

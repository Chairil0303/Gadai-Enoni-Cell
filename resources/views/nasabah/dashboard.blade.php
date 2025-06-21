@extends('layouts.app')

@section('content')

<!-- Alert Component untuk Pembayaran Pending -->
<div id="payment-alerts" class="mb-6"></div>

<!-- Form Profile -->
<div class=" pl-2">
    <div class="bg-[#A0D683] rounded-lg p-6 mb-6 flex items-center justify-left">
        <!-- Illustration Section -->
        <div>
            <img src="{{ asset('images/profile.png') }}" alt="Illustration" class="w-24 rounded-full">
        </div>

    <!-- Text Section -->
    <div class="ml-5">
        <h2 class="text-3xl font-bold mb-1">Hi, {{ ucwords(strtolower($nasabah->nama)) }}</h2>
        <p class="text-gray-600">Selamat datang di Pegadaian Enoni Cell, {{ auth()->user()->cabang->nama_cabang  }},
        </p>
    </div>
</div>

@foreach ($barangGadai as $barang)

<div class="bg-white shadow-lg rounded-lg p-6 transform hover:scale-105 transition-transform duration-300">
    <input type="hidden" id="created-at-{{ $barang->id }}" value="{{ $barang->created_at }}">
    <input type="hidden" id="tempo-{{ $barang->id }}" value="{{ $barang->tempo }}">
    <div id="countdown-{{ $barang->id }}" data-status="{{ $barang->status }}" class="hidden"></div>

    <div class="flex items-center space-x-4">
        <div class="relative w-28 h-28">
            <svg class="w-full h-full" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" stroke="#ddd" stroke-width="10" fill="none" />
                <circle id="progress-{{ $barang->id }}" cx="50" cy="50" r="45" stroke="#4CAF50" stroke-width="10" fill="none" stroke-dasharray="282.6" stroke-dashoffset="0" stroke-linecap="round" />
            </svg>
            <div class="absolute inset-0 flex items-center justify-center text-lg font-semibold text-gray-700">
                <span id="progress-text-{{ $barang->id }}">0 Hari</span>
            </div>
        </div>

        <div class="flex-1">
            <p class="text-lg font-semibold text-gray-800">Total Tebus</p>

            <p class="text-xl font-bold text-green-600">
                Rp {{ number_format(
                    $barang->harga_gadai +
                    (($barang->bunga / 100) * $barang->harga_gadai) + $barang->denda,
                    0, ',', '.'
                ) }}
            </p>
            {{-- <p><strong>No Bon:</strong> {{ $barang->no_bon }}</p> --}}
            @if (Str::startsWith($barang->no_bon, 'DM-') || Str::endsWith($barang->no_bon, '-DM'))
                <p><strong>No Bon:</strong> Sedang menunggu verifikasi admin</p>
            @else
                <p><strong>No Bon:</strong> {{ $barang->no_bon }}</p>
            @endif

            <p><strong>Barang Gadai :</strong> {{ $barang->nama_barang }}</p>
            <p class="text-sm text-gray-600">Barang ini akan jatuh Tempo  di Hari {{$barang->tempo_formatted }} </p>
        </div>
    </div>
</div>
@endforeach



     <div class="bg-white py-2 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-4 lg:max-w-4xl">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-4">

                    <!-- Profile Section -->
                    <a href="{{ route('nasabah.my-profile') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                        <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                            <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                                <i class="fas fa-user-circle text-white text-xl"></i>
                            </div>
                            <span class="pl-12">Profil Saya</span>
                        </dt>
                        <dd class="mt-2 pt-3 text-base text-gray-600">Lihat dan kelola informasi profil Anda.</dd>
                    </a>

                    <!-- Syarat & Ketentuan Section -->
                    <a href="{{ route('nasabah.terms') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                        <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                            <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                                <i class="fas fa-file-contract text-white text-xl"></i>
                            </div>
                            <span class="pl-12">Syarat & Ketentuan</span>
                        </dt>
                        <dd class="mt-2 pt-3 text-base text-gray-600">Baca syarat dan ketentuan layanan gadai.</dd>
                    </a>

                    @foreach($barangGadai as $barang)
                    <!-- Tebus Gadai -->
                    <a href="{{ route('nasabah.konfirmasi', $barang->no_bon) }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                        <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                            <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                                <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <span class="pl-12">Tebus Gadai</span>
                        </dt>
                        <dd class="mt-2 pt-3 text-base text-gray-600">Lakukan pembayaran untuk menebus barang yang telah digadaikan.</dd>
                    </a>
                    @endforeach

                    @foreach ($barangGadai as $barang)


                    <!-- Perpanjang Gadai -->
                    <a href="{{ route('nasabah.perpanjang.details', $barang->no_bon) }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                        <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                            <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                                <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </div>
                            <span class="pl-12">Perpanjang Gadai</span>
                        </dt>
                        <dd class="mt-2 pt-3 text-base text-gray-600">Tambahkan jangka waktu untuk gadai barang.</dd>
                    </a>
                    @endforeach

                    <!-- Lelangan -->
                    <a href="{{ route('nasabah.lelang') }}"  class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                        <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                            <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                                <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
                                </svg>
                            </div>
                            <span class="pl-12">Lelangan</span>
                        </dt>
                        <dd class="mt-2 pt-3 text-base text-gray-600">Lelangan langsung ke koperasi dengan harga terbaik.</dd>
                    </a>
                </dl>
            </div>
        </div>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("div[id^='countdown-']").forEach(function(element) {
        var id = element.id.replace("countdown-", "");
        var status = element.getAttribute("data-status");
        var progressCircle = document.getElementById("progress-" + id);
        var progressText = document.getElementById("progress-text-" + id);

        if (status && status.trim().toLowerCase() === "ditebus") {
            element.innerHTML = `<span class='text-green-600'>Sudah Ditebus</span>`;
            progressCircle.style.stroke = "#4CAF50";
            progressText.textContent = "Sudah Ditebus";
            return;
        }

        var createdAt = new Date(document.getElementById("created-at-" + id).value);
        var tempo = new Date(document.getElementById("tempo-" + id).value);

        function updateCountdown() {
            var now = new Date();
            var timeLeft = tempo - now;
            var totalDuration = tempo - createdAt;
            var daysLeft = Math.ceil(timeLeft / (1000 * 60 * 60 * 24));
            var isLate = daysLeft < 0;
            var overdueDays = Math.abs(daysLeft);
            var percentage = ((timeLeft / totalDuration) * 100).toFixed(2);
            var dashOffset = (282.6 * (100 - percentage)) / 100;

            if (isLate) {
                progressCircle.style.stroke = "#8B0000";
                element.innerHTML = `<span class='text-red-600'>Telat ${overdueDays} hari</span>`;
                progressText.textContent = `Telat ${overdueDays} Hari`;
                progressCircle.style.strokeDashoffset = 282.6;
            } else {
                progressCircle.style.stroke = daysLeft >= 14 ? "#4CAF50" : daysLeft >= 7 ? "#FFC107" : "#F44336";
                element.innerHTML = `${daysLeft} hari`;
                progressText.textContent = `${daysLeft} Hari`;
                progressCircle.style.strokeDashoffset = dashOffset;
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});

// Payment Alert System
class PaymentAlertSystem {
    constructor() {
        this.alertContainer = document.getElementById('payment-alerts');
        this.checkInterval = 30000; // Check every 30 seconds
        this.init();
    }

    init() {
        this.checkPendingPayments();
        this.startPeriodicCheck();
    }

    async checkPendingPayments() {
        try {
            const response = await fetch('/nasabah/check-pending-payments', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.displayAlerts(data.alerts);
            }
        } catch (error) {
            console.error('Error checking pending payments:', error);
        }
    }

    displayAlerts(alerts) {
        if (!alerts || alerts.length === 0) {
            this.alertContainer.innerHTML = '';
            return;
        }

        let alertHTML = '';
        alerts.forEach(alert => {
            const alertClass = this.getAlertClass(alert.type);
            const icon = this.getAlertIcon(alert.type);

            alertHTML += `
                <div class="alert-item ${alertClass} p-4 mb-4 rounded-lg border-l-4 flex items-center justify-between" data-order-id="${alert.order_id}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            ${icon}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">${alert.message}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        ${this.getActionButtons(alert)}
                    </div>
                </div>
            `;
        });

        this.alertContainer.innerHTML = alertHTML;
        this.attachEventListeners();
    }

    getAlertClass(type) {
        switch (type) {
            case 'success':
                return 'bg-green-50 border-green-400 text-green-800';
            case 'warning':
                return 'bg-yellow-50 border-yellow-400 text-yellow-800';
            case 'info':
                return 'bg-blue-50 border-blue-400 text-blue-800';
            default:
                return 'bg-gray-50 border-gray-400 text-gray-800';
        }
    }

    getAlertIcon(type) {
        switch (type) {
            case 'success':
                return '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            case 'warning':
                return '<svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            case 'info':
                return '<svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
            default:
                return '<svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
        }
    }

    getActionButtons(alert) {
        switch (alert.action) {
            case 'refresh_status':
                return `
                    <button onclick="paymentAlertSystem.reprocessPayment('${alert.order_id}')"
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                        Refresh Status
                    </button>
                `;
            case 'remove_pending':
                return `
                    <button onclick="paymentAlertSystem.removeAlert('${alert.order_id}')"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                        Tutup
                    </button>
                `;
            case 'contact_admin':
                return `
                    <button onclick="paymentAlertSystem.contactAdmin()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        Hubungi Admin
                    </button>
                `;
            default:
                return '';
        }
    }

    attachEventListeners() {
        // Auto-hide alerts after 10 seconds
        setTimeout(() => {
            this.alertContainer.innerHTML = '';
        }, 10000);
    }

    async reprocessPayment(orderId) {
        try {
            const response = await fetch('/nasabah/reprocess-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order_id: orderId })
            });

            const data = await response.json();

            if (data.status === 'success') {
                this.showNotification('Status pembayaran berhasil diperbarui!', 'success');
                // Refresh halaman setelah 2 detik
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                this.showNotification(data.message || 'Gagal memperbarui status pembayaran', 'error');
            }
        } catch (error) {
            console.error('Error reprocessing payment:', error);
            this.showNotification('Terjadi kesalahan saat memproses pembayaran', 'error');
        }
    }

    removeAlert(orderId) {
        const alertElement = document.querySelector(`[data-order-id="${orderId}"]`);
        if (alertElement) {
            alertElement.remove();
        }
    }

    contactAdmin() {
        // Bisa diarahkan ke halaman kontak admin atau WhatsApp
        window.open('https://wa.me/6281234567890?text=Halo, saya memiliki masalah dengan pembayaran pending', '_blank');
    }

    showNotification(message, type) {
        // Simple notification system
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    startPeriodicCheck() {
        setInterval(() => {
            this.checkPendingPayments();
        }, this.checkInterval);
    }
}

// Initialize payment alert system
const paymentAlertSystem = new PaymentAlertSystem();
</script>

@endsection

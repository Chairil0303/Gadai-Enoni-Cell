@extends('layouts.app')

@section('content')

    <!-- Sidebar -->
    <!-- <aside class="w-1/4 pr-8 border-r border-gray-300">
        <h2 class="text-xl font-bold mb-6">User Profile</h2>
        <nav class="space-y-4">
            <a href="#" class="flex items-center text-gray-600 hover:text-orange-500">
                <i class="fas fa-user mr-2"></i> User info
            </a>
            <a href="#" class="flex items-center text-gray-600 hover:text-orange-500">
                <i class="fas fa-heart mr-2"></i> Favorites
            </a>
            <a href="#" class="flex items-center text-gray-600 hover:text-orange-500">
                <i class="fas fa-list mr-2"></i> Watchlist
            </a>
            <a href="#" class="flex items-center text-gray-600 hover:text-orange-500">
                <i class="fas fa-cog mr-2"></i> Setting
            </a>
            <a href="#" class="flex items-center text-gray-600 hover:text-orange-500">
                <i class="fas fa-bell mr-2"></i> Notifications
            </a>
            <a href="#" class="flex items-center text-red-500 mt-6">
                <i class="fas fa-sign-out-alt mr-2"></i> Log out
            </a>
        </nav>
    </aside> -->



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
    <div class="bg-white shadow-lg rounded-lg p-6 transform hover:scale-135 transition-transform duration-300">
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
            <div>
                <p class="text-lg font-semibold text-gray-800">💰 Total Tebus</p>
                <p class="text-xl font-bold text-green-600">
                    Rp {{ number_format(
                        $barang->harga_gadai +
                        (($barang->bunga / 100) * $barang->harga_gadai) + $barang->denda,
                        0, ',', '.'
                    ) }}
                </p>
                <p><strong>No Bon:</strong> {{ $barang->no_bon }}</p>
            </div>
        </div>
    </div>
    @endforeach


     <div class="bg-white py-2 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-4 lg:max-w-4xl">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-4">
                    <!-- Terima Gadai -->
                    <a href="{{ route('barang_gadai.create') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                        <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                            <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                                <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                </svg>
                            </div>
                            <span class="pl-12">Taksir Harga Gadai</span>
                        </dt>
                        <dd class="mt-2 pt-3 text-base text-gray-600">Perkirakan harga gadai barang anda di sini</dd>
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
                    <!-- Perpanjang Gadai -->
                    <a href="{{ url('/transaksi_gadai/perpanjang_gadai') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
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


                    <!-- Lelangan -->
                    <a href="{{ url('/transaksi_gadai/terima_jual') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
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
</script>

@endsection

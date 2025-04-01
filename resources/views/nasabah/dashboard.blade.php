@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Nasabah</h1>
    @foreach ($barangGadai as $barang)


    <div class="relative w-32 h-32">
        <svg class="w-full h-full" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" stroke="#ddd" stroke-width="10" fill="none" />
            <circle id="progress-{{ $barang->id }}" cx="50" cy="50" r="45" stroke="#4CAF50" stroke-width="10" fill="none" stroke-dasharray="282.6" stroke-dashoffset="0" stroke-linecap="round" />
        </svg>
        <div class="absolute inset-0 flex items-center justify-center text-xl font-semibold text-gray-700">
            <span id="progress-text-{{ $barang->id }}">0 Hari</span>
        </div>
    </div>

    <input type="hidden" id="created-at-{{ $barang->id }}" value="{{ $barang->created_at }}">
    <input type="hidden" id="tempo-{{ $barang->id }}" value="{{ $barang->tempo }}">
</div>
@endforeach

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">🧑 Data Nasabah</h2>
            <p class="mt-2"><strong>Nama:</strong> {{ $nasabah->nama }}</p>
            <p><strong>NIK:</strong> {{ $nasabah->nik }}</p>
            <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
            <p><strong>No. Telp:</strong> {{ $nasabah->telepon }}</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @foreach ($barangGadai as $barang)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">📦 Data Barang Gadai</h2>
                <p class="mt-2"><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
                <p><strong>No Bon:</strong> {{ $barang->no_bon }}</p>
                <p><strong>Harga Gadai:</strong> Rp {{ number_format($barang->harga_gadai, 0, ',', '.') }}</p>
                <p><strong>Tenor:</strong> {{ $barang->tenor }} hari</p>
                <p><strong>Jatuh Tempo:</strong> {{ $barang->tempo }}</p>
                <p><strong>Waktu Pembayaran:</strong> <span id="countdown-{{ $barang->id }}" class="font-bold text-blue-600"></span></p>

                <!-- Progress Circle -->

            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("[id^='countdown-']").forEach(function(element) {
        var id = element.id.replace("countdown-", "");
        var createdAt = new Date(document.getElementById("created-at-" + id).value);
        var tempo = new Date(document.getElementById("tempo-" + id).value);

        function updateCountdown() {
            var now = new Date();
            var timeLeft = tempo - now;
            var totalDuration = tempo - createdAt;

            var daysLeft = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            var isLate = daysLeft < 0;
            var overdueDays = Math.abs(daysLeft);
            var percentage = ((timeLeft / totalDuration) * 100).toFixed(2);
            var dashOffset = (282.6 * (100 - percentage)) / 100;

            var progressCircle = document.getElementById("progress-" + id);
            var progressText = document.getElementById("progress-text-" + id);

            if (isLate) {
                progressCircle.style.stroke = "#8B0000"; // Merah tua untuk keterlambatan
                element.innerHTML = `<span class='text-red-600'>Telat ${overdueDays} hari</span>`;
                progressText.textContent = `Telat ${overdueDays} Hari`;
                progressCircle.style.strokeDashoffset = 282.6;
            } else {
                if (daysLeft >= 14) {
                    progressCircle.style.stroke = "#4CAF50"; // Hijau
                } else if (daysLeft >= 7) {
                    progressCircle.style.stroke = "#FFC107"; // Kuning
                } else {
                    progressCircle.style.stroke = "#F44336"; // Merah
                }
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
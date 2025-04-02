@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Nasabah</h1>
    <div class="mt-12"></div>
    @foreach ($barangGadai as $barang)


    <div class="relative w-48 h-48">
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
            <p class="mt-2"><strong>Nama:</strong> {{ ucwords(strtolower($nasabah->nama)) }}</p>
            <p><strong>NIK:</strong> {{ $nasabah->nik }}</p>
            <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
            <p><strong>No. Telp:</strong> {{ $nasabah->telepon }}</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @foreach ($barangGadai as $barang)
            <div class="bg-white shadow-md rounded-lg p-6 grid grid-cols-2 gap-4 mt-2">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">📦 Data Barang Gadai</h2>
                <span></span>
                <p class="mt-2"><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
                <p><strong>No Bon:</strong> {{ $barang->no_bon }}</p>
                <p><strong>Tanggal Gadai: </strong>{{ $barang->created_at->translatedFormat('l,d F Y') }}</p>
                <p><strong>Harga Gadai:</strong> Rp {{ number_format($barang->harga_gadai, 0, ',', '.') }}</p>
                <p><strong>Tenor:</strong> {{ $barang->tenor }} hari</p>
                <p><strong>Bunga :</strong>Rp {{ number_format(($barang->bunga/100) * $barang->harga_gadai,0,',', '.') }}</p>

                <p><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($barang->tempo)->translatedFormat('l, d F Y') }}</p>
                {{-- <p><strong>Waktu Pembayaran:</strong> <span id="countdown-{{ $barang->id }}" class="font-bold text-blue-600"></span></p> --}}
                <p><strong>Waktu Pembayaran:</strong>
                    <span id="countdown-{{ $barang->id }}" class="font-bold text-blue-600"
                          data-status="{{ $barang->status }}">
                    </span>
                </p>

            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("[id^='countdown-']").forEach(function(element) {
        var id = element.id.replace("countdown-", "");
        var status = element.getAttribute("data-status");

        console.log("Barang ID:", id, "Status:", status); // Debugging

        var progressCircle = document.getElementById("progress-" + id);
        var progressText = document.getElementById("progress-text-" + id);

        if (status && status.trim().toLowerCase() === "ditebus") {
            element.innerHTML = `<span class='text-green-600'>Sudah Ditebus</span>`;

            if (progressCircle) {
                progressCircle.style.stroke = "#4CAF50"; // Warna hijau
                progressCircle.style.strokeDashoffset = 0;
            }
            if (progressText) {
                progressText.textContent = "Sudah Ditebus";
                progressText.classList.add("text-green-600");
            }
            return; // Stop script kalau sudah ditebus
        }

        var createdAtElement = document.getElementById("created-at-" + id);
        var tempoElement = document.getElementById("tempo-" + id);

        if (!createdAtElement || !tempoElement) {
            console.warn(`Data tidak lengkap untuk barang ID ${id}`);
            return;
        }

        var createdAt = new Date(createdAtElement.value);
        var tempo = new Date(tempoElement.value);

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
                progressCircle.style.stroke = "#8B0000"; // Merah tua
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
{{-- dashboard nasbah --}}

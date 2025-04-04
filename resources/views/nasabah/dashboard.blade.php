@extends('layouts.app')


@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Nasabah</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($barangGadai as $barang)
            <div class="bg-white shadow-lg rounded-lg p-6 transform hover:scale-135 transition-transform duration-300">
                <div class="flex items-center space-x-4">
                    <div class="relative w-28   h-28">
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
                                (($barang->bunga / 100) * $barang->harga_gadai) +$barang->denda,
                                0, ',', '.'
                            ) }}
                        </p>

                        {{-- <p class="text-xl font-bold text-green-600">Rp {{ number_format($barang->harga_gadai + (($barang->bunga / 100) * $barang->harga_gadai + denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat), 0, ',', '.') }}</p> --}}
                    </div>
                </div>
                <input type="hidden" id="created-at-{{ $barang->id }}" value="{{ $barang->created_at }}">
                <input type="hidden" id="tempo-{{ $barang->id }}" value="{{ $barang->tempo }}">
            </div>


            @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">🧑 Data Nasabah</h2>
            <p class="mt-2"><strong>Nama:</strong> {{ ucwords(strtolower($nasabah->nama)) }}</p>
            <p><strong>NIK:</strong> {{ $nasabah->nik }}</p>
            <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
            <p><strong>No. Telp:</strong> {{ $nasabah->telepon }}</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">📦 Data Barang Gadai</h2>
            @foreach ($barangGadai as $barang)
            <div class="p-4 border-b last:border-none">
                <p><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
                <p><strong>No Bon:</strong> {{ $barang->no_bon }}</p>
                <p><strong>Tanggal Gadai:</strong> {{ $barang->created_at->translatedFormat('l, d F Y') }}</p>
                <p><strong>Tenor:</strong> {{ $barang->tenor }} hari</p>
                <p><strong>Harga Gadai:</strong> Rp {{ number_format($barang->harga_gadai, 0, ',', '.') }}</p>
                <p><strong>Bunga:</strong> Rp {{ number_format(max(0, ($barang->bunga / 100) * $barang->harga_gadai), 0, ',', '.') }}</p>
                <p><strong>Denda: </strong>Rp {{number_format($barang->denda)}}<p>
                <p><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($barang->tempo)->translatedFormat('l, d F Y') }}</p>
                <p><strong>Waktu Pembayaran:</strong> <span id="countdown-{{ $barang->id }}" class="font-bold text-blue-600" data-status="{{ $barang->status }}"></span></p>
                <p><strong>Total Penebusan : </strong>Rp {{ number_format($barang->harga_gadai +(($barang->bunga / 100) * $barang->harga_gadai) + $barang->denda,0, ',', '.') }}</p>
            </div>
            @endforeach
            @foreach ($barangGadai as $barang)



<!-- Button untuk tebus -->
<input type="hidden" id="no-bon-{{ $barang->no_bon }}" value="{{ $barang->no_bon }}">
<input type="hidden" id="total-tebus-{{ $barang->no_bon }}" value="{{ $barang->harga_gadai + (($barang->bunga / 100) * $barang->harga_gadai) + $barang->denda }}">
<input type="hidden" id="denda-{{ $barang->no_bon }}" value="{{ $barang->denda }}">
<button onclick="payWithMidtrans('{{ $barang->no_bon }}')" class="bg-green-500 text-white px-4 py-2 rounded">
    Tebus Sekarang
</button>
@endforeach

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
   function payWithMidtrans(noBon) {
    var noBonElement = document.getElementById("no-bon-" + noBon);
    var totalTebusElement = document.getElementById("total-tebus-" + noBon);
    var dendaElement = document.getElementById("denda-" + noBon);

    if (!noBonElement || !totalTebusElement) {
        console.error('Elemen tidak ditemukan untuk barang dengan no_bon: ' + noBon);
        return;
    }

    var amount = totalTebusElement.value;

    fetch('/nasabah/process-payment', {
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
        if (data.status === 'success') {
            window.location.href = data.redirect_url;
        } else {
            alert('Terjadi kesalahan: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll("[id^='countdown-']").forEach(function(element) {
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

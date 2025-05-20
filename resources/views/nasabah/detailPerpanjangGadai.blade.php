@extends('layouts.app')

@section('title', 'Detail Perpanjangan')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg p-8 rounded-xl mt-8 transform transition-all duration-300 hover:shadow-2xl">
    <h2 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-4">Detail Perpanjangan Gadai</h2>

    <!-- Info Nasabah -->
    <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
        <h3 class="text-lg font-semibold text-blue-800 mb-3">Informasi Nasabah</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p><span class="font-medium">Nama Nasabah:</span> {{ $nasabah->nama }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <p><span class="font-medium">No HP:</span> {{ $nasabah->telepon }}</p>
            </div>
        </div>
    </div>

    <!-- Info Barang Gadai -->
    <div class="mb-6 bg-green-50 p-4 rounded-lg border border-green-100">
        <h3 class="text-lg font-semibold text-green-800 mb-3">Informasi Barang Gadai</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p><span class="font-medium">Nama Barang:</span> {{ $barangGadai->nama_barang }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p><span class="font-medium">Harga Gadai:</span> Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p><span class="font-medium">Jatuh Tempo:</span> {{ \Carbon\Carbon::parse($barangGadai->tempo)->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Tombol Pilih Metode -->
    <div class="flex gap-4 mb-6">
        <button onclick="showPerpanjangForm('biasa')" id="btn-biasa" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Perpanjang Biasa</span>
        </button>
        <button onclick="showPerpanjangForm('cicil')" id="btn-cicil" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Perpanjang + Nyicil</span>
        </button>
    </div>

    <!-- Form Perpanjang Biasa -->
    <div id="form-biasa" class="hidden mb-6 bg-blue-50 p-6 rounded-lg border border-blue-100 transition-all duration-300">
        <label for="tenorBaruBiasa" class="block mb-2 font-semibold text-blue-800">Tenor Baru (hari):</label>
        <select id="tenorBaruBiasa" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm transition-all duration-300" required>
            @foreach ($tenors as $tenor)
                <option value="{{ $tenor->tenor }}">{{ $tenor->tenor }} Hari</option>
            @endforeach
        </select>
    </div>

    <!-- Form Perpanjang + Nyicil -->
    <div id="form-cicil" class="hidden mb-6 bg-green-50 p-6 rounded-lg border border-green-100 transition-all duration-300">
        <label for="tenorBaruCicil" class="block mb-2 font-semibold text-green-800">Tenor Baru (hari):</label>
        <select id="tenorBaruCicil" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm transition-all duration-300" required>
            <option value="" disabled selected>Pilih Tenor</option>
            @foreach ($tenors as $tenor)
                <option value="{{ $tenor->tenor }}">{{ $tenor->tenor }} Hari</option>
            @endforeach
        </select>

        <label for="cicilan" class="block mt-4 mb-2 font-semibold text-green-800">Bayar Cicilan Dari Harga Gadai:</label>
        <div class="relative">
            <span class="absolute left-4 top-2 text-gray-500">Rp </span>
            <input type="text" id="cicilan" class="pl-10 border px-3 py-2 rounded-lg w-full focus:ring-2 focus:ring-green-200 focus:border-green-500 transition-all duration-300" placeholder=" 100.000" />
        </div>
        <small class="text-gray-500 mt-1 block">Minimal Rp100.000</small>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-8 flex justify-end gap-4">
        <button id="btn-konfirmasi-biasa" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 hidden flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Konfirmasi Perpanjang</span>
        </button>
        <button id="btn-konfirmasi-cicil" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 hidden flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Konfirmasi Perpanjang + Nyicil</span>
        </button>
        <a href="{{ route('profile') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span>Batal</span>
        </a>
    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<!-- Script -->
<script>
    // Format input rupiah saat user mengetik
    const cicilanInput = document.getElementById('cicilan');
    cicilanInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/[^,\d]/g, '').toString();
        if (value) {
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
            e.target.value = formatted;
        } else {
            e.target.value = '';
        }
    });

    // Helper: convert "Rp 100.000" → 100000
    function parseRupiah(rp) {
        return parseInt(rp.replace(/[^0-9]/g, ''), 10);
    }

    function showPerpanjangForm(type) {
        const btnBiasa = document.getElementById('btn-biasa');
        const btnCicil = document.getElementById('btn-cicil');
        const formBiasa = document.getElementById('form-biasa');
        const formCicil = document.getElementById('form-cicil');
        const confirmBiasa = document.getElementById('btn-konfirmasi-biasa');
        const confirmCicil = document.getElementById('btn-konfirmasi-cicil');

        if (type === 'biasa') {
            formBiasa.classList.remove('hidden');
            formCicil.classList.add('hidden');
            confirmBiasa.classList.remove('hidden');
            confirmCicil.classList.add('hidden');
            btnBiasa.classList.add('bg-blue-700');
            btnCicil.classList.remove('bg-green-700');
        } else {
            formBiasa.classList.add('hidden');
            formCicil.classList.remove('hidden');
            confirmBiasa.classList.add('hidden');
            confirmCicil.classList.remove('hidden');
            btnBiasa.classList.remove('bg-blue-700');
            btnCicil.classList.add('bg-green-700');
        }
    }

    // Konfirmasi Perpanjang Biasa
    document.getElementById('btn-konfirmasi-biasa').addEventListener('click', function () {
        const tenor = document.getElementById('tenorBaruBiasa').value;
        const url = new URL("{{ route('nasabah.konfirmasi.Perpanjang') }}");

        url.searchParams.append('no_bon', "{{ $barangGadai->no_bon }}");
        url.searchParams.append('tenor', tenor);
        url.searchParams.append('type', 'biasa');
        window.location.href = url.toString();
    });

    // Konfirmasi Perpanjang + Nyicil
    document.getElementById('btn-konfirmasi-cicil').addEventListener('click', function () {
        const tenor = document.getElementById('tenorBaruCicil').value;
        const rawCicilan = document.getElementById('cicilan').value;
        const cicilan = parseRupiah(rawCicilan);

        if (!tenor || !cicilan) {
            Swal.fire({
                icon: 'warning',
                title: 'Data belum lengkap',
                text: 'Silakan lengkapi tenor dan cicilan terlebih dahulu.'
            });
            return;
        }

        if (cicilan < 100000) {
            Swal.fire({
                icon: 'error',
                title: 'Nominal terlalu kecil',
                text: 'Cicilan minimal Rp100.000'
            });
            return;
        }

        const url = new URL("{{ route('nasabah.konfirmasi.Perpanjang') }}");
        url.searchParams.append('no_bon', "{{ $barangGadai->no_bon }}");
        url.searchParams.append('tenor', tenor);
        url.searchParams.append('cicilan', cicilan);
        url.searchParams.append('type', 'cicil');
        window.location.href = url.toString();
    });
</script>
@endsection

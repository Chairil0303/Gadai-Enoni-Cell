@extends('layouts.app')

@section('title', 'Detail Perpanjangan')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md p-6 rounded mt-6">
    <h2 class="text-2xl font-semibold mb-4">Detail Perpanjangan Gadai</h2>

    <!-- Info Nasabah -->
    <div class="mb-4">
        <p><strong>Nama Nasabah:</strong> {{ $nasabah->nama }}</p>
        <p><strong>No HP:</strong> {{ $nasabah->telepon }}</p>
    </div>

    <!-- Info Barang Gadai -->
    <div class="mb-4">
        <p><strong>Nama Barang:</strong> {{ $barangGadai->nama_barang }}</p>
        <p><strong>Harga Gadai:</strong> Rp {{ number_format($barangGadai->harga_gadai, 0, ',', '.') }}</p>
        <p><strong>Jatuh Tempo Lama:</strong> {{ \Carbon\Carbon::parse($barangGadai->jatuh_tempo)->translatedFormat('d F Y') }}</p>
    </div>

    <!-- Tombol Pilih Metode -->
    <div class="flex gap-2 mb-4">
        <button onclick="showPerpanjangForm('biasa')" id="btn-biasa" class="bg-blue-500 text-white px-4 py-2 rounded">Perpanjang Biasa</button>
        <button onclick="showPerpanjangForm('cicil')" id="btn-cicil" class="bg-green-500 text-white px-4 py-2 rounded">Perpanjang + Nyicil</button>
    </div>

    <!-- Form Perpanjang Biasa -->
    <div id="form-biasa" class="hidden mb-4">
        <label for="tenorBaruBiasa" class="block mb-1 font-semibold">Tenor Baru (hari):</label>
        <select id="tenorBaruBiasa" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" required>
            <option value="7">7 Hari</option>
            <option value="14">14 Hari</option>
            <option value="30">30 Hari</option>
        </select>
    </div>

    <!-- Form Perpanjang + Nyicil -->
    <div id="form-cicil" class="hidden mb-4">
        <label for="tenorBaruCicil" class="block mb-1 font-semibold">Tenor Baru (hari):</label>
        <select name="tenor" id="tenorBaruCicil" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" required>
            <option value="" disabled selected>Pilih Tenor</option>
            <option value="7">7 Hari</option>
            <option value="14">14 Hari</option>
            <option value="30">30 Hari</option>
        </select>

        <label for="cicilan" class="block mt-3 mb-1 font-semibold">Bayar Cicilan Dari Harga Gadai:</label>
        <input type="number" id="cicilan" class="border px-3 py-2 rounded w-full" min="0" max="{{ $barangGadai->harga_gadai }}">
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-6 flex justify-end gap-2">
        <button id="btn-konfirmasi-biasa" class="bg-blue-600 text-white px-4 py-2 rounded hidden">Konfirmasi Perpanjang</button>
        <button id="btn-konfirmasi-cicil" class="bg-green-600 text-white px-4 py-2 rounded hidden">Konfirmasi Perpanjang + Nyicil</button>
        <a href="{{ route('profile') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<!-- Script -->
<script>
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
        const cicilan = document.getElementById('cicilan').value;

        if (!tenor || cicilan === '') {
            alert('Silakan lengkapi tenor dan cicilan terlebih dahulu.');
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

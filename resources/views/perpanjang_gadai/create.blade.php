@extends('layouts.app')

@section('content')
<div class="mx-auto bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Form Perpanjang Gadai</h2>

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif




    <form action="{{ route('perpanjang_gadai.konfirmasi') }}" method="POST">
    @csrf

    <div class="row">

        <div class="col-md-6">
            <!-- No Bon Lama -->
            <div class="mb-4">
                <label for="no_bon_lama" class="block text-sm font-medium text-gray-700">No Bon Lama</label>
                <input type="text" name="no_bon_lama" id="no_bon_lama" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-300">
            </div>
            <!-- No Bon Baru -->
            <div class="mb-4">
                <label for="no_bon_baru" class="block text-sm font-medium text-gray-700">No Bon Baru</label>
                <input type="text" name="no_bon_baru" id="no_bon_baru" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-300">
            </div>
            <!-- Tenor -->
            <div class="mb-4">
                <label for="tenor" class="block text-sm font-medium text-gray-700">Tenor (hari)</label>
                <select name="tenor" id="tenor"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-300">
                    <option value="7">7 hari</option>
                    <option value="14">14 hari</option>
                    <option value="30">30 hari</option>
                </select>
            </div>

        </div>
        <div class="col-md-6">
                <!-- Harga Gadai Baru -->
                <div class="mb-4">
                    <label for="harga_gadai" class="block text-sm font-medium text-gray-700">Harga Gadai Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-700">Rp</span>
                        <input type="text" name="harga_gadai_display" id="harga_gadai_display"
                            class="pl-10 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-300"
                            placeholder="0" required>
                        <input type="hidden" name="harga_gadai" id="harga_gadai">
                    </div>
                </div>

                <!-- Tombol Lanjut -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Lanjut
                    </button>
                </div>

        </div>


    </div>
</form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const displayInput = document.getElementById('harga_gadai_display');
    const hiddenInput = document.getElementById('harga_gadai');

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function cleanNumber(angka) {
        return angka.replace(/\./g, '');
    }

    displayInput.addEventListener('input', function () {
        let clean = cleanNumber(this.value.replace(/[^0-9]/g, ''));
        this.value = formatRupiah(clean);
        hiddenInput.value = clean;
    });
});
</script>

@endsection

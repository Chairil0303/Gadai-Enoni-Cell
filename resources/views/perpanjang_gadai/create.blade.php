@extends('layouts.app')

@section('content')
<div class=" mx-auto p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-2xl font-semibold mb-6">Perpanjangan Gadai</h2>
    <hr>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('perpanjang_gadai.konfirmasi') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <!-- Bon Lama -->
                <div class="mb-4">
                    <label for="no_bon_lama" class="block text-sm font-medium text-gray-700">Bon Lama</label>
                    <input type="text" name="no_bon_lama" id="no_bon_lama" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" required>
                </div>

                <!-- Bon Baru -->
                <div class="mb-4">
                    <label for="no_bon_baru" class="block text-sm font-medium text-gray-700">Bon Baru</label>
                    <input type="text" name="no_bon_baru" id="no_bon_baru" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" required>
                </div>

                <!-- Tenor Baru -->
                <div class="mb-4">
                    <label for="tenor" class="block text-sm font-medium text-gray-700">Tenor Baru</label>
                    <select name="tenor" id="tenor" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" required>
                        <option value="7">7 Hari</option>
                        <option value="14">14 Hari</option>
                        <option value="30">30 Hari</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Jenis Perpanjangan -->
                <div class="mb-4">
                    <label for="jenis_perpanjangan" class="block text-sm font-medium text-gray-700">Jenis Perpanjangan</label>
                    <select name="jenis_perpanjangan" id="jenis_perpanjangan" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="tanpa_perubahan">Tanpa Penambahan/Pengurangan</option>
                        <option value="penambahan">Dengan Penambahan</option>
                        <option value="pengurangan">Dengan Pengurangan</option>
                    </select>
                </div>

                <!-- Penambahan -->
                <div class="mb-4 hidden" id="field_penambahan">
                    <label for="penambahan" class="block text-sm font-medium text-gray-700">Penambahan Harga Gadai (Rp)</label>
                    <input type="number" name="penambahan" id="penambahan" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" min="0">
                </div>

                <!-- Pengurangan -->
                <div class="mb-4 hidden" id="field_pengurangan">
                    <label for="pengurangan" class="block text-sm font-medium text-gray-700">Pengurangan Harga Gadai (Rp)</label>
                    <input type="number" name="pengurangan" id="pengurangan" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 text-sm" min="0">
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-success text-white font-semibold rounded-xl shadow-md hover:bg-indigo-700 transition duration-200">
                    Lanjutkan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Menunggu sampai halaman selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen-elemen terkait
        const jenisPerpanjanganSelect = document.getElementById("jenis_perpanjangan");
        const fieldPenambahan = document.getElementById("field_penambahan");
        const fieldPengurangan = document.getElementById("field_pengurangan");

        // Fungsi untuk menangani perubahan jenis perpanjangan
        jenisPerpanjanganSelect.addEventListener("change", function() {
            const jenisPerpanjangan = this.value;

            // Sembunyikan atau tampilkan field penambahan/pengurangan
            if (jenisPerpanjangan === "penambahan") {
                fieldPenambahan.classList.remove("hidden");
                fieldPengurangan.classList.add("hidden");
            } else if (jenisPerpanjangan === "pengurangan") {
                fieldPenambahan.classList.add("hidden");
                fieldPengurangan.classList.remove("hidden");
            } else {
                fieldPenambahan.classList.add("hidden");
                fieldPengurangan.classList.add("hidden");
            }
        });
    });
</script>

@endsection

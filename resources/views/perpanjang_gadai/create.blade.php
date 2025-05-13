@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-redo"></i> Perpanjangan Gadai</h4>
        </div>

        <div class="card-body bg-light px-4 py-5">

            @if ($errors->any())
            <div class="alert alert-danger shadow-sm rounded">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('perpanjang_gadai.submit') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <!-- Bon Lama -->
                        <div class="mb-4">
                            <label for="no_bon_lama" class="form-label text-success fw-semibold">Bon Lama</label>
                            <input type="text" name="no_bon_lama" id="no_bon_lama"
                                class="form-control rounded-3 shadow-sm" required>
                        </div>

                        <!-- Bon Baru -->
                        <div class="mb-4">
                            <label for="no_bon_baru" class="form-label text-success fw-semibold">Bon Baru</label>
                            <input type="text" name="no_bon_baru" id="no_bon_baru"
                                class="form-control rounded-3 shadow-sm" required>
                        </div>

                        <!-- Tenor -->
                        <div class="mb-4">
                            <label for="tenor" class="form-label text-success fw-semibold">Tenor Baru</label>
                            <select name="tenor" id="tenor"
                                class="form-select rounded-3 shadow-sm" required>
                                <option value="">-- Pilih Tenor --</option>
                                @foreach ($tenors as $tenor)
                                    <option value="{{ $tenor->tenor }}">
                                        {{ $tenor->tenor }} Hari - Bunga {{ $tenor->bunga_percent }}%
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Jenis Perpanjangan -->
                        <div class="mb-4">
                            <label for="jenis_perpanjangan" class="form-label text-success fw-semibold">Jenis Perpanjangan</label>
                            <select name="jenis_perpanjangan" id="jenis_perpanjangan"
                                class="form-select rounded-3 shadow-sm" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="tanpa_perubahan">Tanpa Penambahan/Pengurangan</option>
                                <option value="penambahan">Dengan Penambahan</option>
                                <option value="pengurangan">Dengan Pengurangan</option>
                            </select>
                        </div>

                        <!-- Penambahan -->
                        <div class="mb-4 d-none" id="field_penambahan">
                            <label for="penambahan" class="form-label text-success fw-semibold">Penambahan Harga Gadai (Rp)</label>
                            <input type="number" name="penambahan" id="penambahan"
                                class="form-control rounded-3 shadow-sm" min="0">
                        </div>

                        <!-- Pengurangan -->
                        <div class="mb-4 d-none" id="field_pengurangan">
                            <label for="pengurangan" class="form-label text-success fw-semibold">Pengurangan Harga Gadai (Rp)</label>
                            <input type="number" name="pengurangan" id="pengurangan"
                                class="form-control rounded-3 shadow-sm" min="0">
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="btn btn-success w-100 rounded-3 shadow-sm fw-semibold">
                                <i class="fas fa-arrow-right"></i> Lanjutkan
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const jenisPerpanjanganSelect = document.getElementById("jenis_perpanjangan");
        const fieldPenambahan = document.getElementById("field_penambahan");
        const fieldPengurangan = document.getElementById("field_pengurangan");

        jenisPerpanjanganSelect.addEventListener("change", function () {
            const value = this.value;
            fieldPenambahan.classList.add("d-none");
            fieldPengurangan.classList.add("d-none");

            if (value === "penambahan") {
                fieldPenambahan.classList.remove("d-none");
            } else if (value === "pengurangan") {
                fieldPengurangan.classList.remove("d-none");
            }
        });
    });
</script>
@endsection

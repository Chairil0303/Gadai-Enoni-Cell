@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Input Data Lelang - Barang: {{ $barang->nama_barang }} (No Bon: {{ $barang->no_bon }})</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('lelang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="barang_gadai_no_bon" value="{{ $barang->no_bon }}">

                <div class="mb-3">
                    <label for="kondisi_barang" class="form-label">Kondisi Barang</label>
                    <textarea name="kondisi_barang" class="form-control shadow-sm" rows="3" required>{{ old('kondisi_barang') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control shadow-sm" rows="2">{{ old('keterangan') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="harga_lelang" class="form-label">Harga Lelang </label>
                    <input type="text" id="harga_lelang" class="form-control shadow-sm" value="{{ old('harga_lelang') }}" placeholder="Rp 0" required>
                    <input type="hidden" name="harga_lelang" id="harga_lelang_hidden">

                </div>

                <div class="mb-3">
                    <label for="foto_barang" class="form-label">Upload Foto Barang (Opsional, Max: 2MB)</label>
                    <input type="file" name="foto_barang[]" class="form-control shadow-sm" id="foto_barang" multiple>
                    <small class="text-muted">Format: jpg, jpeg, png (max 2MB)</small>
                    <small class="text-danger" id="file_error" style="display: none;">Ukuran file maksimal 2MB!</small>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success me-2"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('lelang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk format rupiah --}}
<script>
    const hargaLelangInput = document.getElementById('harga_lelang');
    const hargaLelangHidden = document.getElementById('harga_lelang_hidden');

    hargaLelangInput.addEventListener('keyup', function(e) {
        let value = hargaLelangInput.value.replace(/[^,\d]/g, '').toString();

        if (value) {
            hargaLelangInput.value = formatRupiah(value, 'Rp ');
            hargaLelangHidden.value = value.replace(/[^,\d]/g, '');
        } else {
            hargaLelangHidden.value = '';
        }
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }
</script>
@endsection

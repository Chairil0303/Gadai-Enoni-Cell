@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Tambah Template WhatsApp</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.whatsapp_template.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="type">Tipe Template:</label>
            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                <option value="">-- Pilih Tipe Template --</option>
                <option value="perpanjang" {{ old('type') == 'perpanjang' ? 'selected' : '' }}>Perpanjang Gadai</option>
                <option value="tebus" {{ old('type') == 'tebus' ? 'selected' : '' }}>Tebus Gadai</option>
            </select>
            @error('type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="message">Template Pesan:</label>
            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="5" placeholder="Contoh: Terima kasih, barang Anda sudah diperpanjang...">{{ old('message') }}</textarea>
            @error('message')
                <span class="text-danger">{{ $message }}</span>
            @enderror

            <small class="text-muted mt-2 d-block">
                Gunakan placeholder berikut untuk template:<br>
                - {no_bon} → Nomor BON<br>
                - {nama_barang} → Nama Barang<br>
                - {nama} → Nama Nasabah<br>
                - {nama_cabang} → Nama Cabang<br>
                - {jumlah} → Jumlah Pembayaran<br>
                - {tanggal} → Tanggal Transaksi<br>
            </small>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.whatsapp_template.index') }}" class="btn btn-secondary ms-2">Kembali</a>
        </div>
    </form>
</div>
@endsection

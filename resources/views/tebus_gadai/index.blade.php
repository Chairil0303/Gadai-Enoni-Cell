<!-- resources/views/tebus_gadai/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tebus Gadai</h1>
    <form action="{{ route('tebus_gadai.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="no_bon">No. Bon</label>
            <select name="no_bon" class="form-control" required>
                @foreach($barangGadai as $barang)
                    <option value="{{ $barang->no_bon }}">{{ $barang->nama_barang }} ({{ $barang->no_bon }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id_nasabah">ID Nasabah</label>
            <input type="number" name="id_nasabah" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="jumlah_pembayaran">Jumlah Pembayaran</label>
            <input type="text" name="jumlah_pembayaran" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Tebus</button>
    </form>
</div>
@endsection

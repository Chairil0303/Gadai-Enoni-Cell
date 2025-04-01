@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cari Barang Gadai</h2>
    <form action="{{ route('tebus.cari') }}" method="GET">
        @csrf
        <div class="mb-3">
            <label for="search_no_bon" class="form-label">Cari No. Bon</label>
            <input type="text" name="search_no_bon" class="form-control" placeholder="Masukkan No. Bon">
        </div>
        
        <div class="mb-3">
            <label for="nama_nasabah" class="form-label">Nama Nasabah</label>
            <input type="text" name="nama_nasabah" class="form-control" placeholder="Masukkan Nama Nasabah">
        </div>

        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>
@endsection

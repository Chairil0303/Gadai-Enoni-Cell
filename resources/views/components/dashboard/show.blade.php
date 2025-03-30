@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Nasabah</h2>
    <p><strong>Nama:</strong> {{ $nasabah->nama }}</p>
    <p><strong>NIK:</strong> {{ $nasabah->nik }}</p>
    <p><strong>Alamat:</strong> {{ $nasabah->alamat }}</p>
    <p><strong>Telepon:</strong> {{ $nasabah->telepon }}</p>
</div>
@endsection

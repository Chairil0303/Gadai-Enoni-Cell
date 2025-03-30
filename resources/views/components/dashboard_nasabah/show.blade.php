@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profil Nasabah</h1>
    <table class="table">
        <tr>
            <th>Nama</th>
            <td>{{ $nasabah->nama }}</td>
        </tr>
        <tr>
            <th>NIK</th>
            <td>{{ $nasabah->nik }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $nasabah->alamat }}</td>
        </tr>
        <tr>
            <th>Telepon</th>
            <td>{{ $nasabah->telepon }}</td>
        </tr>
        <tr>
            <th>Status Blacklist</th>
            <td>{{ $nasabah->status_blacklist ? 'Blacklist' : 'Aktif' }}</td>
        </tr>
    </table>

    {{-- Tombol Kembali dan Edit --}}
    <a href="{{ route('dashboard.nasabah') }}" class="btn btn-secondary">Kembali</a>

</div>
@endsection

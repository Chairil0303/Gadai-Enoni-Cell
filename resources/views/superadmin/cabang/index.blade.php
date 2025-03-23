@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('superadmin.cabang.create') }}" class="btn btn-primary">Tambah Cabang</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Cabang</th>
                <th>Alamat</th>
                <th>Kontak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cabangs as $cabang)
                <tr>
                    <td>{{ $cabang->nama_cabang }}</td>
                    <td>{{ $cabang->alamat }}</td>
                    <td>{{ $cabang->kontak }}</td>
                    <td>
                        <a href="{{ route('superadmin.cabang.edit', $cabang->id_cabang) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('cabang.destroy', $cabang->id_cabang) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus cabang ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

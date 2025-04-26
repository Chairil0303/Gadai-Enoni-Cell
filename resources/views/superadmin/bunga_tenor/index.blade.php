@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Pengaturan Bunga & Tenor</h1>

    <a href="{{ route('superadmin.bunga-tenor.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
        + Tambah Bunga & Tenor
    </a>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border">Tenor (Hari)</th>
                    <th class="py-2 px-4 border">Bunga (%)</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td class="py-2 px-4 border">{{ $item->tenor }} hari</td>
                    <td class="py-2 px-4 border">{{ $item->bunga_percent }}%</td>
                    <td class="py-2 px-4 border">
                        <a href="{{ route('superadmin.bunga-tenor.edit', $item) }}" class="text-blue-500 mr-2">Edit</a>
                        <form action="{{ route('superadmin.bunga-tenor.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin mau hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

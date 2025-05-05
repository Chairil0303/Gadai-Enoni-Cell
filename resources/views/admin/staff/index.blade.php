@extends('layouts.app') 
@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Staff</h1>
        <a href="{{ route('admin.staff.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+ Tambah Staff</a>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Username</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Cabang</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($staffs as $staff)
                <tr class="border-t">
                    <td class="px-4 py-2 border">{{ $staff->nama }}</td>
                    <td class="px-4 py-2 border">{{ $staff->username }}</td>
                    <td class="px-4 py-2 border">{{ $staff->email }}</td>
                    <td class="px-4 py-2 border">{{ $staff->cabang->nama_cabang ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        <a href="{{ route('admin.staff.edit', $staff->id_users) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('admin.staff.destroy', $staff->id_users) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus staff ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada data staff.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

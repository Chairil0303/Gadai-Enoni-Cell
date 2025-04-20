@extends('layouts.app') {{-- Sesuaikan dengan layout utama kamu --}}

@section('content')
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Admin</h1>

        <div class="bg-white rounded shadow p-4">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="py-2 px-4">No</th>
                        <th class="py-2 px-4">Nama</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Username</th>
                        <th class="py-2 px-4">Cabang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $admin->nama }}</td>
                            <td class="py-2 px-4">{{ $admin->email }}</td>
                            <td class="py-2 px-4">{{ $admin->username }}</td>
                            <td class="py-2 px-4">{{ $admin->cabang->nama_cabang ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Belum ada admin terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6 max-w-lg bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Admin</h1>

    <form action="{{ route('superadmin.admins.update', $admin->id_users) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nama" class="block font-semibold">Nama</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $admin->nama) }}"
                   class="w-full px-4 py-2 border rounded" autocomplete="off" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" autocomplete="off" name="email" id="email" value="{{ old('email', $admin->email) }}"
                   class="w-full px-4 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label for="username" class="block font-semibold">Username</label>
            <input type="text" autocomplete="off" name="username" id="username" value="{{ old('username', $admin->username) }}"
                   class="w-full px-4 py-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="id_cabang" class="block font-semibold">Cabang</label>
            <select name="id_cabang" id="id_cabang" class="w-full px-4 py-2 border rounded">
                <option value="">-- Pilih Cabang --</option>
                @foreach ($cabangs as $cabang)
                    <option value="{{ $cabang->id_cabang }}" {{ $admin->id_cabang == $cabang->id_cabang ? 'selected' : '' }}>
                        {{ $cabang->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr class="my-4">

        <div class="mb-4" x-data="{ show: false, showConfirm: false }">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            <label for="password" class="block font-semibold">Password Baru</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" id="password"
                    class="w-full px-4 py-2 border rounded pr-10">
                <button type="button" @click="show = !show"
                        class="absolute right-2 top-2 text-sm text-gray-600 focus:outline-none">
                    <span x-text="show ? 'Sembunyikan' : 'Lihat'"></span>
                </button>
            </div>
        </div>

        <div class="mb-4" x-data="{ showConfirm: false }">
            @error('password_confirmation')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <label for="password_confirmation" class="block font-semibold">Konfirmasi Password</label>
            <div class="relative">
                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-2 border rounded pr-10">
                <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute right-2 top-2 text-sm text-gray-600 focus:outline-none">
                    <span x-text="showConfirm ? 'Sembunyikan' : 'Lihat'"></span>
                </button>
            </div>
        </div>


        <div class="flex justify-end">
            <a href="{{ route('superadmin.admins.index') }}" class="mr-4 text-gray-600 hover:underline">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection

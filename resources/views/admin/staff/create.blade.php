@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-xl">
    <h1 class="text-2xl font-bold mb-4">Tambah Staff</h1>

    <form action="{{ route('admin.staff.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="nama" class="block font-semibold">Nama</label>
            <input type="text" name="nama" id="nama" class="w-full border rounded px-3 py-2" required value="{{ old('nama') }}">
            @error('nama') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="username" class="block font-semibold">Username</label>
            <input type="text" name="username" id="username" class="w-full border rounded px-3 py-2" required value="{{ old('username') }}">
            @error('username') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}">
            @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block font-semibold">Password</label>
            <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
            @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block font-semibold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="id_cabang" class="block font-semibold">Cabang (opsional)</label>
            <select name="id_cabang" id="id_cabang" class="w-full border rounded px-3 py-2">
                <option value="">- Pilih Cabang -</option>
                @foreach(\App\Models\Cabang::all() as $cabang)
                    <option value="{{ $cabang->id_cabang }}" {{ old('id_cabang') == $cabang->id_cabang ? 'selected' : '' }}>
                        {{ $cabang->nama_cabang }}
                    </option>
                @endforeach
            </select>
            @error('id_cabang') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
        <a href="{{ route('admin.staff.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
    </form>
</div>
@endsection

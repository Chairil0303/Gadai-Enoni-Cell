@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Staff</h1>

    <form action="{{ route('admin.staff.update', $staff->id_users) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $staff->nama) }}" class="w-full p-2 border rounded">
            @error('nama') <div class="text-red-500">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block">Username</label>
            <input type="text" name="username" value="{{ old('username', $staff->username) }}" class="w-full p-2 border rounded">
            @error('username') <div class="text-red-500">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block">Email</label>
            <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="w-full p-2 border rounded">
            @error('email') <div class="text-red-500">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block">Password (opsional)</label>
            <input type="password" name="password" class="w-full p-2 border rounded">
            @error('password') <div class="text-red-500">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full p-2 border rounded">
        </div>

        <div>
            <label class="block">Cabang</label>
            <select name="id_cabang" class="w-full p-2 border rounded">
                <option value="">-- Pilih Cabang --</option>
                @foreach(\App\Models\Cabang::all() as $cabang)
                    <option value="{{ $cabang->id_cabang }}" {{ $staff->id_cabang == $cabang->id_cabang ? 'selected' : '' }}>
                        {{ $cabang->nama_cabang }}
                    </option>
                @endforeach
            </select>
            @error('id_cabang') <div class="text-red-500">{{ $message }}</div> @enderror
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
    </form>
</div>
@endsection

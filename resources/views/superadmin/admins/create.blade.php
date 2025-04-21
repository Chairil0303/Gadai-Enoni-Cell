@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">Tambah Admin</h1>

        <form  id="form-admin" action="{{ route('superadmin.admins.store') }}" method="POST" class="bg-white rounded shadow p-6">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">Nama</label>
                <input type="text" name="nama" class="w-full border rounded px-3 py-2 " autocomplete="off" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" autocomplete="off">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Username</label>
                <input type="text" name="username" class="w-full border rounded px-3 py-2" autocomplete="off" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Cabang</label>
                <select name="id_cabang" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($cabangs as $cabang)
                        <option value="{{ $cabang->id_cabang }}" 
                            {{ (old('id_cabang', $idCabang ?? '') == $cabang->id_cabang) ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="button" id="btn-simpan" class="bg-success text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="{{ route('superadmin.admins.index') }}" class="ml-2 text-gray-600 hover:underline">
                <button type="button" class="bg-danger text-white px-4 py-2 rounded">
                    Batal
                </button>
            </a>
        </form>
    </div>

    <script>
    document.getElementById('btn-simpan').addEventListener('click', function (e) {
            Swal.fire({
                title: 'Simpan Data?',
                text: "Pastikan data admin sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a8754',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-admin').submit();
                }
            });
        });
    </script>

@endsection

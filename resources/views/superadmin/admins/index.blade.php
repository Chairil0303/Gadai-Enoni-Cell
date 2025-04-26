@extends('layouts.app')

@section('content')
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif
<div class="container mx-auto mt-6" 
     x-data="{ show: false, selectedAdmin: null, admins: @js($admins->keyBy('id_users')) }">

    <!-- <div class="row justify-content-around">
        <div class="col-md-3">
            <h1 class="text-2xl font-bold">Daftar Admin</h1>
        </div>
        <div class="col-md-9">
            <a href="{{ route('superadmin.admins.create') }}"
                class="no-underline mb-2 inline-block bg-success text-white px-4 py-2 rounded hover:bg-green-300">
                + Tambah Admin
            </a>
        </div>
    </div> -->

    <div class="row justify-content-left mb-4">
        <div class="col-md-6">
            <h1 class="text-2xl font-bold">Daftar Admin</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('superadmin.admins.create') }}"
               class="no-underline mb-2 inline-block bg-success text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Admin
            </a>
        </div>
    </div>

    <div class="bg-white rounded shadow p-4">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="py-2 px-4">No</th>
                    <th class="py-2 px-4">Nama</th>
                    <th class="py-2 px-4">Cabang</th>
                    <th class="py-2 px-4">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-2 px-4">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4">{{ $admin->nama }}</td>
                        <td class="py-2 px-4">{{ $admin->cabang->nama_cabang ?? '-' }}</td>
                        <td class="py-2 px-4">
                            <button 
                                class="text-blue-600 hover:underline" 
                                @click="selectedAdmin = admins[{{ $admin->id_users }}]; show = true">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada admin terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Detail -->
    <div x-show="show"
         x-cloak
         class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4">Detail Admin</h2>

            <template x-if="selectedAdmin">
                <div>
                    <p><strong>Nama:</strong> <span x-text="selectedAdmin.nama"></span></p>
                    <p><strong>Email:</strong> <span x-text="selectedAdmin.email ?? '-'"></span></p>
                    <p><strong>Username:</strong> <span x-text="selectedAdmin.username"></span></p>
                    <p><strong>Cabang:</strong> <span x-text="selectedAdmin.cabang?.nama_cabang ?? '-'"></span></p>
                    <p><strong>Kontak Cabang:</strong> <span x-text="selectedAdmin.cabang?.kontak ?? '-'"></span></p>
                    <p><strong>Dibuat pada:</strong> 
                        <span x-text="new Date(selectedAdmin.created_at).toLocaleString('id-ID', {
                            dateStyle: 'medium',
                            timeStyle: 'short'
                        })"></span>
                    </p>
                    <div class="mt-6 flex justify-between">
                        <a :href="`/superadmin/admins/${selectedAdmin.id_users}/edit`"
                           class="bg-yellow-500 no-underline text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Edit
                        </a>
                        <form :action="`/superadmin/admins/${selectedAdmin.id_users}`" method="POST" @submit.prevent="confirmDelete($event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                        <button @click="show = false"
                                class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">
                            Tutup
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- SweetAlert + Alpine -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(e) {
        Swal.fire({
            title: 'Hapus Admin?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    }

    window.confirmDelete = confirmDelete;
</script>
@endsection

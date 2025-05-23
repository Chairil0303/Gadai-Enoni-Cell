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

<div class="container mt-4" x-data="{ show: false, selectedAdmin: null, admins: @js($admins->keyBy('id_users')) }">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-success"><i class="fas fa-user-shield"></i> Daftar Admin</h4>
        <a href="{{ route('superadmin.admins.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Admin
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0"><i class="fas fa-users-cog"></i> Tabel Admin</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Cabang</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $admin->nama }}</td>
                                <td>{{ $admin->cabang->nama_cabang ?? '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-success" 
                                            @click="selectedAdmin = admins[{{ $admin->id_users }}]; show = true">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Belum ada admin terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded shadow-lg w-full max-w-md p-5">
            <h5 class="text-success mb-3"><i class="fas fa-user-cog"></i> Detail Admin</h5>

            <template x-if="selectedAdmin">
                <div>
                    <p><strong>Nama:</strong> <span x-text="selectedAdmin.nama"></span></p>
                    <p><strong>Email:</strong> <span x-text="selectedAdmin.email ?? '-'"></span></p>
                    <p><strong>Username:</strong> <span x-text="selectedAdmin.username"></span></p>
                    <p><strong>Cabang:</strong> <span x-text="selectedAdmin.cabang?.nama_cabang ?? '-'"></span></p>
                    <p><strong>Kontak Cabang:</strong> <span x-text="selectedAdmin.cabang?.kontak ?? '-'"></span></p>
                    <p><strong>Dibuat pada:</strong> 
                        <span x-text="new Date(selectedAdmin.created_at).toLocaleString('id-ID', {
                            dateStyle: 'medium', timeStyle: 'short'
                        })"></span>
                    </p>
                    <div class="d-flex justify-content-between mt-4">
                        <a :href="`/superadmin/admins/${selectedAdmin.id_users}/edit`"
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form :action="`/superadmin/admins/${selectedAdmin.id_users}`" method="POST" @submit.prevent="confirmDelete($event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                        <button @click="show = false" class="btn btn-secondary">
                            Tutup
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

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

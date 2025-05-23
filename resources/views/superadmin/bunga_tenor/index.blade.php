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

<div class="container mt-4" 
     x-data="{ show: false, selectedItem: null, items: @js($data->keyBy('id')) }">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-success"><i class="fas fa-percent"></i> Pengaturan Bunga & Tenor</h4>
        <a href="{{ route('superadmin.bunga-tenor.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tambah Bunga & Tenor
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0"><i class="fas fa-table"></i> Tabel Bunga & Tenor</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>No</th>
                            <th>Tenor (Hari)</th>
                            <th>Bunga (%)</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->tenor }} hari</td>
                                <td class="text-center">{{ $item->bunga_percent }}%</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-success" 
                                            @click="selectedItem = items[{{ $item->id }}]; show = true">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Belum ada data bunga & tenor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded shadow-lg w-full max-w-md p-5">
            <h5 class="text-success mb-3"><i class="fas fa-info-circle"></i> Detail Bunga & Tenor</h5>

            <template x-if="selectedItem">
                <div>
                    <p><strong>Tenor:</strong> <span x-text="selectedItem.tenor + ' hari'"></span></p>
                    <p><strong>Bunga:</strong> <span x-text="selectedItem.bunga_percent + '%'"></span></p>
                    <p><strong>Dibuat pada:</strong> 
                        <span x-text="new Date(selectedItem.created_at).toLocaleString('id-ID', {
                            dateStyle: 'medium',
                            timeStyle: 'short'
                        })"></span>
                    </p>
                    <div class="d-flex justify-content-between mt-4">
                        <a :href="`/superadmin/bunga-tenor/${selectedItem.id}/edit`"
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form :action="`/superadmin/bunga-tenor/${selectedItem.id}`" method="POST" @submit.prevent="confirmDelete($event)">
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

<!-- SweetAlert + Alpine -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(e) {
        Swal.fire({
            title: 'Hapus data?',
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

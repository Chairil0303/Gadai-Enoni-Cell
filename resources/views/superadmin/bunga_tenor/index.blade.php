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
     x-data="{ show: false, selectedItem: null, items: @js($data->keyBy('id')) }">

    <div class="row justify-content-left">
        <div class="col-md-6">
            <h1 class="text-2xl font-bold">Pengaturan Bunga & Tenor</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('superadmin.bunga-tenor.create') }}"
               class="no-underline mb-2 inline-block bg-success text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Bunga & Tenor
            </a>
        </div>
    </div>

    <div class="bg-white rounded shadow p-4 mt-4">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="py-2 px-4">No</th>
                    <th class="py-2 px-4">Tenor (Hari)</th>
                    <th class="py-2 px-4">Bunga (%)</th>
                    <th class="py-2 px-4">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-2 px-4">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4">{{ $item->tenor }} hari</td>
                        <td class="py-2 px-4">{{ $item->bunga_percent }}%</td>
                        <td class="py-2 px-4">
                            <button 
                                class="text-blue-600 hover:underline" 
                                @click="selectedItem = items[{{ $item->id }}]; show = true">
                                Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data bunga & tenor.</td>
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
            <h2 class="text-xl font-semibold mb-4">Detail Bunga & Tenor</h2>

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
                    <div class="mt-6 flex justify-between">
                        <a :href="`/superadmin/bunga-tenor/${selectedItem.id}/edit`"
                           class="bg-yellow-500 no-underline text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Edit
                        </a>
                        <form :action="`/superadmin/bunga-tenor/${selectedItem.id}`" method="POST" @submit.prevent="confirmDelete($event)">
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

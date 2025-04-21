    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto mt-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Cabang</h1>
            <a href="{{ route('superadmin.cabang.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Cabang
            </a>
        </div>

        @if (session('message'))
            <div class="alert alert-success shadow-sm">{{ session('message') }}</div>
        @endif

        <form method="GET" action="{{ route('superadmin.cabang.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" autocomplete="off" name="search" class="form-control" placeholder="Cari nama cabang..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('message') }}
            </div>
        @endif

        <table class="w-full border-collapse border border-gray-300 shadow-md mt-4">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nama Cabang</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Alamat</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Kontak</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Lokasi</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cabangs as $cabang)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2">{{ $cabang->nama_cabang }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $cabang->alamat }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $cabang->kontak }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        @if($cabang->google_maps_link)
                            <a href="{{ $cabang->google_maps_link }}" target="_blank" class="text-blue-600 hover:underline" title="Lihat di Google Maps">
                                <i class="fas fa-map-marked-alt fa-lg"></i>
                            </a>
                        @else
                            <span class="text-gray-400 italic">Tidak tersedia</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <!-- Tombol Detail -->
                        <button class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600"
                            data-bs-toggle="modal" data-bs-target="#modalDetail{{ $cabang->id_cabang }}">
                            <i class="fas fa-info-circle"></i> Detail
                        </button>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="modalDetail{{ $cabang->id_cabang }}" tabindex="-1" aria-labelledby="modalLabel{{ $cabang->id_cabang }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content shadow">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="modalLabel{{ $cabang->id_cabang }}">Detail Cabang</h5>
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nama Cabang:</strong> {{ $cabang->nama_cabang }}</p>
                                        <p><strong>Alamat:</strong> {{ $cabang->alamat }}</p>
                                        <p><strong>Kontak:</strong> {{ $cabang->kontak }}</p>
                                        <p><strong>Google Maps:</strong>
                                            @if($cabang->google_maps_link)
                                                <a href="{{ $cabang->google_maps_link }}" target="_blank">Lihat Lokasi</a>
                                            @else
                                                <em>Tidak tersedia</em>
                                            @endif
                                        </p>
                                        <p><strong>Di buat pada: </strong> {{ $cabang->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <a href="{{ route('superadmin.cabang.edit', $cabang->id_cabang) }}" class="btn btn-success">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete('{{ $cabang->id_cabang }}')">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>

                                        <form id="form-delete-{{ $cabang->id_cabang }}" action="{{ route('superadmin.cabang.destroy', $cabang->id_cabang) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="mt-3">
            {{ $cabangs->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script>
        @if (session('message'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('message') }}',
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Cabang?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }
    </script>
    @endsection

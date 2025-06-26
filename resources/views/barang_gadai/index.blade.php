@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-success"><i class="fas fa-box"></i> Data Barang Gadai</h4>
    </div>

    <form method="GET" action="{{ route('barang_gadai.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter Status --</option>
                    <option value="Tergadai" {{ request('status') == 'Tergadai' ? 'selected' : '' }}>Tergadai</option>
                    <option value="Diperpanjang" {{ request('status') == 'Diperpanjang' ? 'selected' : '' }}>Diperpanjang</option>
                    <option value="Ditebus" {{ request('status') == 'Ditebus' ? 'selected' : '' }}>Ditebus</option>
                    <option value="Dilelang" {{ request('status') == 'Dilelang' ? 'selected' : '' }}>Dilelang</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="no_bon" class="form-control" placeholder="Cari No Bon..." value="{{ request('no_bon') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100"><i class="fas fa-search"></i> Cari</button>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div class="mb-3 alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-lg">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0"><i class="fas fa-list"></i> Tabel Barang Gadai</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 text-center align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>Cabang</th>
                            <th>No Bon</th>
                            <th>Kategori</th>
                            <th>Tipe Barang</th>
                            <th>Atas Nama</th>
                            <th>IMEI</th>
                            <th>Deskripsi</th>
                            <th>Tenor</th>
                            <th>Tgl Gadai</th>
                            <th>Tempo</th>
                            <th>Sisa Hari</th>
                            <th>Harga Gadai</th>
                            <th>Status</th>
                            @if (auth()->user()->isSuperadmin())
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangGadai as $barang)
                            <tr>
                                <td>{{ $barang->cabang->nama_cabang }}</td>
                                <td>{{ $barang->no_bon }}</td>
                                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->nasabah->nama ?? '-' }}</td>
                                <td>{{ $barang->imei ?? '-' }}</td>
                                <td>{{ $barang->deskripsi }}</td>
                                <td>{{ $barang->bungaTenor->tenor }} hari</td>
                                <td>{{ \Carbon\Carbon::parse($barang->created_at)->format('d, m, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($barang->tempo)->format('d, m, Y') }}</td>
                                <td>
                                    @if($barang->telat > 0)
                                        <span class="text-danger">Telat {{ $barang->telat }} hari</span>
                                    @else
                                        <span class="text-dark">Sisa {{ $barang->sisa_hari }} hari</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($barang->harga_gadai, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($barang->status) {
                                            'Ditebus' => 'bg-success',
                                            'Dilelang' => 'bg-danger',
                                            'Tergadai' => 'bg-warning',
                                            'Diperpanjang' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $barang->status }}</span>
                                </td>
                                @if (auth()->user()->isSuperadmin())
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('barang_gadai.edit', $barang->no_bon) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('barang_gadai.destroy', $barang->no_bon) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-muted">Belum ada data barang gadai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION --}}
                <div class="p-3">
                    {{ $barangGadai->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@endsection

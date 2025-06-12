@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-gavel"></i> Data Barang yang Sudah Dilelang</h4>
            <a href="{{ route('lelang.pilihan') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($barangLelang->isEmpty())
                <div class="alert alert-info">Belum ada data barang lelang yang aktif.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No Bon</th>
                                <th>Nama Barang</th>
                                <th>Harga Lelang</th>
                                <th>Aksi</th>
                                <th>Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangLelang as $item)
                            <tr>
                                <td>{{ $item->barangGadai->no_bon }}</td>
                                <td>{{ $item->barangGadai->nama_barang }}</td>
                                <td>Rp {{ number_format($item->harga_lelang, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('lelang.edit', $item->barangGadai->no_bon) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                                <td>
                                    @if($item->status === 'Tebus')
                                        <span class="badge bg-success">Terjual</span>
                                    @else
                                        <form action="{{ route('lelang.jual', $item->id) }}" method="POST" class="d-inline form-jual">
                                            @csrf
                                            <button type="button" class="btn btn-primary btn-sm btn-confirm-jual" data-id="{{ $item->id }}">
                                                <i class="fas fa-cash-register"></i> Jual
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $barangLelang->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jualButtons = document.querySelectorAll('.btn-confirm-jual');

        jualButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Yakin barang ini terjual?',
                    text: "Status akan berubah menjadi 'Tebus'.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, jualkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

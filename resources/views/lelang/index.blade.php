@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-box"></i> Data Barang Telat</h4>
                    <a href="{{ route('lelang.pilihan') }}" class="btn btn-light text-success">
                        Kembali
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    @if (auth()->user()->isSuperadmin())
                                        <th>Cabang</th>
                                    @endif
                                    <th>No Bon</th>
                                    <th>Tipe Barang</th>
                                    <th>Tempo</th>
                                    <th>Telat</th>
                                    <th>Detail</th>
                                    @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangGadai as $barang)
                                <tr>
                                    @if (auth()->user()->isSuperadmin())
                                        <td>{{ $barang->cabang->nama_cabang ?? '-' }}</td>
                                    @endif
                                    <td>{{ $barang->no_bon }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ \Carbon\Carbon::parse($barang->tempo)->format('d, m, Y') }}</td>
                                    <td>
                                        @if($barang->telat > 0)
                                            <span class="badge bg-danger">Telat {{ $barang->telat }} hari</span>
                                        @else
                                            <span class="badge bg-secondary">Sisa {{ $barang->sisa_hari }} hari</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="showDetail('{{ $barang->no_bon }}')">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </button>
                                    </td>
                                    @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                                    <td>
                                        @if ($barang->status !== 'Dilelang')
                                            <a href="{{ route('lelang.create', $barang->no_bon) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-gavel"></i> Lelang
                                            </a>
                                        @else
                                            <a href="{{ route('lelang.edit', $barang->no_bon) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($barangGadai->isEmpty())
                            <div class="text-center text-muted mt-3">
                                <em>Tidak ada barang yang telat saat ini.</em>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Barang -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Barang Gadai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>No Bon:</strong> <span id="noBon"></span></p>
                <p><strong>Kategori:</strong> <span id="kategori"></span></p>
                <p><strong>Atas Nama:</strong> <span id="atasNama"></span></p>
                <p><strong>Tenor:</strong> <span id="tenor"></span> Hari</p>
                <p><strong>Harga Gadai:</strong> Rp <span id="hargaGadai"></span></p>
                <p><strong>Tanggal Gadai:</strong> <span id="createdAt"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetail(noBon) {
        fetch(`/barang-gadai/detail/${noBon}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('noBon').innerText = data.no_bon;
                document.getElementById('kategori').innerText = data.kategori;
                document.getElementById('atasNama').innerText = data.atas_nama;
                document.getElementById('tenor').innerText = data.tenor;
                document.getElementById('hargaGadai').innerText = new Intl.NumberFormat().format(data.harga_gadai);
                document.getElementById('createdAt').innerText = data.created_at;
                const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
                detailModal.show();
            })
            .catch(error => {
                console.error('Error fetching detail:', error);
                alert('Gagal memuat detail barang. Silakan cek konsol browser.');
            });
    }
</script>
@endsection

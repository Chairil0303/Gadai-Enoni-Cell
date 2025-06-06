@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-lg">
        <div class="card-body">
            {{-- Navigation Tabs --}}
            <ul class="nav nav-tabs mb-4" id="lelangTabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('view') !== 'lelang' ? 'active' : '' }}" 
                        href="{{ route('lelang.index', ['view' => 'telat']) }}">
                        <i class="fas fa-clock"></i> Barang Telat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('view') === 'lelang' ? 'active' : '' }}" 
                        href="{{ route('lelang.index', ['view' => 'lelang']) }}">
                        <i class="fas fa-gavel"></i> Barang Lelang
                    </a>
                </li>
            </ul>

            {{-- Flash message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Barang Lelang --}}
            @if ($view === 'lelang')
                <h5 class="mb-3"><i class="fas fa-gavel text-primary"></i> Data Barang Lelang</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                @if (auth()->user()->isSuperadmin())
                                    <th>Cabang</th>
                                @endif
                                <th>Nama Barang</th>
                                <th>Tanggal Lelang</th>
                                <th>Status</th>
                                <th>Detail</th>
                                @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangLelang as $barang)
                            <tr>
                                @if (auth()->user()->isSuperadmin())
                                    <td>{{ $barang->cabang->nama_cabang ?? '-' }}</td>
                                @endif
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ \Carbon\Carbon::parse($barang->tanggal_lelang)->format('d-m-Y') }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $barang->status }}</span></td>
                                <td>
                                    <button class="btn btn-info btn-sm" onclick="showDetail('{{ $barang->no_bon }}')">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                </td>
                                @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                                <td>
                                    <a href="{{ route('lelang.edit', $barang->no_bon) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data lelang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            {{-- Barang Telat --}}
            @else
                <h5 class="mb-3"><i class="fas fa-box text-success"></i> Data Barang Telat</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                @if (auth()->user()->isSuperadmin())
                                    <th>Cabang</th>
                                @endif
                                <th>Nama Barang</th>
                                <th>Tempo</th>
                                <th>Telat</th>
                                <th>Detail</th>
                                @if (auth()->user()->isSuperadmin() || auth()->user()->isAdmin())
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangGadai as $barang)
                            <tr>
                                @if (auth()->user()->isSuperadmin())
                                    <td>{{ $barang->cabang->nama_cabang ?? '-' }}</td>
                                @endif
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ \Carbon\Carbon::parse($barang->tempo)->format('d-m-Y') }}</td>
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
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada barang yang telat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p><strong>No Bon:</strong> <span id="noBon"></span></p>
                <p><strong>Kategori:</strong> <span id="kategori"></span></p>
                <p><strong>Atas Nama:</strong> <span id="atasNama"></span></p>
                <p><strong>Tenor:</strong> <span id="tenor"></span> Hari</p>
                <p><strong>Harga Gadai:</strong> Rp <span id="hargaGadai"></span></p>
                <p><strong>Tanggal Gadai:</strong> <span id="createdAt"></span></p>
            </div>
        </div>
    </div>
</div>

{{-- Modal JS --}}
<script>
    function showDetail(noBon) {
        fetch(`/barang-gadai/detail/${noBon}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then(data => {
                document.getElementById('noBon').innerText = data.no_bon;
                document.getElementById('kategori').innerText = data.kategori;
                document.getElementById('atasNama').innerText = data.atas_nama;
                document.getElementById('tenor').innerText = data.tenor;
                document.getElementById('hargaGadai').innerText = new Intl.NumberFormat().format(data.harga_gadai);
                document.getElementById('createdAt').innerText = data.created_at;

                new bootstrap.Modal(document.getElementById('detailModal')).show();
            })
            .catch(error => {
                console.error('Gagal ambil detail:', error);
                alert('Gagal memuat detail barang.');
            });
    }
</script>
@endsection

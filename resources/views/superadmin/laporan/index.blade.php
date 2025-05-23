@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-chart-bar"></i> Laporan Transaksi Gadai</h4>
        </div>
        <div class="card-body bg-light">
            <form method="GET" action="{{ route('superadmin.laporan.detail') }}" class="px-3">
                <div class="mb-3">
                    <label class="form-label text-success fw-semibold">
                        <i class="fas fa-building"></i> Pilih Cabang
                    </label>
                    <select name="cabang_id" class="form-select rounded-3 shadow-sm" required>
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($cabangs as $cabang)
                            <option value="{{ $cabang->id_cabang }}">{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-success fw-semibold">
                        <i class="fas fa-calendar-alt"></i> Tipe Laporan
                    </label>
                    <select name="tipe" class="form-select rounded-3 shadow-sm" onchange="toggleDateInputs(this.value)">
                        <option value="harian">Harian</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                </div>

                <div class="mb-3" id="tanggalInput">
                    <label class="form-label text-success fw-semibold">
                        <i class="fas fa-calendar-day"></i> Tanggal
                    </label>
                    <input type="date" name="tanggal" class="form-control rounded-3 shadow-sm">
                </div>

                <div class="mb-3 d-none" id="bulanInput">
                    <label class="form-label text-success fw-semibold">
                        <i class="fas fa-calendar"></i> Bulan
                    </label>
                    <input type="month" name="bulan" class="form-control rounded-3 shadow-sm">
                </div>

                <div class="text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary shadow-sm rounded-3">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm px-4 rounded-3">
                        <i class="fas fa-eye"></i> Lihat Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleDateInputs(value) {
        document.getElementById('tanggalInput').classList.toggle('d-none', value !== 'harian');
        document.getElementById('bulanInput').classList.toggle('d-none', value !== 'bulanan');
    }
</script>
@endsection

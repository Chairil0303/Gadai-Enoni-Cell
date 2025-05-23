@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Pilih Cabang & Jenis Laporan</h1>

    <form method="GET" action="{{ route('superadmin.laporan.detail') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block mb-1">Cabang:</label>
            <select name="cabang_id" class="form-select w-full" required>
                <option value="">-- Pilih Cabang --</option>
                @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->id_cabang }}">
                        {{ $cabang->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">Tipe Laporan:</label>
            <select name="tipe" class="form-select w-full" onchange="toggleDateInputs(this.value)">
                <option value="harian">Harian</option>
                <option value="bulanan">Bulanan</option>
            </select>
        </div>

        <div id="tanggalInput">
            <label class="block mb-1">Tanggal:</label>
            <input type="date" name="tanggal" class="form-input w-full">
        </div>

        <div id="bulanInput" class="hidden">
            <label class="block mb-1">Bulan:</label>
            <input type="month" name="bulan" class="form-input w-full">
        </div>

        <div class="md:col-span-3">
            <button type="submit" class="btn btn-primary">Lihat Laporan</button>
        </div>
    </form>
</div>

<script>
    function toggleDateInputs(value) {
        document.getElementById('tanggalInput').classList.toggle('hidden', value !== 'harian');
        document.getElementById('bulanInput').classList.toggle('hidden', value !== 'bulanan');
    }
</script>
@endsection

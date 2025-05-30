@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4">
            <h4><i class="fas fa-search"></i> Cari Barang Gadai</h4>
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('admin.tebus.cari') }}" method="GET" class="px-3">
                @csrf

                <div class="mb-3">
                    <label for="search_no_bon" class="form-label text-success fw-semibold">
                        <i class="fas fa-receipt"></i> No. Bon
                    </label>
                    <input autocomplete="off" type="text" name="no_bon" class="form-control rounded-3 shadow-sm" placeholder="Masukkan No. Bon">
                </div>

                <div class="mb-3">
                    <label for="nama_nasabah" class="form-label text-success fw-semibold">
                        <i class="fas fa-user"></i> Nama Nasabah
                    </label>
                    <input type="text" name="nama_nasabah" class="form-control rounded-3 shadow-sm" placeholder="Masukkan Nama Nasabah">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success shadow-sm px-4 rounded-3">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

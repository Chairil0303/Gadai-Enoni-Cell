@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history"></i>
                    @if(auth()->user()->role === 'Superadmin')
                        Aktivitas Seluruh Cabang
                    @else
                        Aktivitas Cabang {{ auth()->user()->cabang->nama_cabang }}
                    @endif
                </h5>
                <div class="btn-group">
                    <a href="{{ route('activities.index') }}" class="btn btn-light {{ !request('filter') ? 'active' : '' }}">
                        Semua
                    </a>
                    <a href="{{ route('activities.index', ['filter' => 'today']) }}" class="btn btn-light {{ request('filter') === 'today' ? 'active' : '' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('activities.index', ['filter' => 'this_month']) }}" class="btn btn-light {{ request('filter') === 'this_month' ? 'active' : '' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('activities.index', ['filter' => 'last_month']) }}" class="btn btn-light {{ request('filter') === 'last_month' ? 'active' : '' }}">
                        Bulan Lalu
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>Nama</th>
                            <th>Cabang</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                        <tr>
                            <td>{{ $activity->nama }}</td>
                            <td>{{ $activity->nama_cabang }}</td>
                            <td>{{ $activity->deskripsi ?? $activity->aksi }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($activity->waktu_aktivitas)->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Tidak ada aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-group .btn {
        border: 1px solid #ddd;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn-group .btn.active {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }
    .btn-group .btn:hover:not(.active) {
        background-color: #f8f9fa;
    }
</style>
@endpush
@endsection

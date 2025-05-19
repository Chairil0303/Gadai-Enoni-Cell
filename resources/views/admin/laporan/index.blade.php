@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Laporan</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('admin.laporan.harian') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded shadow text-center">
            <i class="fas fa-calendar-day mr-2"></i> Laporan Harian
        </a>
        <a href="{{ route('admin.laporan.bulanan') }}" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded shadow text-center">
            <i class="fas fa-calendar-alt mr-2"></i> Laporan Bulanan
        </a>
    </div>
</div>
@endsection

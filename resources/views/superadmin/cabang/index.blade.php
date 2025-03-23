@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('superadmin.cabang.create') }}" class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 mb-3">Tambah Cabang</a>
    @livewire('cabang-table')
</div>
@endsection

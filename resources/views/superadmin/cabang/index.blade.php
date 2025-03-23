@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('superadmin.cabang.create') }}" class="btn btn-primary mb-3">Tambah Cabang</a>
    @livewire('cabang-table')
</div>
@endsection

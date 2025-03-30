<!-- resources/views/dashboard/nasabah.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Selamat datang, {{ auth()->user()->nama }}</h1>
</div>
@endsection

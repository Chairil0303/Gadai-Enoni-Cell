<!-- resources/views/dashboard/nasabah.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Selamat datang, {{ auth()->user()->nama }}</h2>
    <p>Ini adalah dashboard Nasabah Anda.</p>
</div>
@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
            });
        });
    </script>
@endif

@endsection

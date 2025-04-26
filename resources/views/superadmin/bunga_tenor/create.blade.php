@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Tambah Bunga & Tenor</h1>

    <form action="{{ route('superadmin.bunga-tenor.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="tenor" class="block font-bold mb-2">Tenor (Hari)</label>
            <input type="number" name="tenor" id="tenor" class="border p-2 w-full" required>
        </div>

        <div class="mb-4">
            <label for="bunga_percent" class="block font-bold mb-2">Bunga (%)</label>
            <input type="number" name="bunga_percent" id="bunga_percent" step="0.01" class="border p-2 w-full" required>
        </div>

        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection

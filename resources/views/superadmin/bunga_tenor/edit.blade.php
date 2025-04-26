@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Bunga & Tenor</h1>

    <form action="{{ route('superadmin.bunga-tenor.update', $bungaTenor) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="tenor" class="block font-bold mb-2">Tenor (Hari)</label>
            <input type="number" name="tenor" id="tenor" value="{{ old('tenor', $bungaTenor->tenor) }}" class="border p-2 w-full" required>
        </div>

        <div class="mb-4">
            <label for="bunga_percent" class="block font-bold mb-2">Bunga (%)</label>
            <input type="number" name="bunga_percent" id="bunga_percent" step="0.01" value="{{ old('bunga_percent', $bungaTenor->bunga_percent) }}" class="border p-2 w-full" required>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Update
        </button>
    </form>
</div>
@endsection

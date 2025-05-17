@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Halaman: {{ $terms->title ?? 'Syarat & Ketentuan' }}</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.terms.update') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="title" class="block font-semibold mb-1">Judul Halaman</label>
            <input type="text" name="title" id="title" value="{{ old('title', $terms->title ?? '') }}" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="content" class="block font-semibold mb-1">Konten</label>
            <textarea name="content" id="editor" rows="20" class="w-full border border-gray-300 rounded-md">{{ old('content', $terms->content ?? '') }}</textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.25.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor', {
        height: 500
    });
</script>
@endsection

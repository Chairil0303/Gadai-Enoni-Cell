@extends('layouts.app')

@section('title', $terms->title)

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-green-600 to-emerald-400 text-black rounded-2xl p-6 shadow-lg mb-8">
        <h1 class="text-4xl font-bold mb-2">📋 {{ $terms->title }}</h1>
        <p class="text-lg">Harap membaca dan menyetujui seluruh ketentuan berikut sebelum mengambil barang gadai Anda.</p>
    </div>

    {{-- KONTEN DARI DATABASE --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-green-500 prose prose-green max-w-none">
        {!! $terms->content !!}
    </div>

    {{-- CTA --}}
    <div class="text-center mt-10">
        <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full shadow-lg transition transform hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M3 6h11m-7 8h7m5-6l4 4m0 0l-4 4m4-4H9" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection

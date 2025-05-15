@extends('layouts.app')

@section('title', 'Syarat & Ketentuan')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-green-600 to-emerald-400 text-black rounded-2xl p-6 shadow-lg mb-8">
        <h1 class="text-4xl font-bold mb-2">📋 Syarat & Ketentuan Pengambilan Barang</h1>
        <p class="text-lg">Harap membaca dan menyetujui seluruh ketentuan berikut sebelum mengambil barang gadai Anda.</p>
    </div>

    {{-- SYARAT CARD --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border-t-4 border-green-500">
        <h2 class="text-2xl font-semibold text-green-700 mb-4 flex items-center">
            ✅ Syarat Pengambilan Barang
        </h2>
        <ul class="space-y-4 text-gray-700 text-base">
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-green-500 mt-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"/></svg>
                Wajib membawa <strong>Surat/Bon Gadai</strong> dan <strong>KTP Asli</strong>.
            </li>
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-green-500 mt-1" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 5.707 8.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"/></svg>
                Pengambilan barang yang diwakilkan wajib menyertakan:
                <ul class="list-disc ml-6 mt-2 space-y-1">
                    <li>Surat Kuasa dari pihak penggadai</li>
                    <li>Surat/Bon Gadai</li>
                    <li>KTP Asli atas nama penggadai</li>
                    <li>KTP Asli dari pihak yang mewakilkan</li>
                </ul>
            </li>
        </ul>
    </div>

    {{-- PERNYATAAN CARD --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-emerald-500">
        <h2 class="text-2xl font-semibold text-emerald-700 mb-4 flex items-center">
            📝 Pernyataan Persetujuan
        </h2>
        <ol class="list-decimal list-inside space-y-3 text-gray-800 text-base">
            <li>Mengikuti seluruh aturan yang berlaku di <strong>Enoni Cellular</strong>.</li>
            <li>Barang yang saya gadaikan adalah milik pribadi dan bukan hasil tindak kejahatan.
                <em>Jika dikemudian hari bermasalah, saya bertanggung jawab sepenuhnya tanpa melibatkan Enoni Cellular.</em>
            </li>
            <li>Menyetujui jumlah tebusan tetap meskipun pengambilan dilakukan sebelum jatuh tempo.</li>
            <li>Menyetujui denda keterlambatan sebesar <strong>1% per hari</strong> dari total gadai.</li>
            <li>Barang menjadi <strong>Hak Milik Enoni Cellular</strong> dan dapat dijual setelah 7 hari dari jatuh tempo.</li>
            <li><em>Bertanggung jawab atas kerusakan barang apabila disimpan lebih dari 1 bulan.</em></li>
        </ol>
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

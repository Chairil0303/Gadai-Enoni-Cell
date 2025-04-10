@extends('layouts.app')

@section('content')
<div class="bg-white py-2 sm:py-32">
<div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-4 lg:max-w-4xl">
        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-4">

            <!-- Terima Gadai -->
            <a href="{{ route('barang_gadai.create') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                    <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                        <i class="fas fa-hand-holding-dollar text-white text-lg"></i>
                    </div>
                    <span class="pl-12">Terima Gadai</span>
                </dt>
                <dd class="mt-2 pt-3 text-base text-gray-600">Proses penerimaan barang gadai dengan mudah dan cepat.</dd>
            </a>

            <!-- Tebus Gadai -->
            <a href="{{ url('/transaksi_gadai/tebus_gadai') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                    <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                        <i class="fas fa-money-bill-wave text-white text-lg"></i>
                    </div>
                    <span class="pl-12">Tebus Gadai</span>
                </dt>
                <dd class="mt-2 pt-3 text-base text-gray-600">Lakukan pembayaran untuk menebus barang yang telah digadaikan.</dd>
            </a>

            <!-- Perpanjang Gadai -->
            <a href="{{ route('perpanjang_gadai.create') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                    <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                        <i class="fas fa-clock-rotate-left text-white text-lg"></i>
                    </div>
                    <span class="pl-12">Perpanjang Gadai</span>
                </dt>
                <dd class="mt-2 pt-3 text-base text-gray-600">Tambahkan jangka waktu untuk gadai barang.</dd>
            </a>

            <!-- Terima Jual -->
            <a href="{{ url('/transaksi_gadai/terima_jual') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                    <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                        <i class="fas fa-store text-white text-lg"></i>
                    </div>
                    <span class="pl-12">Terima Jual</span>
                </dt>
                <dd class="mt-2 pt-3 text-base text-gray-600">Jual barang langsung ke koperasi dengan harga terbaik.</dd>
            </a>

            <!-- Lelangan -->
            <a href="{{ url('/transaksi_gadai/terima_jual') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                    <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                        <i class="fas fa-gavel text-white text-lg"></i>
                    </div>
                    <span class="pl-12">Lelangan</span>
                </dt>
                <dd class="mt-2 pt-3 text-base text-gray-600">Lelangan langsung ke koperasi dengan harga terbaik.</dd>
            </a>

        </dl>
    </div>
</div>
</div>
@endsection

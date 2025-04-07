
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
                            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"/>
                            </svg>
                        </div>
                        <span class="pl-12">Terima Gadai</span>
                    </dt>
                    <dd class="mt-2 pt-3 text-base text-gray-600">Proses penerimaan barang gadai dengan mudah dan cepat.</dd>
                </a>

                <!-- Tebus Gadai -->
                <a href="{{ url('/transaksi_gadai/tebus_gadai') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                    <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                        <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                            <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <span class="pl-12">Tebus Gadai</span>
                    </dt>
                    <dd class="mt-2 pt-3 text-base text-gray-600">Lakukan pembayaran untuk menebus barang yang telah digadaikan.</dd>
                </a>

                <!-- Perpanjang Gadai -->
                <a href="{{ url('/transaksi_gadai/perpanjang_gadai') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                    <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                        <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                            <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </div>
                        <span class="pl-12">Perpanjang Gadai</span>
                    </dt>
                    <dd class="mt-2 pt-3 text-base text-gray-600">Tambahkan jangka waktu untuk gadai barang.</dd>
                </a>

                <!-- Terima Jual -->
                <a href="{{ url('/transaksi_gadai/terima_jual') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                    <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                        <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                            <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
                            </svg>
                        </div>
                        <span class="pl-12">Terima Jual</span>
                    </dt>
                    <dd class="mt-2 pt-3 text-base text-gray-600">Jual barang langsung ke koperasi dengan harga terbaik.</dd>
                </a>

                <!-- Lelangan -->
                <a href="{{ url('/transaksi_gadai/terima_jual') }}" class="no-underline group relative pl-16 block hover:bg-gray-100 p-4 rounded-lg transition">
                    <dt class="text-base font-semibold text-gray-900 flex items-center space-x-4">
                        <div class="absolute top-4 left-7 flex size-10 items-center justify-center rounded-lg bg-green-600 group-hover:bg-green-700 transition">
                            <svg class="size-6 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
                            </svg>
                        </div>
                        <span class="pl-12">Lelangan</span>
                    </dt>
                    <dd class="mt-2 pt-3 text-base text-gray-600">Lelangan langsung ke koperasi dengan harga terbaik.</dd>
                </a>
            </dl>
        </div>
    </div>
</div>


    <script>
        function handleClick(fitur) {
            alert('Anda memilih fitur: ' + fitur);
        }
    </script>
</div>

    @endsection

<!-- Tambahkan di layout utama: CDN Alpine.js -->
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

<!-- Wrapper Layout -->
<div x-data="{ open: false }" class="flex h-screen">

    <!-- Sidebar -->
    <div :class="open ? 'translate-x-0' : '-translate-x-full'" class="fixed sm:relative z-50 sm:translate-x-0 transform transition-transform duration-300 h-full w-64 bg-gray-800 text-white shadow-lg flex-shrink-0 pl-4">
        <div class="p-4 pt-12 text-lg font-bold">
            Dashboard
        </div>
        <ul class="space-y-1 pb-4">

            <li>
                <a href="{{ route('notifikasi.index') }}" class="py-3 block p-2 hover:bg-gray-700">
                    📢 Notifikasi
                </a>
            </li>

            @if(auth()->user()->isSuperadmin())
                <li><a href="{{ route('transaksi_gadai.index') }}" class="py-3 block p-2 hover:bg-gray-700">💰 Transaksi Gadai</a></li>
                <li><a href="{{ route('barang_gadai.index') }}" class="py-3 block p-2 hover:bg-gray-700">📦 Barang Gadai</a></li>
                <li><a href="{{ route('nasabah.index') }}" class="py-3 block p-2 hover:bg-gray-700">👨‍💼 Nasabah</a></li>
                <li><a href="{{ route('cabang.index') }}" class="py-3 block p-2 hover:bg-gray-700">🏣 Cabang</a></li>
                <li><a href="{{ route('lelang_barang.index') }}" class="py-3 block p-2 hover:bg-gray-700">⚖️ Lelang</a></li>
                <li><a href="{{ route('laporan.index') }}" class="py-3 block p-2 hover:bg-gray-700">📝 Laporan</a></li>
            @endif

            @if(auth()->user()->isAdmin())
                <li><a href="{{ route('barang_gadai.index') }}" class="py-3 block p-2 hover:bg-gray-700">📦 Barang Gadai</a></li>
                <li><a href="{{ route('transaksi_gadai.index') }}" class="py-3 block p-2 hover:bg-gray-700">💰 Transaksi Gadai</a></li>
                <li><a href="{{ route('nasabah.index') }}" class="py-3 block p-2 hover:bg-gray-700">👨‍💼 Nasabah</a></li>
                <li><a href="{{ route('lelang_barang.index') }}" class="py-3 block p-2 hover:bg-gray-700">⚖️ Lelang</a></li>
            @endif
        </ul>
    </div>

    <!-- Konten utama -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-md px-4 py-3 flex items-center justify-between sm:justify-end">
            <button @click="open = !open" class="sm:hidden text-gray-800 focus:outline-none">
                <!-- Hamburger Icon -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </header>

        <!-- Main Content -->
        <main class="p-4 overflow-auto">
            {{-- Konten halaman ditaruh di sini --}}
        </main>
    </div>
</div>

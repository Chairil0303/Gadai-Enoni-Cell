<aside 
    class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform md:translate-x-0 transition-transform duration-300 z-50"
    :class="{ '-translate-x-full': !open }"
    x-data
>
    <!-- Tombol Close untuk Mobile -->
    <button 
        class="absolute top-2 right-2 text-white text-xl md:hidden"
        @click="open = false"
    >
        ✖
    </button>

    <div class="p-4 pt-12 text-lg font-bold border-b border-gray-600">
        Dashboard
    </div>

    <ul class="mt-4 space-y-1 px-2">
        {{-- Semua Role Bisa Akses Notifikasi --}}
        <li>
            <a href="{{ route('notifikasi.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">
                📢 Notifikasi
            </a>
        </li>

        {{-- Khusus Superadmin --}}
        @if(auth()->user()->isSuperadmin())
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">💰 Transaksi Gadai</a></li>
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">📦 Barang Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">👨‍💼 Nasabah</a></li>
            <li><a href="{{ route('cabang.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">🏣 Cabang</a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">⚖️ Lelang</a></li>
            <li><a href="{{ route('laporan.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">📝 Laporan</a></li>
        @endif

        {{-- Khusus Admin --}}
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">📦 Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">💰 Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">👨‍💼 Nasabah</a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 block px-4 rounded hover:bg-gray-700">⚖️ Lelang</a></li>
        @endif
    </ul>
</aside>

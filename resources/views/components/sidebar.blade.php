<div class="h-screen w-52 bg-gray-800 text-white shadow-lg flex-shrink-0 pl-4">
    <div class="p-4 pt-12 text-lg font-bold">
        Dashboard
    </div>
    <ul>

        {{-- Semua Role Bisa Akses Notifikasi --}}
        <li>
            <a href="{{ route('notifikasi.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">
                📢 Notifikasi
            </a>
        </li>

        {{-- Khusus Superadmin --}}
        @if(auth()->user()->isSuperadmin())
        <li><a href="{{ route('transaksi_gadai.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">💰 Transaksi Gadai</a></li>
        <li><a href="{{ route('barang_gadai.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">📦 Barang Gadai</a></li>
        <li><a href="{{ route('nasabah.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">👨‍💼 Nasabah</a></li>
        <li><a href="{{ route('cabang.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">🏣 Cabang</a></li>
        <li><a href="{{ route('lelang_barang.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">⚖️ Lelang</a></li>
        <li><a href="{{ route('laporan.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">📝 Laporan</a></li>
        @endif

        {{-- Khusus Admin --}}
        @if(auth()->user()->isAdmin())
            <li>
                <a href="{{ route('barang_gadai.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">
                    📦 Barang Gadai
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi_gadai.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">
                    💰 Transaksi Gadai
                </a>
            </li>
            <li>
                <a href="{{ route('nasabah.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">👨‍💼 Nasabah</a>
            </li>
            <li>
                <a href="{{ route('lelang_barang.index') }}" class="py-3 text-white no-underline block p-2 hover:bg-gray-700">
                    ⚖️ Lelang
                </a>
            </li>
        @endif


        @if(auth()->user()->isNasabah())
        <li>
            <a href="{{ route('profile') }}" class="block px-4 py-2">Profil Saya</a>

        </li>
    @endif


    </ul>
</div>

<aside>
    <!-- Tombol ✖ hanya untuk mobile -->
    <button 
        class="absolute top-2 right-2 text-white text-xl md:hidden"
        @click="sidebarOpen = false"
    >
        ✖
    </button>

    <div class="p-4 pt-12 text-lg font-bold border-b border-gray-600">
        Dashboard
    </div>

    <ul class="mt-4 space-y-1 px-2">
        {{-- Semua Role Bisa Akses Notifikasi --}}
        <li>
            <a href="{{ route('notifikasi.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">
                📢 Notifikasi
            </a>
        </li>

        {{-- Khusus Superadmin --}}
        @if(auth()->user()->isSuperadmin())
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">💰 Transaksi Gadai</a></li>
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">📦 Barang Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">👨‍💼 Nasabah</a></li>
            <li><a href="{{ route('superadmin.cabang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">🏣 Cabang</a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">⚖️ Lelang</a></li>
            <li><a href="{{ route('laporan.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">📝 Laporan</a></li>
        @endif

        {{-- Khusus Admin --}}
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">📦 Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">💰 Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">👨‍💼 Nasabah</a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">⚖️ Lelang</a></li>
        @endif
    </ul>


    {{-- Profil & Logout - Hanya muncul di Mobile --}}
    <div class="block  border-t border-gray-600 mt-4 pt-4 px-4">
        <div class="text-sm text-white mb-2">
            👤 {{ strtoupper(auth()->user()->nama) }}
        </div>

        <form id="logoutFormMobile" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirmLogoutMobile()" class="w-full text-left px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white">
                🚪 Log Out
            </button>
        </form>
    </div>
</aside>

<script>
    function confirmLogoutMobile() {
        Swal.fire({
            title: 'Yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutFormMobile').submit();
            }
        });
    }
</script>

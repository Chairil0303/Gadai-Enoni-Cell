<<<<<<< HEAD
<aside>
    <!-- Tombol ✖ hanya untuk mobile -->
    <button 
        class="absolute top-2 right-2 text-white text-xl md:hidden"
        @click="sidebarOpen = false"
    >
        <i class="fas fa-times"></i>
    </button>

    <div class="p-4 pt-12 text-lg font-bold border-b border-gray-600 text-white">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </div>

    <ul class="mt-4 space-y-1 px-2">
        {{-- Semua Role Bisa Akses Notifikasi --}}
        <li>
            <a href="{{ route('notifikasi.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">
                <i class="fas fa-bell mr-2"></i> Notifikasi
            </a>
        </li>

        {{-- Khusus Superadmin --}}
        @if(auth()->user()->isSuperadmin())
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('superadmin.cabang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-building mr-2"></i> Cabang</a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li><a href="{{ route('laporan.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-file-alt mr-2"></i> Laporan</a></li>
        @endif

        {{-- Khusus Admin --}}
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
        @endif
    </ul>

    {{-- Profil & Logout - Hanya muncul di Mobile --}}
    <div class="block border-t border-gray-600 mt-4 pt-4 px-4">
        <div class="text-sm text-white mb-2">
            <i class="fas fa-user-circle mr-1"></i> {{ strtoupper(auth()->user()->nama) }}
        </div>

        <form id="logoutFormMobile" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirmLogoutMobile()" class="w-full text-left px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white">
                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
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
=======
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
>>>>>>> dashboard-nasabah

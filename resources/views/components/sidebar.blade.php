<aside class="overflow-y-auto max-h-screen pb-5">
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
            <li>
                <a href="{{ route('superadmin.kategori-barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">
                    <i class="fas fa-tags mr-2"></i> Kategori Barang
                </a>
            </li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('superadmin.cabang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-building mr-2"></i> Cabang</a></li>
            <li><a href="{{ route('superadmin.admins.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">
                <i class="fas fa-users-cog mr-2"></i> Admin
            </a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li><a href="{{ route('laporan.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-file-alt mr-2"></i> Laporan</a></li>
        @endif

        {{-- Khusus Admin --}}
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('lelang_barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li><a href="{{ route('barang_gadai.diperpanjang_dm') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-box-open mr-2"></i> Ubah NoBon </a></li>

        @endif
    </ul>

    {{-- Profil & Logout - Hanya muncul di Mobile --}}
    <div class="block border-t border-gray-600 pt-2 px-4">
        <div class="text-sm text-white mb-2">
            <i class="fas fa-user-circle mr-1"></i> {{ strtoupper(auth()->user()->nama) }}
        </div>

        <form id="logoutFormMobile" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirmLogoutMobile()" class="w-full text-left px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white">
                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
            </button>
        </form >
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

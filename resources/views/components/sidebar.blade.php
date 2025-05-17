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
        @if(auth()->user()->isNasabah())

        <li>
            <a href="{{ route('nasabah.terms') }}" class="block px-4 py-2 hover:bg-green-100 rounded">
                📋 Syarat & Ketentuan
            </a>
        </li>

        @endif


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
            <li>
                <a href="{{ route('superadmin.bunga-tenor.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">
                    <i class="fas fa-percentage mr-2"></i> Bunga & Tenor
                </a>
            </li>
            <li><a href="{{ route('lelang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li><a href="{{ route('laporan.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-file-alt mr-2"></i> Laporan</a></li>
        @endif

        {{-- Khusus Admin --}}
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('lelang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li>
                <a href="{{ route('admin.staff.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white">
                    <i class="fas fa-user-cog mr-2"></i> Staff
                </a>
            </li>
            <li><a href="{{ route('admin.laporan.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-file-alt mr-2"></i> Laporan</a></li>

             <li>
            <a href="{{ route('admin.whatsapp_template.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.67 12.09a9.61 9.61 0 00-.54-1.91 9.8 9.8 0 00-.84-1.68l-.18-.25-.12-.15-.06-.08a9.93 9.93 0 00-.77-.91A10.13 10.13 0 0015.73 5.5a9.72 9.72 0 00-3.73-.74 9.68 9.68 0 00-9.45 7.34A9.64 9.64 0 003 14.1a9.72 9.72 0 00.58 1.87 9.93 9.93 0 00.85 1.68l.17.24.13.16a10.21 10.21 0 00.75.9 10.1 10.1 0 004.61 2.79l.34.06.19.03.23.04.15.02.21.03h.15l.15.02.09.01h.26c1.24 0 2.41-.24 3.52-.71a9.85 9.85 0 003.35-2.37 9.77 9.77 0 002.14-3.59 9.73 9.73 0 00.47-1.86c.05-.22.09-.44.12-.66a9.68 9.68 0 00.03-.67l-.03-.6zm-7.8 4.69l-.36-.02-.36-.06a7.83 7.83 0 01-1.31-.29 7.74 7.74 0 01-2.5-1.45 6.32 6.32 0 01-1.56-1.84 4.54 4.54 0 01-.71-2.27v-.18l.04-.13.11-.18c.02-.02.06-.05.1-.07a.8.8 0 01.26-.04h.06c.11 0 .26.02.41.04l.29.07c.1.02.18.05.25.09.06.03.12.06.17.1l.12.1.1.1.08.12c.16.28.34.55.54.8.21.25.45.49.7.7l.15.14c.05.03.08.05.12.07.02 0 .05.01.07.02a.5.5 0 00.16.02.7.7 0 00.24-.05c.14-.05.3-.14.49-.27.2-.14.37-.25.53-.33l.24-.12a.7.7 0 01.26-.05h.13c.07.01.13.02.19.04s.1.04.15.07.08.06.11.1.06.08.08.13l.06.16c.03.1.06.19.09.3.02.09.04.17.05.25s.01.14.01.19c0 .05-.01.11-.03.16-.01.05-.04.11-.06.17l-.04.07-.07.11-.06.09-.13.16c-.15.19-.32.39-.52.6-.2.2-.42.4-.65.59-.23.18-.47.36-.72.52-.26.16-.52.32-.8.45-.27.13-.55.25-.84.36z"></path>
                </svg>
                <span class="ml-3">WhatsApp Template</span>
            </a>
            </li>
            <li>
                <a href="{{ route('admin.terms.edit') }}" class="flex items-center px-4 py-2 hover:bg-gray-100 rounded {{ request()->routeIs('admin.terms.edit') ? 'bg-gray-200 font-semibold' : '' }}">
                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16h8M8 12h8m-8-4h8M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Syarat & Ketentuan
                </a>
            </li>

        @endif
        {{-- khusus staff --}}
        @if(auth()->user()->isStaf())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('lelang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            {{-- <li><a href="{{ route('barang_gadai.diperpanjang_dm') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white"><i class="fas fa-box-open mr-2"></i> Ubah NoBon </a></li> --}}
            <li>
                <a href="{{ route('barang_gadai.diperpanjang_dm') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white flex justify-between items-center">
                    <span><i class="fas fa-box-open mr-2"></i> Ubah NoBon</span>
                    @if ($jumlahUbahNoBon > 0)
                        <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $jumlahUbahNoBon }}
                        </span>
                    @endif
                </a>
            </li>
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

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
            <a href="{{ route('notifikasi.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('notifikasi.index') ? 'bg-green-600 font-semibold' : '' }}">
                <i class="fas fa-bell mr-2"></i> Notifikasi
            </a>
        </li>
        @if(auth()->user()->isNasabah())

        <li>
            <a href="{{ route('nasabah.terms') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('nasabah.terms') ? 'bg-green-600 font-semibold text-white' : '' }}">
               <i class="fa fa-file-contract"></i> <span class="ml-2"> Syarat & Ketentuan </span>
            </a>
        </li>

        @endif


        {{-- Khusus Superadmin --}}
        @if(auth()->user()->isSuperadmin())
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('transaksi_gadai.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('barang_gadai.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li>
                <a href="{{ route('superadmin.kategori-barang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('superadmin.kategori-barang.index') ? 'bg-green-600 font-semibold' : '' }}">
                    <i class="fas fa-tags mr-2"></i> Kategori Barang
                </a>
            </li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('nasabah.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('superadmin.cabang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('superadmin.cabang.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-building mr-2"></i> Cabang</a></li>
            <li><a href="{{ route('superadmin.admins.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('superadmin.admins.index') ? 'bg-green-600 font-semibold' : '' }}">
                <i class="fas fa-users-cog mr-2"></i> Admin
            </a></li>
            <li>
                <a href="{{ route('superadmin.bunga-tenor.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('superadmin.bunga-tenor.index') ? 'bg-green-600 font-semibold' : '' }}">
                    <i class="fas fa-percentage mr-2"></i> Bunga & Tenor
                </a>
            </li>
            <li><a href="{{ route('lelang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('lelang.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li>
                <a href="{{ route('superadmin.laporan.index') }}" 
                class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white 
                {{ request()->routeIs('superadmin.laporan.index') ? 'bg-green-600 font-semibold' : '' }}">
                    <i class="fas fa-chart-line mr-2"></i> Laporan Gadai
                </a>
            </li>
            @endif

        {{-- Khusus Admin --}}
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('barang_gadai.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('transaksi_gadai.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('nasabah.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('lelang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('lelang.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li>
                <a href="{{ route('admin.barang-lelang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('admin.barang-lelang.*') ? 'bg-green-600 font-semibold' : '' }}">
                    <i class="fas fa-gavel mr-2"></i> Barang Lelang
                </a>
            </li>
            <li>
                <a href="{{ route('admin.staff.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('admin.staff.index') ? 'bg-green-600 font-semibold' : '' }}">
                    <i class="fas fa-user-cog mr-2"></i> Staff
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ str_contains(request()->route()->getName(), 'admin.laporan') ? 'bg-green-600 font-semibold' : '' }}">
                    <i class="fas fa-file-alt mr-2"></i> Laporan
                </a>
            </li>
            <a href="{{ route('admin.whatsapp_template.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('admin.whatsapp_template.index') ? 'bg-green-600 font-semibold' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span class="ml-2">WhatsApp Template</span>
            </a>
            </li>
            <li>
                <a href="{{ route('admin.terms.edit') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('admin.terms.edit') ? 'bg-green-600 font-semibold' : '' }}">
                    <i class="fa fa-file-contract"></i> <span class="ml-2">Syarat & Ketentuan</span>
                </a>
            </li>

        @endif
        {{-- khusus staff --}}
        @if(auth()->user()->isStaf())
            <li><a href="{{ route('barang_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('barang_gadai.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-box-open mr-2"></i> Barang Gadai</a></li>
            <li><a href="{{ route('transaksi_gadai.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('transaksi_gadai.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Gadai</a></li>
            <li><a href="{{ route('nasabah.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('nasabah.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-user-tie mr-2"></i> Nasabah</a></li>
            <li><a href="{{ route('lelang.index') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white {{ request()->routeIs('lelang.index') ? 'bg-green-600 font-semibold' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Lelang</a></li>
            <li>
                <a href="{{ route('barang_gadai.diperpanjang_dm') }}" class="py-2 no-underline block px-4 rounded hover:bg-gray-700 text-white flex justify-between items-center {{ request()->routeIs('barang_gadai.diperpanjang_dm') ? 'bg-green-600 font-semibold' : '' }}">
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

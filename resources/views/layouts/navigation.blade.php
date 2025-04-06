<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Hamburger -->
                <button @click="sidebarOpen = !sidebarOpen" class="mr-4 flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none md:hidden">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>

                <p class="ml-4 mt-2 text-xl text-green-600 font-semibold">
                    Enoni Cell Gadai
                </p>
            </div>

            <!-- Info Cabang dan Profil -->
            <div class="hidden sm:flex sm:items-center">
                @if(auth()->user()->isadmin())
                    <p class="mt-6 m-6 text-lg text-gray-600">
                        {{ auth()->user()->cabang->nama_cabang ?? 'Cabang tidak ditemukan' }},
                        {{ auth()->user()->cabang->alamat ?? 'Alamat tidak tersedia' }},
                        {{ auth()->user()->cabang->kontak ?? 'Nomor tidak tersedia' }}
                    </p>
                @endif

                <!-- Profil -->
                <div class="relative mt-3 m-6">
                    <button id="profileButton" class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-500 text-white text-lg font-bold focus:outline-none">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                    </button>

                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-50">
                        <div class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            {{ strtoupper(auth()->user()->nama) }}
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-200">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const profileButton = document.getElementById("profileButton");
        const profileDropdown = document.getElementById("profileDropdown");

        profileButton?.addEventListener("click", function (e) {
            e.stopPropagation();
            profileDropdown?.classList.toggle("hidden");
        });

        document.addEventListener("click", function (e) {
            if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add("hidden");
            }
        });
    });
</script>

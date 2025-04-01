<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <!-- {{ __('Dashboard') }} -->
                        <p class="mt-2 py-2 text-2xl text-green-600 font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl lg:text-balance">Enoni Cell Gadai</p>
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(auth()->user()->isadmin())
                    <p class="mt-6 m-6 text-lg/1 text-gray-600">
                        {{ auth()->user()->cabang->nama_cabang ?? 'Cabang tidak ditemukan' }},
                        {{ auth()->user()->cabang->alamat ?? 'Alamat tidak tersedia' }}
                        {{ auth()->user()->cabang->kontak ?? 'Nomor tidak tersedia' }}
                    </p>
                @endif

                <!-- Profile Dropdown Button -->
                <div class="mt-3 m-6">
                    <div class="relative">
                        <button id="profileButton" class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-500 text-white text-lg font-bold focus:outline-none">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        </button>

                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                            @if(auth()->user()->isNasabah())
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                                Profile
                            </a>
                            @endif
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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var profileButton = document.getElementById("profileButton");
        var profileDropdown = document.getElementById("profileDropdown");

        if (profileButton && profileDropdown) {
            profileButton.addEventListener("click", function(event) {
                event.stopPropagation();
                profileDropdown.classList.toggle("hidden");
            });

            document.addEventListener("click", function(event) {
                if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.add("hidden");
                }
            });
        }
    });
</script>

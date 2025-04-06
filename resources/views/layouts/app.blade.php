<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Enoni Cell') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ open: false }">
        <div class="min-h-screen flex bg-gray-100 dark:bg-gray-900">

            {{-- Sidebar Responsive --}}
            @include('components.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col md:ml-64">

                {{-- Navbar / Topbar --}}
                <div class="bg-green-800 text-white flex items-center px-4 py-3 md:px-8 shadow-md">
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden mr-4 text-2xl" @click="open = true">☰</button>
                    <h1 class="text-xl font-bold">ENONI CELL</h1>
                </div>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-2">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>

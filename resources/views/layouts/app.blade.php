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

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
    {{-- Sidebar --}}
    <div 
        class="fixed inset-y-0 left-0 w-64 z-40 bg-gray-800 transform transition-transform duration-300 ease-in-out text-white"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    >
        @include('components.sidebar')
    </div>

    {{-- Overlay (untuk mobile) --}}
    <div 
        class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden" 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        x-transition.opacity
    ></div>

    {{-- Main Content --}}
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 md:pl-64 transition-all duration-300">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="p-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

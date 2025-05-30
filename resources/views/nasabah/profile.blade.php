@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-32"></div>
            <div class="px-6 py-4 relative">
                <div class="absolute -top-16 left-6">
                    <div class="w-32 h-32 rounded-full border-4 border-white bg-white overflow-hidden">
                        <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="ml-36">
                    <h2 class="text-3xl font-bold text-gray-800">{{ $nasabah->nama }}</h2>
                    <p class="text-gray-600">{{ auth()->user()->cabang->nama_cabang }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-id-card text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">NIK</p>
                            <p class="font-medium">{{ $nasabah->nik }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="font-medium">{{ $nasabah->alamat }}</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Nomor Telepon</p>
                            <p class="font-medium">{{ $nasabah->telepon }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-green-500 w-6"></i>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Username</p>
                            <p class="font-medium">{{ auth()->user()->username }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Status Akun</h3>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full {{ $nasabah->status_blacklist ? 'bg-red-500' : 'bg-green-500' }} mr-3"></div>
                <p class="font-medium">{{ $nasabah->status_blacklist ? 'Akun Terblokir' : 'Akun Aktif' }}</p>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Ubah Password</h3>
                <button onclick="togglePasswordForm()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Ubah Password
                </button>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="passwordForm" class="hidden">
                <form action="{{ route('nasabah.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Simpan Password Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordForm() {
    const form = document.getElementById('passwordForm');
    form.classList.toggle('hidden');
}
</script>
@endsection

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
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Status Akun</h3>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full {{ $nasabah->status_blacklist ? 'bg-red-500' : 'bg-green-500' }} mr-3"></div>
                <p class="font-medium">{{ $nasabah->status_blacklist ? 'Akun Terblokir' : 'Akun Aktif' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

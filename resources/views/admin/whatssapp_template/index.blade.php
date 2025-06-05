@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Template WhatsApp</h2>
            <a href="{{ route('admin.whatsapp_template.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">Tambah Template</a>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block">
            <table class="w-full border rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Message</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr class="border-t {{ $template->is_active ? 'bg-green-50' : '' }}">
                            <td class="p-3">{{ $template->type }}</td>
                            <td class="p-3">{{ $template->message }}</td>
                            <td class="p-3">
                                @if($template->is_active)
                                    <span class="text-green-600 font-semibold">Aktif</span>
                                @else
                                    <span class="text-gray-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-3 space-x-2">
                                <a href="{{ route('admin.whatsapp_template.edit', $template->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg inline-block transition duration-200">Edit</a>

                                <form action="{{ route('admin.whatsapp_template.destroy', $template->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition duration-200">Hapus</button>
                                </form>

                                @if(!$template->is_active)
                                    <form action="{{ route('admin.whatsapp_template.activate', $template->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg transition duration-200">Aktifkan</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.whatsapp_template.deactivate', $template->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg transition duration-200">Nonaktifkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @foreach($templates as $template)
                <div class="bg-white rounded-lg shadow-md p-4 {{ $template->is_active ? 'border-l-4 border-green-500' : '' }}">
                    <div class="space-y-2">
                        <div class="flex justify-between items-start">
                            <div class="font-semibold text-gray-700">Type:</div>
                            <div>{{ $template->type }}</div>
                        </div>
                        <div class="flex justify-between items-start">
                            <div class="font-semibold text-gray-700">Message:</div>
                            <div class="text-right flex-1 ml-2">{{ $template->message }}</div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="font-semibold text-gray-700">Status:</div>
                            <div>
                                @if($template->is_active)
                                    <span class="text-green-600 font-semibold">Aktif</span>
                                @else
                                    <span class="text-gray-500">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="pt-3 flex flex-wrap gap-2">
                            <a href="{{ route('admin.whatsapp_template.edit', $template->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm transition duration-200">Edit</a>

                            <form action="{{ route('admin.whatsapp_template.destroy', $template->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm transition duration-200">Hapus</button>
                            </form>

                            @if(!$template->is_active)
                                <form action="{{ route('admin.whatsapp_template.activate', $template->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm transition duration-200">Aktifkan</button>
                                </form>
                            @else
                                <form action="{{ route('admin.whatsapp_template.deactivate', $template->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg text-sm transition duration-200">Nonaktifkan</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

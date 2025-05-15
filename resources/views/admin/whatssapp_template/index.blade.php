@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Template WhatsApp</h2>
            <a href="{{ route('admin.whatsapp_template.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg">Tambah Template</a>
        </div>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Type</th>
                    <th class="p-2">Message</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                    <tr class="border-t">
                        <td class="p-2">{{ $template->type }}</td>
                        <td class="p-2">{{ $template->message }}</td>
                        <td class="p-2">
                            <a href="{{ route('admin.whatsapp_template.edit', $template->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded-lg">Edit</a>
                            <form action="{{ route('admin.whatsapp_template.destroy', $template->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 text-white px-2 py-1 rounded-lg">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

{{-- The Master doesn't talk, he acts. --}}
<table class="w-full border-collapse border border-gray-300 shadow-md mt-4">
    <thead class="bg-gray-200 text-gray-700">
        <tr>
            <th class="border border-gray-300 px-4 py-2 text-left">Nama Cabang</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Id Cabang</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Alamat</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Kontak</th>
            <th class="border border-gray-300 px-4 py-2 text-center"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cabangs as $cabang)
        <tr class="hover:bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">{{ $cabang->nama_cabang }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $cabang->id_cabang }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $cabang->alamat }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $cabang->kontak }}</td>
            <td class="border border-gray-300 px-4 py-2 text-center">
                <a href="{{ route('superadmin.cabang.edit', $cabang->id_cabang) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Edit</a>
<button wire:click="delete({{ $cabang->id_cabang }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Hapus cabang ini?')">Hapus</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@if (session()->has('message'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
        {{ session('message') }}
    </div>
@endif


<table class="w-full border-collapse border border-gray-300 shadow-md mt-4">
    <thead class="bg-gray-200 text-gray-700">
        <tr>
            <th class="border border-gray-300 px-4 py-2 text-left">Nama Cabang</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Id Cabang</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Alamat</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Kontak</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Lokasi</th> <!-- Tambahan -->
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

            <!-- Kolom Google Maps -->
            <td class="border border-gray-300 px-4 py-2 text-center">
                @if($cabang->google_maps_link)
                    <a href="{{ $cabang->google_maps_link }}" target="_blank" class="text-blue-600 hover:underline" title="Lihat di Google Maps">
                        <i class="fas fa-map-marked-alt fa-lg"></i>
                    </a>
                @else
                    <span class="text-gray-400 italic">Tidak tersedia</span>
                @endif
            </td>

            <td class="border border-gray-300 px-4 py-2 text-center">
                <a href="{{ route('superadmin.cabang.edit', $cabang->id_cabang) }}"
                class="text-sm no-underline bg-green-500 text-white m-1 px-3 py-1 rounded hover:bg-green-600 inline-flex items-center gap-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('superadmin.cabang.destroy', $cabang->id_cabang) }}" method="POST"
                    style="display:inline;" onsubmit="return confirm('Hapus cabang ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-sm bg-red-500 text-white m-1 px-3 py-1 rounded hover:bg-red-600 inline-flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

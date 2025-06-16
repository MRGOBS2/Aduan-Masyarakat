
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                Daftar Aduan
            </h2>
            <a href="{{ route('aduan.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md 
                      font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none">
                + Tambah Aduan
            </a>
        </div>

          <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <table class="min-w-full border divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pelapor</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($aduans as $aduan)
                            <tr>
                                <td class="px-4 py-2">{{ $aduan->judul }}</td>
                                <td class="px-4 py-2">{{ $aduan->user->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $aduan->kategori->nama ?? '-' }}</td>
                                <td class="px-4 py-2" x-data="{ show: false }">
    @if($aduan->gambar_aduan)
        <img 
            src="{{ asset('storage/' . $aduan->gambar_aduan) }}" 
            alt="Gambar Aduan" 
            class="h-12 w-auto cursor-pointer"
            @click="show = true"
        >

        <!-- Modal -->
        <div 
            x-show="show"
            x-transition
            style="display: none"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.outside="show = false"
        >
            <div 
                class="bg-white p-4 rounded shadow-lg max-w-xl w-full relative"
                @click.away="show = false"
            >
                <button 
                    class="absolute top-2 right-2 text-gray-700 hover:text-red-500 text-xl font-bold"
                    @click="show = false"
                >
                    &times;
                </button>
                <img 
                    src="{{ asset('storage/' . $aduan->gambar_aduan) }}" 
                    alt="Preview Gambar" 
                    class="w-full h-auto rounded"
                >
            </div>
        </div>
    @else
        -
    @endif
</td>

                                <td class="px-4 py-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $aduan->status == 'diproses' ? 'bg-yellow-100 text-yellow-800' : 
                                            ($aduan->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $aduan->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $aduan->created_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Tidak ada data aduan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>


  

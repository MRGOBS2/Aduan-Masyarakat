<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 leading-tight">
            Tambah Aduan
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-xl rounded-lg space-y-6">

                <form action="{{ route('aduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Pelapor --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pelapor</label>
                            <input type="text" value="{{ auth()->user()->name }}"
                                   class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 w-full text-gray-700 cursor-not-allowed" disabled>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-1">Kategori *</label>
                            <select name="kategori_id" id="kategori_id" required
                                    class="border border-gray-300 rounded-md px-4 py-2 w-full focus:ring-2 focus:ring-yellow-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Judul --}}
                        <div>
                            <label for="judul" class="block text-sm font-semibold text-gray-700 mb-1">Judul Aduan *</label>
                            <input type="text" name="judul" id="judul"
                                   class="border border-gray-300 rounded-md px-4 py-2 w-full focus:ring-2 focus:ring-yellow-500" required>
                        </div>

                        {{-- Lokasi --}}
                        <div>
                            <label for="lokasi" class="block text-sm font-semibold text-gray-700 mb-1">Lokasi *</label>
                            <input type="text" name="lokasi" id="lokasi"
                                   class="border border-gray-300 rounded-md px-4 py-2 w-full focus:ring-2 focus:ring-yellow-500" required>
                        </div>

                        {{-- Isi Aduan --}}
                        <div class="md:col-span-2">
                            <label for="isi" class="block text-sm font-semibold text-gray-700 mb-1">Isi Aduan *</label>
                            <textarea name="isi_aduan" id="isi" rows="4"
                                      class="border border-gray-300 rounded-md px-4 py-2 w-full focus:ring-2 focus:ring-yellow-500"
                                      required></textarea>
                        </div>

                        {{-- Gambar Aduan --}}
                        <div>
                            <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-1">Gambar Aduan</label>
                            <input type="file" name="gambar" id="gambar"
                                   class="border border-gray-300 rounded-md px-4 py-2 w-full file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                        </div>

                        {{-- Tanggal Aduan --}}
                        <div>
                            <label for="tanggal_aduan" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Aduan *</label>
                            <input type="datetime-local" name="tanggal_aduan" id="tanggal_aduan"
                                   value="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="border border-gray-300 rounded-md px-4 py-2 w-full focus:ring-2 focus:ring-yellow-500" required>
                        </div>

                        {{-- Status --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <div class="flex gap-6 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="diproses" checked class="form-radio text-yellow-600">
                                    <span class="ml-2 text-sm text-gray-700">Diproses</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="selesai" class="form-radio text-green-600">
                                    <span class="ml-2 text-sm text-gray-700">Selesai</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-start gap-4 pt-6">
                        <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-6 rounded shadow transition">
                            Submit
                        </button>
                        <a href="{{ route('dashboard') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded shadow transition">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

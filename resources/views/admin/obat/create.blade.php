<x-layouts.app title="Tambah Obat">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('obat.index') }}" class="text-sm font-semibold text-primary hover:underline flex items-center gap-2 mb-2">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Data Obat
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Tambah Obat Baru
        </h2>
    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border max-w-2xl">
        <div class="card-body p-6">
            <form action="{{ route('obat.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    {{-- Nama Obat --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama Obat</label>
                        <input type="text" name="nama_obat" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Contoh: Paracetamol 500mg">
                    </div>

                    {{-- Kemasan --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Kemasan</label>
                        <input type="text" name="kemasan"
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Contoh: Strip @ 10 tablet">
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Contoh: 5000">
                    </div>

                    {{-- [TAMBAHAN UAS] Input Stok Awal --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Stok Awal Obat</label>
                        <input type="number" name="stok" value="0" min="0" required
                            class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Contoh: 50">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                    <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white font-semibold rounded-xl transition">
                        Simpan Obat
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
<x-organizer-layout :title="'Tiket: ' . $concert->title">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('organizer.concerts.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Kembali</a>
        <span class="text-stone-300">/</span>
        <span class="text-sm text-stone-500">Kategori & Kuota Tiket</span>
    </div>

    <h1 class="mt-6 text-2xl font-bold tracking-tight">Kategori & Kuota Tiket</h1>
    <p class="mt-1 text-sm text-stone-500">{{ $concert->title }}</p>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">

        {{-- Form tambah kategori baru --}}
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="font-semibold">Tambah Kategori Baru</h2>
                <form method="POST" action="{{ route('organizer.ticket-types.store', $concert) }}" class="mt-4 space-y-3">
                    @csrf
                    @if ($errors->any())
                        <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-800">
                            <ul class="list-inside list-disc space-y-0.5">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-stone-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name" placeholder="mis. VIP, Festival" value="{{ old('name') }}" required
                               class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" placeholder="150000" min="0" value="{{ old('price') }}" required
                               class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone-700 mb-1">Total Kuota</label>
                        <input type="number" name="quota" placeholder="100" min="1" value="{{ old('quota') }}" required
                               class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone-700 mb-1">Maks per Order</label>
                        <input type="number" name="max_per_order" placeholder="4" min="1" value="{{ old('max_per_order', 4) }}" required
                               class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">+ Tambah Kategori</button>
                </form>
            </div>
        </div>

        {{-- Daftar kategori --}}
        <div class="lg:col-span-2">
            <h2 class="font-semibold mb-3">Daftar Kategori Tiket</h2>
            <div class="space-y-3">
                @forelse ($concert->ticketTypes as $type)
                    <div class="rounded-xl border border-stone-200 bg-white p-5">
                        <form method="POST" action="{{ route('organizer.ticket-types.update', [$concert, $type]) }}">
                            @csrf
                            @method('PUT')
                            <div class="grid gap-3 sm:grid-cols-4">
                                <div>
                                    <label class="block text-xs font-medium text-stone-700 mb-1">Kategori</label>
                                    <input type="text" name="name" value="{{ $type->name }}" required
                                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-stone-700 mb-1">Harga (Rp)</label>
                                    <input type="number" name="price" value="{{ $type->price }}" min="0" required
                                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-stone-700 mb-1">Sisa Kuota</label>
                                    <input type="number" name="quota" value="{{ $type->quota }}" min="0" required
                                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-stone-700 mb-1">Maks / Order</label>
                                    <input type="number" name="max_per_order" value="{{ $type->max_per_order }}" min="1" required
                                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                </div>
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <button type="submit" class="rounded-lg bg-teal-700 px-4 py-1.5 text-sm font-medium text-white hover:bg-teal-800">Simpan Perubahan</button>
                        </form>
                                <form method="POST" action="{{ route('organizer.ticket-types.destroy', [$concert, $type]) }}"
                                      onsubmit="return confirm('Hapus kategori {{ addslashes($type->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-red-200 px-4 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-stone-300 p-8 text-center text-sm text-stone-500">
                        Belum ada kategori tiket untuk konser ini. Tambahkan dari form di sebelah kiri.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-organizer-layout>

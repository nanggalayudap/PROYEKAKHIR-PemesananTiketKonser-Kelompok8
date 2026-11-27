<x-organizer-layout :title="'Detail: ' . $concert->title">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('organizer.concerts.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Kembali</a>
        <span class="text-stone-300">/</span>
        <span class="text-sm text-stone-500">Detail Konser</span>
    </div>

    <div class="mt-6 grid gap-8 lg:grid-cols-3">

        {{-- Info utama --}}
        <div class="lg:col-span-2 space-y-6">
            @if ($concert->banner)
                <img src="{{ asset('storage/' . $concert->banner) }}" alt="{{ $concert->title }}" class="h-56 w-full rounded-xl object-cover">
            @else
                <div class="flex h-56 items-center justify-center rounded-xl bg-teal-900 text-2xl font-bold text-teal-50">{{ $concert->title }}</div>
            @endif

            <div>
                <h1 class="text-2xl font-bold tracking-tight">{{ $concert->title }}</h1>
                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-stone-500">Status Moderasi</dt>
                         <dd>
                             @php $sc = match ($concert->status) { 'published' => 'bg-green-100 text-green-800', 'closed' => 'bg-stone-200 text-stone-700', default => 'bg-amber-100 text-amber-800' }; @endphp
                             <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $sc }}">{{ $concert->status }}</span>
                         </dd>
                    </div>
                    <div><dt class="text-stone-500">Line-up</dt><dd class="font-medium">{{ $concert->lineup }}</dd></div>
                    <div><dt class="text-stone-500">Kota & Venue</dt><dd class="font-medium">{{ $concert->venue }}, {{ $concert->city }}</dd></div>
                    <div><dt class="text-stone-500">Tanggal & Jam</dt><dd class="font-medium">{{ $concert->event_date->format('d M Y, H:i') }} WIB</dd></div>
                </dl>
                <p class="mt-4 text-sm leading-relaxed text-stone-700">{{ $concert->description }}</p>
            </div>

            {{-- Kategori tiket --}}
            <div>
                <h2 class="text-base font-semibold">Daftar Kategori Tiket</h2>
                <div class="mt-3 overflow-x-auto rounded-xl border border-stone-200 bg-white">
                    <table class="min-w-full text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">Kategori</th>
                                <th class="px-4 py-3 font-medium">Harga</th>
                                <th class="px-4 py-3 font-medium">Sisa Kuota</th>
                                <th class="px-4 py-3 font-medium">Maks / Order</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($concert->ticketTypes as $type)
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $type->name }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($type->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $type->quota }}</td>
                                    <td class="px-4 py-3">{{ $type->max_per_order }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-6 text-center text-stone-500">Belum ada kategori tiket.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Aksi cepat --}}
        <aside class="h-fit rounded-xl border border-stone-200 bg-white p-5 space-y-3">
            <h2 class="font-semibold">Aksi</h2>
            <a href="{{ route('organizer.concerts.edit', $concert) }}"
               class="block w-full rounded-lg border border-stone-300 px-4 py-2 text-center text-sm font-medium text-stone-700 hover:bg-stone-50">Edit Konser</a>
            <a href="{{ route('organizer.ticket-types.index', $concert) }}"
               class="block w-full rounded-lg bg-teal-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-teal-800">Kelola Kategori Tiket</a>
        </aside>
    </div>
</x-organizer-layout>

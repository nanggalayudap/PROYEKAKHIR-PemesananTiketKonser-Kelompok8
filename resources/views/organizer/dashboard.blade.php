<x-organizer-layout title="Dashboard">
    <h1 class="text-2xl font-bold tracking-tight">Dashboard Event Organizer</h1>
    <p class="mt-1 text-sm text-stone-500">Selamat datang, {{ Auth::user()->name }}.</p>

    {{-- Stat cards --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Event Aktif (Published)</p>
            <p class="mt-1 text-3xl font-bold text-teal-800">{{ $eventAktif }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Total Tiket Terjual</p>
            <p class="mt-1 text-3xl font-bold text-teal-800">{{ $tiketTerjual }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Total Pendapatan</p>
            <p class="mt-1 text-2xl font-bold text-teal-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('organizer.orders.index', ['status' => 'waiting_verification']) }}"
           class="rounded-xl border border-amber-200 bg-amber-50 p-5 hover:border-amber-400">
            <p class="text-sm text-amber-700">Menunggu Verifikasi</p>
            <p class="mt-1 text-3xl font-bold text-amber-700">{{ $menungguVerifikasi }}</p>
        </a>
    </div>

    {{-- Daftar konser --}}
    <div class="mt-10">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Konser & Sisa Kuota</h2>
            <a href="{{ route('organizer.concerts.create') }}" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">+ Buat Konser Baru</a>
        </div>

        <div class="mt-4 overflow-x-auto rounded-xl border border-stone-200 bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Judul Konser</th>
                        <th class="px-4 py-3 font-medium">Kota & Venue</th>
                        <th class="px-4 py-3 font-medium">Tanggal Acara</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Kategori & Sisa Kuota</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($daftarKonser as $konser)
                        @php
                            $statusClass = match ($konser->status) {
                                'published' => 'bg-green-100 text-green-800',
                                'closed'    => 'bg-stone-200 text-stone-700',
                                default     => 'bg-amber-100 text-amber-800',
                            };
                        @endphp
                        <tr class="hover:bg-stone-50">
                            <td class="px-4 py-3 font-medium">{{ $konser->title }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $konser->city }} — {{ $konser->venue }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $konser->event_date->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $konser->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-stone-600">
                                @forelse ($konser->ticketTypes as $type)
                                    <div>{{ $type->name }}: <span class="font-medium">{{ $type->quota }} sisa</span> (Rp {{ number_format($type->price, 0, ',', '.') }})</div>
                                @empty
                                    <em class="text-stone-400">Belum ada kategori tiket</em>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('organizer.ticket-types.index', $konser) }}" class="text-teal-700 hover:underline">Atur Tiket</a>
                                    <a href="{{ route('organizer.concerts.show', $konser) }}" class="text-stone-500 hover:underline">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-stone-500">Anda belum membuat konser. <a href="{{ route('organizer.concerts.create') }}" class="text-teal-700 underline">Buat sekarang</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-organizer-layout>

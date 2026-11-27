<x-organizer-layout title="Konser Saya">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-bold tracking-tight">Manajemen Konser Saya</h1>
        <a href="{{ route('organizer.concerts.create') }}" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">+ Tambah Konser Baru</a>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Judul Konser</th>
                    <th class="px-4 py-3 font-medium">Kota & Venue</th>
                    <th class="px-4 py-3 font-medium">Tanggal Acara</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Kategori</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($daftarKonser as $concert)
                    @php
                        $statusClass = match ($concert->status) {
                            'published' => 'bg-green-100 text-green-800',
                            'closed'    => 'bg-stone-200 text-stone-700',
                            default     => 'bg-amber-100 text-amber-800',
                        };
                    @endphp
                    <tr class="hover:bg-stone-50">
                        <td class="px-4 py-3 font-medium">{{ $concert->title }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $concert->city }} — {{ $concert->venue }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $concert->event_date->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $concert->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $concert->ticketTypes->count() }} kategori</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('organizer.ticket-types.index', $concert) }}" class="text-teal-700 hover:underline">Tiket</a>
                                <a href="{{ route('organizer.concerts.show', $concert) }}" class="text-stone-500 hover:underline">Detail</a>
                                <a href="{{ route('organizer.concerts.edit', $concert) }}" class="text-stone-500 hover:underline">Edit</a>
                                <form action="{{ route('organizer.concerts.destroy', $concert) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus konser {{ addslashes($concert->title) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-stone-500">Belum ada konser yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $daftarKonser->links() }}</div>
</x-organizer-layout>

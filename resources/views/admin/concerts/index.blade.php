<x-admin-layout title="Moderasi Konser">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Moderasi & Kurasi Konser</h1>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.concerts.index') }}" class="mt-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau kota…"
               class="rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
        <select name="status" class="rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <option value="">Semua Status</option>
            <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="closed"    {{ request('status') === 'closed'    ? 'selected' : '' }}>Closed</option>
        </select>
        <button type="submit" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Filter</button>
        <a href="{{ route('admin.concerts.index') }}" class="rounded-lg border border-stone-300 px-4 py-2 text-sm text-stone-600 hover:bg-stone-50">Reset</a>
    </form>

    <div class="mt-5 overflow-x-auto rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Judul Konser</th>
                    <th class="px-4 py-3 font-medium">Organizer</th>
                    <th class="px-4 py-3 font-medium">Kota & Venue</th>
                    <th class="px-4 py-3 font-medium">Tanggal Acara</th>
                    <th class="px-4 py-3 font-medium">Status</th>
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
                        <td class="px-4 py-3 text-stone-600">{{ $concert->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $concert->city }} — {{ $concert->venue }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $concert->event_date->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $concert->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.concerts.show', $concert) }}" class="text-teal-700 hover:underline">Detail & Moderasi</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-stone-500">Belum ada data konser.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $daftarKonser->withQueryString()->links() }}</div>
</x-admin-layout>

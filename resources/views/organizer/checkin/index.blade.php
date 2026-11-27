<x-organizer-layout title="Check-In Gate">
    <h1 class="text-2xl font-bold tracking-tight">Validasi Check-In Gate</h1>
    <p class="mt-1 text-sm text-stone-500">Masukkan atau pindai kode tiket penonton untuk validasi masuk (anti-duplikasi).</p>

    {{-- Form Check-In --}}
    <div class="mt-6 max-w-xl rounded-xl border border-stone-200 bg-white p-5">
        <form method="POST" action="{{ route('organizer.checkin.store') }}" class="flex flex-col gap-3 sm:flex-row">
            @csrf
            <input type="text" name="ticket_code" placeholder="Contoh: TIX-2026-8A9X" autofocus required
                   class="flex-1 rounded-lg border border-stone-300 px-3 py-2 text-sm font-mono focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <button type="submit" class="rounded-lg bg-teal-700 px-5 py-2 text-sm font-medium text-white hover:bg-teal-800">
                Validasi Masuk
            </button>
        </form>
    </div>

    {{-- Riwayat Check-In --}}
    <div class="mt-8">
        <h2 class="text-lg font-semibold">Riwayat Check-In Terbaru</h2>
        <div class="mt-3 overflow-x-auto rounded-xl border border-stone-200 bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Kode Tiket</th>
                        <th class="px-4 py-3 font-medium">Nama Penonton</th>
                        <th class="px-4 py-3 font-medium">Konser</th>
                        <th class="px-4 py-3 font-medium">Kategori</th>
                        <th class="px-4 py-3 font-medium">Waktu Check-In</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($riwayatCheckIn as $ticket)
                        <tr class="hover:bg-stone-50">
                            <td class="px-4 py-3 font-mono text-xs font-medium">{{ $ticket->ticket_code }}</td>
                            <td class="px-4 py-3 font-medium">{{ $ticket->attendee_name }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $ticket->order->concert->title ?? '-' }}</td>
                            <td class="px-4 py-3 text-stone-600">{{ $ticket->ticketType->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-stone-500">{{ $ticket->checked_in_at ? $ticket->checked_in_at->format('d M Y, H:i:s') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-stone-500">Belum ada penonton yang melakukan check-in.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-organizer-layout>

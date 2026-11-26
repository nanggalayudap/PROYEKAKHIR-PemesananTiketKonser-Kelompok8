<x-eventra-layout title="Tiket Saya">
    <h1 class="text-2xl font-bold tracking-tight">Tiket Saya</h1>

    <div class="mt-6 grid gap-5 sm:grid-cols-2">
        @forelse ($tickets as $ticket)
            <article class="overflow-hidden rounded-lg border border-stone-300 bg-white">
                <div class="bg-teal-900 px-4 py-2 text-teal-50">
                    <p class="font-semibold">{{ $ticket->order->concert->title }}</p>
                    <p class="text-xs">{{ $ticket->ticketType->name }}</p>
                </div>
                <div class="flex items-center justify-between gap-3 p-4 text-sm">
                    <div class="space-y-1">
                        <p>{{ $ticket->attendee_name }}</p>
                        <p class="text-stone-500">{{ $ticket->order->concert->event_date->format('d M Y, H:i') }}</p>
                        <p class="font-mono text-xs font-semibold">{{ $ticket->ticket_code }}</p>
                        <a href="{{ route('orders.eticket', $ticket->order) }}" class="inline-block font-medium text-teal-700 hover:underline">Lihat / Unduh Tiket</a>
                    </div>
                    @if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class))
                        <div class="shrink-0">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(90)->generate($ticket->ticket_code) !!}</div>
                    @endif
                </div>
            </article>
        @empty
            <p class="col-span-full rounded border border-dashed border-stone-300 p-8 text-center text-stone-600">Belum ada tiket. Tiket muncul di sini setelah pembayaran disetujui.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $tickets->links() }}</div>
</x-eventra-layout>

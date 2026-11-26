<x-eventra-layout title="E-Ticket">
    <div class="mx-auto max-w-2xl">
        <div class="flex items-center justify-between print:hidden">
            <a href="{{ route('orders.show', $order) }}" class="text-sm text-teal-700 hover:underline">&larr; Detail pesanan</a>
            <button onclick="window.print()" class="rounded bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Cetak / simpan PDF</button>
        </div>

        <div class="mt-6 space-y-6">
            @foreach ($order->tickets as $ticket)
                <article class="break-inside-avoid overflow-hidden rounded-lg border border-stone-300 bg-white">
                    <div class="bg-teal-900 px-5 py-3 text-teal-50">
                        <p class="text-lg font-semibold">{{ $order->concert->title }}</p>
                        <p class="text-sm">{{ $ticket->ticketType->name }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-4 p-5">
                        <dl class="space-y-2 text-sm">
                            <div><dt class="text-stone-500">Pemegang tiket</dt><dd class="font-medium">{{ $ticket->attendee_name }}</dd></div>
                            <div><dt class="text-stone-500">Jadwal</dt><dd class="font-medium">{{ $order->concert->event_date->format('d M Y, H:i') }} WIB</dd></div>
                            <div><dt class="text-stone-500">Lokasi</dt><dd class="font-medium">{{ $order->concert->venue }}, {{ $order->concert->city }}</dd></div>
                            <div><dt class="text-stone-500">Kode tiket</dt><dd class="font-mono font-semibold">{{ $ticket->ticket_code }}</dd></div>
                            @if ($ticket->is_checked_in)
                                <div class="font-medium text-red-700">Sudah digunakan ({{ $ticket->checked_in_at?->format('d M Y, H:i') }})</div>
                            @endif
                        </dl>
                        <div class="shrink-0">
                            @if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class))
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(130)->generate($ticket->ticket_code) !!}
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</x-eventra-layout>

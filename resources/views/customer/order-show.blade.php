@php $status = $order->effectiveStatus(); @endphp
<x-eventra-layout :title="'Pesanan ' . $order->order_code">
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('orders.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Pesanan Saya</a>

        <div class="mt-4 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">{{ $order->order_code }}</h1>
                <p class="text-stone-600">{{ $order->concert->title }} &middot; {{ $order->concert->event_date->format('d M Y, H:i') }}</p>
            </div>
            @include('customer.partials.status-badge', ['status' => $status])
        </div>

        <div class="mt-6 rounded-lg border border-stone-200 bg-white p-5 text-sm">
            @if ($status === 'pending')
                <p>Pesanan menunggu pembayaran sampai {{ $order->expires_at->format('d M Y, H:i') }}.</p>
                <a href="{{ route('orders.payment', $order) }}" class="mt-3 inline-block rounded bg-teal-700 px-4 py-2 font-medium text-white hover:bg-teal-800">Bayar sekarang</a>
            @elseif ($status === 'waiting_verification')
                <p>Bukti pembayaran sudah diterima. Penyelenggara sedang memeriksanya.</p>
            @elseif ($status === 'paid')
                <p>Pembayaran disetujui. E-ticket sudah terbit.</p>
                <a href="{{ route('orders.eticket', $order) }}" class="mt-3 inline-block rounded bg-teal-700 px-4 py-2 font-medium text-white hover:bg-teal-800">Lihat dan unduh E-Ticket</a>
            @elseif ($status === 'rejected')
                <p>Pembayaran ditolak oleh penyelenggara dan kuota tiket dikembalikan. Jika masih ingin membeli, silakan buat pesanan baru.</p>
                <a href="{{ route('concerts.show', $order->concert) }}" class="mt-3 inline-block rounded bg-teal-700 px-4 py-2 font-medium text-white hover:bg-teal-800">Buat pesanan baru</a>
            @else
                <p>Batas waktu pembayaran 24 jam telah habis, pesanan dibatalkan dan kuota dikembalikan.</p>
                <a href="{{ route('concerts.show', $order->concert) }}" class="mt-3 inline-block rounded bg-teal-700 px-4 py-2 font-medium text-white hover:bg-teal-800">Buat pesanan baru</a>
            @endif
        </div>

        <div class="mt-6 rounded-lg border border-stone-200 bg-white p-5">
            <h2 class="font-semibold">Rincian tiket</h2>
            <ul class="mt-3 divide-y divide-stone-100 text-sm">
                @foreach ($order->tickets as $ticket)
                    <li class="flex justify-between py-2">
                        <span>{{ $ticket->attendee_name }} <span class="text-stone-500">({{ $ticket->ticketType->name }})</span></span>
                        @if ($status === 'paid')<span class="font-mono text-xs">{{ $ticket->ticket_code }}</span>@endif
                    </li>
                @endforeach
            </ul>
            <p class="mt-3 flex justify-between border-t border-stone-200 pt-3 text-sm font-semibold">
                <span>Total</span><span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </p>
        </div>
    </div>
</x-eventra-layout>

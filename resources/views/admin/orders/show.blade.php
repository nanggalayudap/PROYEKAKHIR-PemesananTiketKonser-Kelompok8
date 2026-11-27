<x-admin-layout :title="'Audit Pesanan ' . $order->order_code">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Kembali</a>
        <span class="text-stone-300">/</span>
        <span class="text-sm text-stone-500">Detail Audit Pesanan</span>
    </div>

    <h1 class="mt-6 text-2xl font-bold tracking-tight font-mono">{{ $order->order_code }}</h1>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        {{-- Detail pesanan --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="font-semibold mb-3">Informasi Pesanan</h2>
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-stone-500">Pembeli</dt><dd class="font-medium">{{ $order->user->name ?? '-' }}</dd></div>
                    <div><dt class="text-stone-500">Email</dt><dd class="font-medium">{{ $order->user->email ?? '-' }}</dd></div>
                    <div><dt class="text-stone-500">Konser</dt><dd class="font-medium">{{ $order->concert->title ?? '-' }}</dd></div>
                    <div>
                        <dt class="text-stone-500">Status</dt>
                        <dd>
                            @php
                                $statusMap = [
                                    'pending'              => ['Menunggu', 'bg-amber-100 text-amber-800'],
                                    'waiting_verification' => ['Verifikasi', 'bg-blue-100 text-blue-800'],
                                    'paid'                 => ['Lunas', 'bg-green-100 text-green-800'],
                                    'rejected'             => ['Ditolak', 'bg-red-100 text-red-800'],
                                ];
                                [$label, $class] = $statusMap[$order->status] ?? [$order->status, 'bg-stone-200 text-stone-700'];
                            @endphp
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">{{ $label }}</span>
                        </dd>
                    </div>
                    <div><dt class="text-stone-500">Total Tagihan</dt><dd class="font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</dd></div>
                </dl>
            </div>

            {{-- Tiket --}}
            <div>
                <h2 class="font-semibold mb-3">Rincian Tiket Diterbitkan</h2>
                <div class="overflow-x-auto rounded-xl border border-stone-200 bg-white">
                    <table class="min-w-full text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">Kode Tiket</th>
                                <th class="px-4 py-3 font-medium">Nama Pemegang</th>
                                <th class="px-4 py-3 font-medium">Kategori</th>
                                <th class="px-4 py-3 font-medium">Harga</th>
                                <th class="px-4 py-3 font-medium">Status Check-In</th>
                                <th class="px-4 py-3 font-medium">Waktu Check-In</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($order->tickets as $ticket)
                                <tr>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $ticket->ticket_code }}</td>
                                    <td class="px-4 py-3">{{ $ticket->attendee_name }}</td>
                                    <td class="px-4 py-3">{{ $ticket->ticketType->name ?? '-' }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($ticket->ticketType->price ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @if ($ticket->is_checked_in)
                                            <span class="inline-block rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Sudah Check-In</span>
                                        @else
                                            <span class="inline-block rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-medium text-stone-600">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-stone-500">{{ $ticket->checked_in_at ? $ticket->checked_in_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-6 text-center text-stone-500">Belum ada tiket diterbitkan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Bukti pembayaran --}}
        @if ($order->payment_proof)
            <aside class="h-fit rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="font-semibold mb-3">Bukti Pembayaran</h2>
                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" class="w-full rounded-lg border border-stone-200 object-contain">
            </aside>
        @endif
    </div>
</x-admin-layout>

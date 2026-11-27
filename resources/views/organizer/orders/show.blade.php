<x-organizer-layout :title="'Verifikasi Pesanan ' . $order->order_code">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('organizer.orders.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Kembali</a>
        <span class="text-stone-300">/</span>
        <span class="text-sm text-stone-500">Detail Verifikasi Pesanan</span>
    </div>

    <h1 class="mt-6 text-2xl font-bold tracking-tight font-mono">{{ $order->order_code }}</h1>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        {{-- Detail & tiket --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="font-semibold mb-3">Informasi Pesanan</h2>
                <dl class="grid gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-stone-500">Pembeli</dt><dd class="font-medium">{{ $order->user->name ?? '-' }}</dd></div>
                    <div><dt class="text-stone-500">Email</dt><dd class="font-medium">{{ $order->user->email ?? '-' }}</dd></div>
                    <div><dt class="text-stone-500">Konser</dt><dd class="font-medium">{{ $order->concert->title ?? '-' }}</dd></div>
                    <div>
                        <dt class="text-stone-500">Status Saat Ini</dt>
                        <dd>
                            @php
                                $statusMap = [
                                    'pending'              => ['Menunggu', 'bg-amber-100 text-amber-800'],
                                    'waiting_verification' => ['Menunggu Verifikasi', 'bg-blue-100 text-blue-800'],
                                    'paid'                 => ['Lunas', 'bg-green-100 text-green-800'],
                                    'rejected'             => ['Ditolak', 'bg-red-100 text-red-800'],
                                ];
                                [$label, $class] = $statusMap[$order->status] ?? [$order->status, 'bg-stone-200 text-stone-700'];
                            @endphp
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">{{ $label }}</span>
                        </dd>
                    </div>
                    <div class="sm:col-span-2"><dt class="text-stone-500">Total Tagihan</dt><dd class="text-lg font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</dd></div>
                </dl>
            </div>

            {{-- Tiket --}}
            <div>
                <h2 class="font-semibold mb-3">Rincian Tiket Dipesan</h2>
                <div class="overflow-x-auto rounded-xl border border-stone-200 bg-white">
                    <table class="min-w-full text-sm">
                        <thead class="bg-stone-50 text-left text-stone-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">Kode Tiket</th>
                                <th class="px-4 py-3 font-medium">Nama Pemegang</th>
                                <th class="px-4 py-3 font-medium">Kategori</th>
                                <th class="px-4 py-3 font-medium">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($order->tickets as $ticket)
                                <tr>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $ticket->ticket_code }}</td>
                                    <td class="px-4 py-3">{{ $ticket->attendee_name }}</td>
                                    <td class="px-4 py-3">{{ $ticket->ticketType->name ?? '-' }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($ticket->ticketType->price ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Bukti & tindakan --}}
        <aside class="space-y-5">
            @if ($order->payment_proof)
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <h2 class="font-semibold mb-3">Bukti Pembayaran</h2>
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" class="w-full rounded-lg border border-stone-200 object-contain">
                </div>
            @else
                <div class="rounded-xl border border-dashed border-stone-300 bg-stone-50 p-5 text-center text-sm text-stone-500">
                    Pembeli belum mengunggah bukti pembayaran.
                </div>
            @endif

            @if ($order->status === 'waiting_verification')
                <div class="rounded-xl border border-stone-200 bg-white p-5 space-y-3">
                    <h2 class="font-semibold">Tindakan Verifikasi</h2>
                    <form method="POST" action="{{ route('organizer.orders.verify', $order) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" onclick="return confirm('Konfirmasi pembayaran ini menjadi PAID?')"
                                class="w-full rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">
                            ✓ Konfirmasi Pembayaran (Approve)
                        </button>
                    </form>
                    <form method="POST" action="{{ route('organizer.orders.verify', $order) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" onclick="return confirm('Tolak pembayaran ini dan kembalikan kuota tiket?')"
                                class="w-full rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                            ✗ Tolak & Kembalikan Kuota
                        </button>
                    </form>
                </div>
            @endif
        </aside>
    </div>
</x-organizer-layout>

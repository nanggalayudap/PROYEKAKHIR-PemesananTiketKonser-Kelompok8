<x-eventra-layout title="Riwayat Transaksi">
    <h1 class="text-2xl font-bold tracking-tight">Riwayat Transaksi</h1>

    <div class="mt-6 overflow-x-auto rounded-lg border border-stone-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-stone-100 text-left text-stone-600">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Konser</th>
                    <th class="px-4 py-3">Tanggal pesan</th>
                    <th class="px-4 py-3">Tiket</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($orders as $order)
                    @php $status = $order->effectiveStatus(); @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $order->order_code }}</td>
                        <td class="px-4 py-3">{{ $order->concert->title }}</td>
                        <td class="px-4 py-3">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3">{{ $order->tickets_count }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">@include('customer.partials.status-badge', ['status' => $status])</td>
                        <td class="space-x-3 px-4 py-3 text-right">
                            @if ($status === 'pending')
                                <a href="{{ route('orders.payment', $order) }}" class="font-medium text-teal-700 hover:underline">Bayar</a>
                            @endif
                            @if ($status === 'paid')
                                <a href="{{ route('orders.eticket', $order) }}" class="font-medium text-teal-700 hover:underline">E-Ticket</a>
                            @endif
                            <a href="{{ route('orders.show', $order) }}" class="text-stone-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-stone-600">Kamu belum punya pesanan. <a href="{{ route('home') }}" class="text-teal-700 underline">Cari konser</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
</x-eventra-layout>

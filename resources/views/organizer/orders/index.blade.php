<x-organizer-layout title="Verifikasi Pembayaran">
    <h1 class="text-2xl font-bold tracking-tight">Verifikasi Pembayaran Pesanan</h1>

    {{-- Filter --}}
    <form method="GET" action="{{ route('organizer.orders.index') }}" class="mt-6 flex flex-wrap gap-3">
        <select name="status" class="rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <option value="">Semua Status</option>
            <option value="pending"              {{ request('status') === 'pending'              ? 'selected' : '' }}>Pending</option>
            <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
            <option value="paid"                 {{ request('status') === 'paid'                 ? 'selected' : '' }}>Lunas</option>
            <option value="rejected"             {{ request('status') === 'rejected'             ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Filter</button>
        <a href="{{ route('organizer.orders.index') }}" class="rounded-lg border border-stone-300 px-4 py-2 text-sm text-stone-600 hover:bg-stone-50">Reset</a>
    </form>

    <div class="mt-5 overflow-x-auto rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-stone-50 text-left text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Kode Pesanan</th>
                    <th class="px-4 py-3 font-medium">Pembeli</th>
                    <th class="px-4 py-3 font-medium">Konser</th>
                    <th class="px-4 py-3 font-medium">Total</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($daftarPesanan as $order)
                    @php
                        $statusMap = [
                            'pending'              => ['Menunggu', 'bg-amber-100 text-amber-800'],
                            'waiting_verification' => ['Verifikasi', 'bg-blue-100 text-blue-800'],
                            'paid'                 => ['Lunas', 'bg-green-100 text-green-800'],
                            'rejected'             => ['Ditolak', 'bg-red-100 text-red-800'],
                        ];
                        [$label, $class] = $statusMap[$order->status] ?? [$order->status, 'bg-stone-200 text-stone-700'];
                    @endphp
                    <tr class="hover:bg-stone-50">
                        <td class="px-4 py-3 font-mono font-medium">{{ $order->order_code }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $order->concert->title ?? '-' }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">{{ $label }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('organizer.orders.show', $order) }}" class="text-teal-700 hover:underline">Periksa Bukti</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-stone-500">Belum ada pesanan masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $daftarPesanan->withQueryString()->links() }}</div>
</x-organizer-layout>

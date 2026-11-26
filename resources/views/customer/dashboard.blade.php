<x-eventra-layout title="Dashboard">
    <h1 class="text-2xl font-bold tracking-tight">Halo, {{ auth()->user()->name }}</h1>
    <p class="mt-1 text-stone-600">Ringkasan tiket dan pesananmu.</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <a href="{{ route('tickets.index') }}" class="rounded-lg border border-stone-200 bg-white p-5 hover:border-teal-600">
            <p class="text-3xl font-bold text-teal-800">{{ $stats['tickets'] }}</p>
            <p class="text-sm text-stone-600">Tiket aktif</p>
        </a>
        <a href="{{ route('orders.index') }}" class="rounded-lg border border-stone-200 bg-white p-5 hover:border-teal-600">
            <p class="text-3xl font-bold text-amber-700">{{ $stats['pending'] }}</p>
            <p class="text-sm text-stone-600">Menunggu pembayaran</p>
        </a>
        <a href="{{ route('orders.index') }}" class="rounded-lg border border-stone-200 bg-white p-5 hover:border-teal-600">
            <p class="text-3xl font-bold text-blue-700">{{ $stats['waiting'] }}</p>
            <p class="text-sm text-stone-600">Menunggu verifikasi</p>
        </a>
    </div>

    <section class="mt-10">
        <div class="flex items-end justify-between">
            <h2 class="text-xl font-semibold">Pesanan terakhir</h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-teal-700 hover:underline">Riwayat pemesanan</a>
        </div>
        <div class="mt-3 divide-y divide-stone-100 rounded-lg border border-stone-200 bg-white text-sm">
            @forelse ($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between gap-3 px-4 py-3 hover:bg-stone-50">
                    <span><span class="font-medium">{{ $order->concert->title }}</span> <span class="text-stone-500">&middot; {{ $order->tickets_count }} tiket</span></span>
                    @include('customer.partials.status-badge', ['status' => $order->effectiveStatus()])
                </a>
            @empty
                <p class="px-4 py-6 text-center text-stone-600">Belum ada pesanan.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-xl font-semibold">Rekomendasi konser</h2>
        <div class="mt-3 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($recommended as $concert)
                @include('customer.partials.concert-card', ['concert' => $concert])
            @endforeach
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-xl font-semibold">Konser terbaru</h2>
        <div class="mt-3 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($latest as $concert)
                @include('customer.partials.concert-card', ['concert' => $concert])
            @endforeach
        </div>
    </section>
</x-eventra-layout>

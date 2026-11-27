<x-admin-layout title="Dashboard">
    <h1 class="text-2xl font-bold tracking-tight">Dashboard Statistik Global</h1>
    <p class="mt-1 text-sm text-stone-500">Ringkasan kondisi platform Eventra secara keseluruhan.</p>

    {{-- Stat cards --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Total Pengguna</p>
            <p class="mt-1 text-3xl font-bold text-teal-800">{{ $totalPengguna }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Konser Aktif (Published)</p>
            <p class="mt-1 text-3xl font-bold text-teal-800">{{ $konserAktif }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Total Tiket Terjual</p>
            <p class="mt-1 text-3xl font-bold text-teal-800">{{ $tiketTerjual }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Total Perputaran Dana</p>
            <p class="mt-1 text-2xl font-bold text-teal-800">Rp {{ number_format($perputaranDana, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="mt-10 grid gap-8 xl:grid-cols-2">

        {{-- Konser menunggu moderasi --}}
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Konser Menunggu Moderasi</h2>
                <a href="{{ route('admin.concerts.index', ['status' => 'draft']) }}" class="text-sm text-teal-700 hover:underline">Lihat semua</a>
            </div>
            <div class="mt-3 overflow-hidden rounded-xl border border-stone-200 bg-white">
                @forelse ($konserMenungguModerasi as $konser)
                    <div class="flex items-center justify-between gap-4 border-b border-stone-100 px-4 py-3 last:border-b-0">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-sm">{{ $konser->title }}</p>
                            <p class="text-xs text-stone-500">{{ $konser->user->name ?? '-' }} &middot; {{ $konser->city }}</p>
                        </div>
                        <a href="{{ route('admin.concerts.show', $konser) }}" class="shrink-0 rounded bg-teal-700 px-3 py-1 text-xs font-medium text-white hover:bg-teal-800">Tinjau</a>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-sm text-stone-500">Tidak ada konser yang menunggu moderasi.</p>
                @endforelse
            </div>
        </section>

        {{-- Transaksi terbaru --}}
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Transaksi Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-teal-700 hover:underline">Lihat semua</a>
            </div>
            <div class="mt-3 overflow-hidden rounded-xl border border-stone-200 bg-white">
                @forelse ($transaksiTerbaru as $order)
                    @php
                        $statusMap = [
                            'pending'              => ['Menunggu', 'bg-amber-100 text-amber-800'],
                            'waiting_verification' => ['Verifikasi', 'bg-blue-100 text-blue-800'],
                            'paid'                 => ['Lunas', 'bg-green-100 text-green-800'],
                            'rejected'             => ['Ditolak', 'bg-red-100 text-red-800'],
                        ];
                        [$label, $class] = $statusMap[$order->status] ?? [$order->status, 'bg-stone-200 text-stone-700'];
                    @endphp
                    <div class="flex items-center justify-between gap-4 border-b border-stone-100 px-4 py-3 last:border-b-0">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $order->order_code }}</p>
                            <p class="text-xs text-stone-500">{{ $order->user->name ?? '-' }} &middot; {{ $order->concert->title ?? '-' }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">{{ $label }}</span>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-xs text-teal-700 hover:underline">Detail</a>
                        </div>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-sm text-stone-500">Belum ada data transaksi.</p>
                @endforelse
            </div>
        </section>

    </div>
</x-admin-layout>

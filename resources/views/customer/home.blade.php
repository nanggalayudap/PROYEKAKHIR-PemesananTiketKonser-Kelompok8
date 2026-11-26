<x-eventra-layout title="Beranda">
    @if ($featured)
        <section class="overflow-hidden rounded-xl bg-teal-900 text-teal-50">
            <div class="grid md:grid-cols-2">
                <div class="p-8 md:p-12">
                    <p class="text-sm text-teal-200">Konser unggulan</p>
                    <h1 class="mt-2 text-4xl font-bold tracking-tight">{{ $featured->title }}</h1>
                    <p class="mt-3 text-teal-100">{{ $featured->lineup }}</p>
                    <p class="mt-4 text-sm">{{ $featured->event_date->format('d M Y, H:i') }} WIB &middot; {{ $featured->venue }}, {{ $featured->city }}</p>
                    <a href="{{ route('concerts.show', $featured) }}" class="mt-6 inline-block rounded bg-white px-5 py-2.5 text-sm font-medium text-teal-900 hover:bg-teal-50">Lihat Detail</a>
                </div>
                @if ($featured->banner)
                    <img src="{{ asset('storage/' . $featured->banner) }}" alt="{{ $featured->title }}" class="h-full min-h-56 w-full object-cover">
                @endif
            </div>
        </section>
    @else
        <section class="rounded-xl bg-teal-900 p-12 text-teal-50">
            <h1 class="text-3xl font-bold">Selamat datang di Eventra</h1>
            <p class="mt-2">Belum ada konser yang tayang. Pantau terus ya.</p>
        </section>
    @endif

    <section class="mt-12">
        <div class="flex items-end justify-between">
            <h2 class="text-2xl font-bold tracking-tight">Rekomendasi konser</h2>
            <a href="{{ route('concerts.index') }}" class="text-sm text-teal-700 hover:underline">Lihat semua</a>
        </div>
        <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($recommended as $concert)
                @include('customer.partials.concert-card', ['concert' => $concert])
            @empty
                <p class="col-span-full text-stone-600">Belum ada rekomendasi.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-12">
        <h2 class="text-2xl font-bold tracking-tight">Konser yang akan datang</h2>
        <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($upcomingList as $concert)
                @include('customer.partials.concert-card', ['concert' => $concert])
            @empty
                <p class="col-span-full text-stone-600">Belum ada konser lain yang dijadwalkan.</p>
            @endforelse
        </div>
    </section>
</x-eventra-layout>

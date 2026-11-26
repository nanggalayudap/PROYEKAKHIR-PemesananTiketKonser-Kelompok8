@php $minPrice = $concert->ticketTypes->min('price'); @endphp
<a href="{{ route('concerts.show', $concert) }}" class="group overflow-hidden rounded-lg border border-stone-200 bg-white hover:border-teal-600">
    @if ($concert->banner)
        <img src="{{ asset('storage/' . $concert->banner) }}" alt="{{ $concert->title }}" class="h-44 w-full object-cover">
    @else
        <div class="flex h-44 items-center justify-center bg-teal-900 px-4 text-center text-lg font-semibold text-teal-50">{{ $concert->title }}</div>
    @endif
    <div class="p-4">
        <h3 class="font-semibold group-hover:text-teal-800">{{ $concert->title }}</h3>
        <p class="mt-1 text-sm text-stone-600">{{ $concert->lineup }}</p>
        <p class="mt-3 text-sm text-stone-700">{{ $concert->event_date->format('d M Y, H:i') }} &middot; {{ $concert->city }}</p>
        @if ($concert->genre)<p class="mt-1 text-xs text-stone-500">{{ $concert->genre }}</p>@endif
        @if ($minPrice !== null)
            <p class="mt-1 text-sm font-medium text-teal-800">Mulai Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
        @endif
        <span class="mt-3 inline-block text-sm font-medium text-teal-700">Lihat Detail</span>
    </div>
</a>

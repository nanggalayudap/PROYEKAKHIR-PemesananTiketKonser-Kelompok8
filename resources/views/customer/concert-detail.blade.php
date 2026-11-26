@php
    $types = $concert->ticketTypes->map(fn ($t) => [
        'id'    => $t->id,
        'name'  => $t->name,
        'price' => (float) $t->price,
        'quota' => $t->quota,
        'max'   => max(1, min($t->max_per_order, $t->quota)),
    ])->values()->all();
    $firstAvailable = collect($types)->firstWhere('quota', '>', 0)['id'] ?? 0;
@endphp
<x-eventra-layout :title="$concert->title">
    <a href="{{ route('home') }}" class="text-sm text-teal-700 hover:underline">&larr; Kembali ke katalog</a>

    <div class="mt-4 grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            @if ($concert->banner)
                <img src="{{ asset('storage/' . $concert->banner) }}" alt="{{ $concert->title }}" class="h-72 w-full rounded-lg object-cover">
            @else
                <div class="flex h-72 items-center justify-center rounded-lg bg-teal-900 px-6 text-center text-3xl font-bold text-teal-50">{{ $concert->title }}</div>
            @endif

            <h1 class="mt-6 text-3xl font-bold tracking-tight">{{ $concert->title }}</h1>
            <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-stone-500">Jadwal</dt><dd class="font-medium">{{ $concert->event_date->format('d M Y, H:i') }} WIB</dd></div>
                <div><dt class="text-stone-500">Lokasi</dt><dd class="font-medium">{{ $concert->venue }}, {{ $concert->city }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-stone-500">Pengisi acara</dt><dd class="font-medium">{{ $concert->lineup }}</dd></div>
            </dl>
            <p class="mt-6 whitespace-pre-line leading-relaxed text-stone-700">{{ $concert->description }}</p>
        </div>

        <aside class="h-fit rounded-lg border border-stone-200 bg-white p-5">
            <h2 class="text-lg font-semibold">Pilih tiket</h2>

            @if ($errors->any())
                <div class="mt-3 rounded bg-red-50 p-3 text-sm text-red-800">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            @guest
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach ($concert->ticketTypes as $t)
                        <li class="flex justify-between rounded border border-stone-200 px-3 py-2">
                            <span>{{ $t->name }} <span class="text-stone-500">(sisa {{ $t->quota }})</span></span>
                            <span class="font-medium">Rp {{ number_format($t->price, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('login') }}" class="mt-5 block rounded bg-teal-700 px-4 py-2 text-center text-sm font-medium text-white hover:bg-teal-800">Login untuk memesan</a>
                <p class="mt-2 text-center text-xs text-stone-500">Belum punya akun? <a href="{{ route('register') }}" class="text-teal-700 underline">Daftar</a></p>
            @else
                @if (! auth()->user()->hasRole('customer'))
                    <p class="mt-4 text-sm text-stone-600">Hanya akun customer yang dapat memesan tiket.</p>
                @elseif ($firstAvailable === 0)
                    <p class="mt-4 rounded bg-stone-100 p-3 text-sm">Semua tiket untuk konser ini sudah habis.</p>
                @else
                    <form method="POST" action="{{ route('checkout.store', $concert) }}"
                          x-data="{
                              types: @js($types),
                              typeId: {{ $firstAvailable }},
                              qty: 1,
                              get type() { return this.types.find(t => t.id == this.typeId); },
                              get total() { return this.type ? this.type.price * this.qty : 0; },
                              rupiah(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }
                          }" class="mt-4 space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <template x-for="t in types" :key="t.id">
                                <label class="flex cursor-pointer items-center justify-between rounded border px-3 py-2 text-sm"
                                       :class="t.quota === 0 ? 'cursor-not-allowed opacity-50' : (typeId == t.id ? 'border-teal-600 bg-teal-50' : 'border-stone-200')">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" name="ticket_type_id" :value="t.id" x-model="typeId" :disabled="t.quota === 0" @change="qty = 1" class="text-teal-700 focus:ring-teal-600">
                                        <span><span x-text="t.name"></span>
                                            <span class="text-stone-500" x-text="t.quota === 0 ? '(habis)' : '(sisa ' + t.quota + ')'"></span></span>
                                    </span>
                                    <span class="font-medium" x-text="rupiah(t.price)"></span>
                                </label>
                            </template>
                        </div>

                        <div>
                            <label class="text-sm text-stone-600">Jumlah tiket (maks. <span x-text="type.max"></span>)</label>
                            <select x-model.number="qty" class="mt-1 w-full rounded border-stone-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                                <template x-for="n in type.max" :key="n"><option :value="n" x-text="n"></option></template>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <p class="text-sm text-stone-600">Nama pemegang tiket</p>
                            <template x-for="i in qty" :key="i">
                                <input type="text" name="attendees[]" required maxlength="100" :placeholder="'Nama pemegang tiket ' + i"
                                       class="w-full rounded border-stone-300 text-sm focus:border-teal-600 focus:ring-teal-600">
                            </template>
                        </div>

                        <div class="flex justify-between border-t border-stone-200 pt-3 text-sm">
                            <span>Total</span><span class="font-semibold" x-text="rupiah(total)"></span>
                        </div>
                        <button class="w-full rounded bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Pesan sekarang</button>
                        <p class="text-xs text-stone-500">Pesanan harus dibayar dalam 24 jam, jika tidak akan dibatalkan otomatis.</p>
                    </form>
                @endif
            @endguest
        </aside>
    </div>
</x-eventra-layout>

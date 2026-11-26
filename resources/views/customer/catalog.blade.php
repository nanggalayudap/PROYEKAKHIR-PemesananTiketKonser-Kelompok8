<x-eventra-layout title="Daftar Konser">
    <h1 class="text-3xl font-bold tracking-tight">Daftar Konser</h1>

    @if ($errors->any())
        <div class="mt-4 rounded bg-red-50 p-3 text-sm text-red-800">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('concerts.index') }}" class="mt-6 flex flex-col gap-2 sm:flex-row">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul konser atau nama artis"
               class="w-full rounded border-stone-300 text-sm focus:border-teal-600 focus:ring-teal-600">
        <button type="submit" class="rounded bg-teal-700 px-5 py-2 text-sm font-medium text-white hover:bg-teal-800">Cari</button>
        <select name="city" onchange="this.form.submit()"
                class="rounded border-stone-300 text-sm focus:border-teal-600 focus:ring-teal-600 sm:w-48">
            <option value="">Semua kota</option>
            @foreach ($cities as $city)
                <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
            @endforeach
        </select>
    </form>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($concerts as $concert)
            @include('customer.partials.concert-card', ['concert' => $concert])
        @empty
            <p class="col-span-full rounded border border-dashed border-stone-300 p-8 text-center text-stone-600">Belum ada konser yang cocok dengan pencarianmu.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $concerts->links() }}</div>
</x-eventra-layout>
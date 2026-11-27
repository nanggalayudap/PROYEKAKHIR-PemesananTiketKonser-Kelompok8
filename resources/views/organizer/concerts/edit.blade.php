<x-organizer-layout :title="'Edit: ' . $concert->title">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('organizer.concerts.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Kembali</a>
        <span class="text-stone-300">/</span>
        <span class="text-sm text-stone-500">Edit Konser</span>
    </div>

    <div class="mt-6 max-w-xl">
        <h1 class="text-2xl font-bold tracking-tight">Edit Konser: {{ $concert->title }}</h1>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organizer.concerts.update', $concert) }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Judul Konser</label>
                <input type="text" name="title" value="{{ old('title', $concert->title) }}" required
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Genre Musik <span class="text-stone-400">(opsional)</span></label>
                <input type="text" name="genre" value="{{ old('genre', $concert->genre) }}" placeholder="Contoh: Pop, Indie, Rock, Jazz"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Ganti Poster / Banner <span class="text-stone-400">(kosongkan jika tetap)</span></label>
                @if ($concert->banner)
                    <img src="{{ asset('storage/' . $concert->banner) }}" alt="Banner saat ini" class="mb-2 h-32 rounded-lg object-cover">
                @endif
                <input type="file" name="banner" accept="image/*"
                       class="w-full text-sm text-stone-600 file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-teal-700 hover:file:bg-teal-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Line-Up Artis</label>
                <input type="text" name="lineup" value="{{ old('lineup', $concert->lineup) }}" required
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $concert->city) }}" required
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Lokasi Venue</label>
                    <input type="text" name="venue" value="{{ old('venue', $concert->venue) }}" required
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Tanggal & Jam Acara</label>
                <input type="datetime-local" name="event_date"
                       value="{{ old('event_date', \Carbon\Carbon::parse($concert->event_date)->format('Y-m-d\TH:i')) }}" required
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Deskripsi Acara</label>
                <textarea name="description" rows="5" required
                          class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">{{ old('description', $concert->description) }}</textarea>
            </div>
            <button type="submit" class="w-full rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Simpan Perubahan</button>
        </form>
    </div>
</x-organizer-layout>

<x-organizer-layout title="Buat Konser Baru">
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('organizer.concerts.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Kembali</a>
        <span class="text-stone-300">/</span>
        <span class="text-sm text-stone-500">Buat Konser Baru</span>
    </div>

    <div class="mt-6 max-w-xl">
        <h1 class="text-2xl font-bold tracking-tight">Buat Konser Baru</h1>
        <p class="mt-1 text-sm text-stone-500">Konser akan dibuat dengan status <strong>draft</strong> dan menunggu moderasi admin sebelum tayang.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organizer.concerts.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Judul Konser</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Genre Musik <span class="text-stone-400">(opsional)</span></label>
                <input type="text" name="genre" value="{{ old('genre') }}" placeholder="Contoh: Pop, Indie, Rock, Jazz"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Poster / Banner <span class="text-stone-400">(jpg, png, webp, maks 2 MB)</span></label>
                <input type="file" name="banner" accept="image/*"
                       class="w-full text-sm text-stone-600 file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-teal-700 hover:file:bg-teal-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Line-Up Artis</label>
                <input type="text" name="lineup" value="{{ old('lineup') }}" placeholder="Contoh: Hindia, .Feast, NIKI" required
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" required
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">Lokasi Venue</label>
                    <input type="text" name="venue" value="{{ old('venue') }}" required
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Tanggal & Jam Acara</label>
                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700 mb-1">Deskripsi Acara</label>
                <textarea name="description" rows="5" required
                          class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="w-full rounded-lg bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">
                Simpan & Lanjut Atur Kategori Tiket
            </button>
        </form>
    </div>
</x-organizer-layout>

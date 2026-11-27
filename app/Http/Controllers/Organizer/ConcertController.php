<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConcertController extends Controller
{
    public function index(): View
    {
        $daftarKonser = Concert::with('ticketTypes')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('organizer.concerts.index', compact('daftarKonser'));
    }

    public function create(): View
    {
        return view('organizer.concerts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:100'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'lineup' => ['required', 'string'],
            'description' => ['required', 'string'],
            'venue' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'event_date' => ['required', 'date', 'after:now'],
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        $concert = Concert::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::lower(Str::random(5)),
            'genre' => $validated['genre'] ?? null,
            'banner' => $bannerPath,
            'lineup' => $validated['lineup'],
            'description' => $validated['description'],
            'venue' => $validated['venue'],
            'city' => $validated['city'],
            'event_date' => $validated['event_date'],
            'status' => 'draft',
        ]);

        return redirect()->route('organizer.ticket-types.index', $concert)
            ->with('success', 'Konser berhasil dibuat dengan status draft. Silakan tambahkan kategori tiket.');
    }

    public function show(Concert $concert): View
    {
        abort_if($concert->user_id !== Auth::id(), 403);
        $concert->load('ticketTypes');

        return view('organizer.concerts.show', compact('concert'));
    }

    public function edit(Concert $concert): View
    {
        abort_if($concert->user_id !== Auth::id(), 403);

        return view('organizer.concerts.edit', compact('concert'));
    }

    public function update(Request $request, Concert $concert): RedirectResponse
    {
        abort_if($concert->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:100'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'lineup' => ['required', 'string'],
            'description' => ['required', 'string'],
            'venue' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'event_date' => ['required', 'date'],
        ]);

        $dataUpdate = [
            'title' => $validated['title'],
            'genre' => $validated['genre'] ?? null,
            'lineup' => $validated['lineup'],
            'description' => $validated['description'],
            'venue' => $validated['venue'],
            'city' => $validated['city'],
            'event_date' => $validated['event_date'],
        ];

        if ($request->hasFile('banner')) {
            if ($concert->banner) {
                Storage::disk('public')->delete($concert->banner);
            }
            $dataUpdate['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $concert->update($dataUpdate);

        return redirect()->route('organizer.concerts.index')
            ->with('success', 'Data konser berhasil diperbarui.');
    }

    public function destroy(Concert $concert): RedirectResponse
    {
        abort_if($concert->user_id !== Auth::id(), 403);

        if ($concert->orders()->exists()) {
            return redirect()->route('organizer.concerts.index')
                ->with('error', 'Konser tidak dapat dihapus karena sudah memiliki transaksi tiket.');
        }

        if ($concert->banner) {
            Storage::disk('public')->delete($concert->banner);
        }

        $concert->delete();

        return redirect()->route('organizer.concerts.index')
            ->with('success', 'Konser berhasil dihapus.');
    }
}
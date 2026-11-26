<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConcertController extends Controller
{
    public function index(Request $request): View
    {
        $daftarKonser = Concert::with(['user', 'ticketTypes'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.concerts.index', compact('daftarKonser'));
    }

    public function show(Concert $concert): View
    {
        $concert->load(['user', 'ticketTypes']);

        return view('admin.concerts.show', compact('concert'));
    }

    public function updateStatus(Request $request, Concert $concert): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,published,closed'],
        ]);

        $concert->update([
            'status' => $validated['status'],
        ]);

        $pesanStatus = match ($validated['status']) {
            'published' => 'Konser disetujui dan resmi tayang di katalog.',
            'closed' => 'Konser telah ditutup.',
            default => 'Status konser dikembalikan ke draft.',
        };

        return redirect()->back()->with('success', $pesanStatus);
    }
}
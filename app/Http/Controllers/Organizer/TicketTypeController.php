<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketTypeController extends Controller
{
    public function index(Concert $concert): View
    {
        abort_if($concert->user_id !== Auth::id(), 403);
        $concert->load('ticketTypes');

        return view('organizer.ticket-types.index', compact('concert'));
    }

    public function store(Request $request, Concert $concert): RedirectResponse
    {
        abort_if($concert->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'max_per_order' => ['required', 'integer', 'min:1', 'lte:quota'],
        ]);

        $concert->ticketTypes()->create($validated);

        return redirect()->route('organizer.ticket-types.index', $concert)
            ->with('success', 'Kategori tiket berhasil ditambahkan.');
    }

    public function update(Request $request, Concert $concert, TicketType $ticketType): RedirectResponse
    {
        abort_if($concert->user_id !== Auth::id() || $ticketType->concert_id !== $concert->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:0'],
            'max_per_order' => ['required', 'integer', 'min:1'],
        ]);

        $ticketType->update($validated);

        return redirect()->route('organizer.ticket-types.index', $concert)
            ->with('success', 'Kategori tiket berhasil diperbarui.');
    }

    public function destroy(Concert $concert, TicketType $ticketType): RedirectResponse
    {
        abort_if($concert->user_id !== Auth::id() || $ticketType->concert_id !== $concert->id, 403);

        if ($ticketType->tickets()->exists()) {
            return redirect()->route('organizer.ticket-types.index', $concert)
                ->with('error', 'Kategori tiket tidak dapat dihapus karena sudah ada tiket yang dipesan.');
        }

        $ticketType->delete();

        return redirect()->route('organizer.ticket-types.index', $concert)
            ->with('success', 'Kategori tiket berhasil dihapus.');
    }
}
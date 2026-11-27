<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckInController extends Controller
{
    public function index(): View
    {
        $riwayatCheckIn = Ticket::with(['order.concert', 'ticketType'])
            ->where('is_checked_in', true)
            ->whereHas('order.concert', fn ($query) => $query->where('user_id', Auth::id()))
            ->latest('checked_in_at')
            ->take(15)
            ->get();

        return view('organizer.checkin.index', compact('riwayatCheckIn'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_code' => ['required', 'string'],
        ]);

        $kodeTiket = trim($validated['ticket_code']);

        $ticket = Ticket::with(['order.concert', 'ticketType'])
            ->where('ticket_code', $kodeTiket)
            ->first();

        if (!$ticket) {
            return redirect()->back()
                ->with('error', "Kode tiket '{$kodeTiket}' tidak ditemukan.");
        }

        if ($ticket->order->concert->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Tiket ini bukan milik acara yang Anda selenggarakan.');
        }

        if ($ticket->order->status !== 'paid') {
            return redirect()->back()
                ->with('error', "Tiket ditolak: status pembayaran masih '{$ticket->order->status}'.");
        }

        if ($ticket->is_checked_in) {
            return redirect()->back()
                ->with('error', "GAGAL (Duplikat): Tiket {$ticket->ticket_code} atas nama {$ticket->attendee_name} sudah check-in pada {$ticket->checked_in_at}.");
        }

        $ticket->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "CHECK-IN BERHASIL: {$ticket->attendee_name} ({$ticket->ticketType->name} - {$ticket->order->concert->title}).");
    }
}
<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $daftarPesanan = Order::with(['user', 'concert'])
            ->whereHas('concert', fn ($query) => $query->where('user_id', Auth::id()))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('organizer.orders.index', compact('daftarPesanan'));
    }

    public function show(Order $order): View
    {
        abort_if($order->concert->user_id !== Auth::id(), 403);
        $order->load(['user', 'concert', 'tickets.ticketType']);

        return view('organizer.orders.show', compact('order'));
    }

    public function verify(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->concert->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        if ($order->status !== 'waiting_verification') {
            return redirect()->back()
                ->with('error', 'Pesanan ini tidak dalam status menunggu verifikasi.');
        }

        if ($validated['action'] === 'approve') {
            $order->update(['status' => 'paid']);

            return redirect()->route('organizer.orders.show', $order)
                ->with('success', 'Pembayaran dikonfirmasi (paid). E-Ticket pembeli kini aktif.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'rejected']);

            $jumlahPerKategori = $order->tickets()
                ->selectRaw('ticket_type_id, COUNT(*) as total')
                ->groupBy('ticket_type_id')
                ->pluck('total', 'ticket_type_id');

            foreach ($jumlahPerKategori as $ticketTypeId => $jumlah) {
                TicketType::where('id', $ticketTypeId)
                    ->lockForUpdate()
                    ->increment('quota', $jumlah);
            }
        });

        return redirect()->route('organizer.orders.show', $order)
            ->with('success', 'Pembayaran ditolak (rejected) dan kuota tiket telah dikembalikan.');
    }
}
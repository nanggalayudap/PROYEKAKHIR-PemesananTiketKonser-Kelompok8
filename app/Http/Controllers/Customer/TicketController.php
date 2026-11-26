<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class TicketController extends Controller
{
    /** Tiket Saya: semua tiket dari pesanan yang sudah lunas. */
    public function index()
    {
        $tickets = Ticket::with(['ticketType', 'order.concert'])
            ->whereHas('order', fn ($q) => $q->where('user_id', auth()->id())->where('status', 'paid'))
            ->latest()
            ->paginate(12);

        return view('customer.tickets', compact('tickets'));
    }
}

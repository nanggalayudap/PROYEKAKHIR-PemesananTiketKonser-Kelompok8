<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'tickets' => Ticket::whereHas('order', fn ($q) => $q->where('user_id', $user->id)->where('status', 'paid'))->count(),
            'pending' => $user->orders()->where('status', 'pending')->where('expires_at', '>', now())->count(),
            'waiting' => $user->orders()->where('status', 'waiting_verification')->count(),
        ];

        $recentOrders = $user->orders()->with('concert')->withCount('tickets')->latest()->take(3)->get();

        $upcoming = Concert::where('status', 'published')->where('event_date', '>=', now());

        $recommended = (clone $upcoming)
            ->with('ticketTypes')
            ->withCount(['orders as sold_orders' => fn ($q) => $q->where('status', 'paid')])
            ->orderByDesc('sold_orders')->orderBy('event_date')
            ->take(3)->get();

        $latest = (clone $upcoming)->with('ticketTypes')->latest()->take(3)->get();

        return view('customer.dashboard', compact('stats', 'recentOrders', 'recommended', 'latest'));
    }
}

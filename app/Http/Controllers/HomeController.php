<?php

namespace App\Http\Controllers;

use App\Models\Concert;

class HomeController extends Controller
{
    public function index()
    {
        $upcoming = Concert::where('status', 'published')->where('event_date', '>=', now());

        // Konser unggulan = konser terdekat yang akan tayang
        $featured = (clone $upcoming)->with('ticketTypes')->orderBy('event_date')->first();

        // Rekomendasi = konser dengan pesanan lunas (paid) terbanyak
        $recommended = (clone $upcoming)
            ->with('ticketTypes')
            ->withCount(['orders as sold_orders' => fn ($q) => $q->where('status', 'paid')])
            ->orderByDesc('sold_orders')
            ->orderBy('event_date')
            ->take(4)
            ->get();

        $upcomingList = (clone $upcoming)
            ->with('ticketTypes')
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->orderBy('event_date')
            ->take(6)
            ->get();

        return view('customer.home', compact('featured', 'recommended', 'upcomingList'));
    }
}

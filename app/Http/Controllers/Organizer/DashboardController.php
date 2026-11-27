<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $organizerId = Auth::id();

        $eventAktif = Concert::where('user_id', $organizerId)
            ->where('status', 'published')
            ->count();

        $tiketTerjual = Ticket::whereHas('order', function ($query) use ($organizerId) {
            $query->where('status', 'paid')
                ->whereHas('concert', fn ($q) => $q->where('user_id', $organizerId));
        })->count();

        $totalPendapatan = Order::where('status', 'paid')
            ->whereHas('concert', fn ($query) => $query->where('user_id', $organizerId))
            ->sum('total_price');

        $menungguVerifikasi = Order::where('status', 'waiting_verification')
            ->whereHas('concert', fn ($query) => $query->where('user_id', $organizerId))
            ->count();

        $daftarKonser = Concert::with('ticketTypes')
            ->where('user_id', $organizerId)
            ->latest()
            ->get();

        return view('organizer.dashboard', compact(
            'eventAktif',
            'tiketTerjual',
            'totalPendapatan',
            'menungguVerifikasi',
            'daftarKonser'
        ));
    }
}
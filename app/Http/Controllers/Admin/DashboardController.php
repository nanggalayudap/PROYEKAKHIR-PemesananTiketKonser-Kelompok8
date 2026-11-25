<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalPengguna = User::count();
        $konserAktif = Concert::where('status', 'published')->count();
        $tiketTerjual = Ticket::whereHas('order', function ($query) {
            $query->where('status', 'paid');
        })->count();
        $perputaranDana = Order::where('status', 'paid')->sum('total_price');

        $konserMenungguModerasi = Concert::with('user')
            ->where('status', 'draft')
            ->latest()
            ->take(5)
            ->get();

        $transaksiTerbaru = Order::with(['user', 'concert'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPengguna',
            'konserAktif',
            'tiketTerjual',
            'perputaranDana',
            'konserMenungguModerasi',
            'transaksiTerbaru'
        ));
    }
}
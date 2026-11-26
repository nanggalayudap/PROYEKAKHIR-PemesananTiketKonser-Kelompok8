<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $daftarPesanan = Order::with(['user', 'concert'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('order_code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('daftarPesanan'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'concert', 'tickets.ticketType']);

        return view('admin.orders.show', compact('order'));
    }
}
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('concert')->withCount('tickets')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->ownOrFail($order);
        $order->load(['concert', 'tickets.ticketType']);

        return view('customer.order-show', compact('order'));
    }

    public function payment(Order $order)
    {
        $this->ownOrFail($order);

        if ($order->effectiveStatus() !== 'pending') {
            return redirect()->route('orders.show', $order);
        }

        $order->load(['concert.organizer', 'tickets.ticketType']);

        return view('customer.payment', compact('order'));
    }

    public function uploadProof(Request $request, Order $order)
    {
        $this->ownOrFail($order);

        if ($order->effectiveStatus() !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan ini tidak lagi menunggu pembayaran.');
        }

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'payment_proof.required' => 'Pilih foto bukti pembayaran terlebih dahulu.',
            'payment_proof.image'    => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'status'        => 'waiting_verification',
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Bukti pembayaran terkirim. Menunggu verifikasi penyelenggara.');
    }

    public function eticket(Order $order)
    {
        $this->ownOrFail($order);
        abort_unless($order->status === 'paid', 403, 'E-ticket baru tersedia setelah pembayaran disetujui.');

        $order->load(['concert', 'tickets.ticketType']);

        return view('customer.eticket', compact('order'));
    }

    private function ownOrFail(Order $order): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
    }
}

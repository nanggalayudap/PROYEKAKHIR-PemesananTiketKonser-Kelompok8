<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function store(Request $request, Concert $concert)
    {
        abort_unless($concert->status === 'published' && $concert->event_date->isFuture(), 404);

        $data = $request->validate([
            'ticket_type_id' => ['required', 'integer'],
            'attendees'      => ['required', 'array', 'min:1'],
            'attendees.*'    => ['required', 'string', 'max:100'],
        ], [
            'attendees.*.required' => 'Nama pemegang tiket wajib diisi.',
        ]);

        $names = array_map('trim', $data['attendees']);
        $qty = count($names);

        $order = DB::transaction(function () use ($data, $concert, $names, $qty) {
            // lockForUpdate: kunci baris kategori supaya dua pembeli tidak mengambil kuota yang sama
            $type = TicketType::where('id', $data['ticket_type_id'])
                ->where('concert_id', $concert->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($qty > $type->max_per_order) {
                throw ValidationException::withMessages([
                    'attendees' => "Maksimal {$type->max_per_order} tiket per pesanan.",
                ]);
            }

            if ($qty > $type->quota) {
                throw ValidationException::withMessages([
                    'attendees' => 'Kuota tiket tidak mencukupi.',
                ]);
            }

            $type->decrement('quota', $qty);

            do {
                $orderCode = 'ORD-' . strtoupper(Str::random(8));
            } while (Order::where('order_code', $orderCode)->exists());

            $order = Order::create([
                'user_id'     => auth()->id(),
                'concert_id'  => $concert->id,
                'order_code'  => $orderCode,
                'total_price' => $type->price * $qty,
                'status'      => 'pending',
                'expires_at'  => now()->addHours(24),
            ]);

            foreach ($names as $name) {
                do {
                    $code = 'TIX-' . now()->year . '-' . strtoupper(Str::random(8));
                } while (Ticket::where('ticket_code', $code)->exists());

                Ticket::create([
                    'order_id'       => $order->id,
                    'ticket_type_id' => $type->id,
                    'ticket_code'    => $code,
                    'attendee_name'  => $name,
                ]);
            }

            return $order;
        });

        return redirect()->route('orders.payment', $order);
    }
}
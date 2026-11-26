<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire';

    protected $description = 'Batalkan pesanan pending yang melewati batas 24 jam dan kembalikan kuota tiket';

    public function handle(): int
    {
        $ids = Order::where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->pluck('id');

        foreach ($ids as $id) {
            DB::transaction(function () use ($id) {
                $order = Order::with('tickets')->lockForUpdate()->find($id);

                if (! $order || $order->status !== 'pending') {
                    return;
                }

                $order->tickets->groupBy('ticket_type_id')->each(
                    fn ($tickets, $typeId) => TicketType::whereKey($typeId)->increment('quota', $tickets->count())
                );

                $order->update(['status' => 'expired']);
            });
        }

        $this->info("{$ids->count()} pesanan kedaluwarsa diproses.");

        return self::SUCCESS;
    }
}

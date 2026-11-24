<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'order_id',
        'ticket_type_id',
        'ticket_code',
        'attendee_name',
        'is_checked_in',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'is_checked_in' => 'boolean',
            'checked_in_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }
}
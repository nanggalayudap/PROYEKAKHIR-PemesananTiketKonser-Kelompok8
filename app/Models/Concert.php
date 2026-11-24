<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concert extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'banner',
        'lineup',
        'description',
        'venue',
        'city',
        'event_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
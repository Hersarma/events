<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventGuest extends Model
{
    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'full_name',
        'phone',
        'phone_normalized',
        'max_guests',
        'note',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function rsvp(): HasOne
    {
        return $this->hasOne(EventRsvp::class, 'event_guest_id');
    }
}
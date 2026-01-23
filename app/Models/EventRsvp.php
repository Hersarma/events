<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRsvp extends Model
{
    protected $table = 'event_rsvps';

    protected $fillable = [
        'event_id',
        'status',
        'name',
        'email',
        'phone',
        'guests_count',
        'note',
        'ip_address',
        'user_agent',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

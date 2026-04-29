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
        'event_guest_id',
        'responded_at',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
    public function invitedGuest()
{
    return $this->belongsTo(EventGuest::class, 'event_guest_id');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class EventGuest extends Model
{
    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'full_name',
        'phone',
        'phone_normalized',
        'qr_token',
        'max_guests',
        'note',
        'checked_in_at',
        'checked_in_count',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (EventGuest $guest) {
            $guest->qr_token ??= static::makeQrToken();
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function rsvp(): HasOne
    {
        return $this->hasOne(EventRsvp::class, 'event_guest_id');
    }

    public function ensureQrToken(): string
    {
        if (!$this->qr_token) {
            $this->forceFill(['qr_token' => static::makeQrToken()])->save();
        }

        return $this->qr_token;
    }

    public function canReceiveQrCode(): bool
    {
        $status = $this->rsvp?->status;

        return in_array($status, ['yes', 'couple'], true);
    }

    private static function makeQrToken(): string
    {
        do {
            $token = Str::lower(Str::random(40));
        } while (static::where('qr_token', $token)->exists());

        return $token;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title','slug','date_at',
        'location_name','location_url',
        'video_path',
        'primary_color','secondary_color',
        'rsvp_email',
        'token','is_active','expires_at',
    ];

    protected $casts = [
        'date_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function getInviteUrlAttribute(): string
    {
        return route('invite.show', ['slug' => $this->slug, 'token' => $this->token]);
    }

    public static function makeSlug(string $title): string
    {
        return Str::slug($title);
    }

    public static function makeToken(): string
    {
        return Str::random(24); // 24 je dovoljno, a nije predugačko
    }
}

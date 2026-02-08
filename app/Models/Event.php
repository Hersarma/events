<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'template',
        'language',

        'title',
        'slug',
        'token',

        'is_active',
        'expires_at',

        'date_at',
        'location_name',
        'location_address',
        'location_url',
        'rsvp_email',

        'hero_type',
        'hero_video_path',
        'hero_image_path',
        'map_image_path',

        'content',
        'style',
        'location_marker_path',
        'location_image_path',


    ];

    protected $casts = [
        'date_at' => 'datetime',
        'expires_at' => 'datetime',
        'content' => 'array',
        'style' => 'array',
        'is_active' => 'boolean',
    ];

    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function getInviteUrlAttribute(): string
{
    return route('invite.show', ['token' => $this->token]);
}

    public static function makeSlug(string $title): string
    {
        return Str::slug($title) ?: 'event';
    }

    public static function makeToken(): string
    {
        return Str::lower(Str::random(6) . '-' . Str::random(6));
    }
}

<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Form extends Component
{
    use WithFileUploads;

    public ?Event $event = null;

    public string $title = '';
    public ?string $date_at = null; // "Y-m-d\TH:i" from input
    public string $location_name = '';
    public string $location_url = '';
    public string $rsvp_email = '';

    public string $primary_color = '#111827';
    public string $secondary_color = '#6B7280';

    public bool $is_active = true;
    public ?string $expires_at = null;

    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null */
    public $video = null;

    public ?string $video_path = null;
    public string $token = '';
    public string $slug = '';

    public function mount(?Event $event = null): void
    {
        $this->event = $event;

        if ($event) {
            $this->title = $event->title;
            $this->slug = $event->slug;
            $this->token = $event->token;

            $this->date_at = $event->date_at?->format('Y-m-d\TH:i');
            $this->location_name = $event->location_name ?? '';
            $this->location_url  = $event->location_url ?? '';
            $this->rsvp_email    = $event->rsvp_email ?? '';

            $this->primary_color = $event->primary_color;
            $this->secondary_color = $event->secondary_color;

            $this->is_active = (bool) $event->is_active;
            $this->expires_at = $event->expires_at?->format('Y-m-d\TH:i');

            $this->video_path = $event->video_path;
        } else {
            // default token (biće sačuvan u bazi na save)
            $this->token = Str::random(24);
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->event) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'min:3', 'max:120'],
            'date_at' => ['nullable', 'date'],
            'location_name' => ['nullable', 'string', 'max:120'],
            'location_url' => ['nullable', 'string', 'max:255'],
            'rsvp_email' => ['nullable', 'email', 'max:190'],

            'primary_color' => ['required', 'string', 'max:20'],
            'secondary_color' => ['required', 'string', 'max:20'],

            'is_active' => ['boolean'],
            'expires_at' => ['nullable', 'date'],

            'video' => ['nullable', 'file', 'mimetypes:video/mp4', 'max:51200'], // 50MB
        ]);

        $slug = $this->event?->slug ?: Str::slug($this->title);
        $token = $this->event?->token ?: ($this->token ?: Str::random(24));

        // osiguraj da slug nije prazan
        if (!$slug) {
            $slug = 'event';
        }

        // upload video ako je izabran
        if ($this->video) {
            // obriši stari video (ako postoji)
            if ($this->event?->video_path) {
                Storage::disk('public')->delete($this->event->video_path);
            }

            $path = $this->video->store('invites', 'public');
            $this->video_path = $path;
        }

        $payload = [
            'title' => $this->title,
            'slug' => $slug,
            'token' => $token,

            'date_at' => $this->date_at ? now()->parse($this->date_at) : null,
            'location_name' => $this->location_name ?: null,
            'location_url' => $this->location_url ?: null,
            'rsvp_email' => $this->rsvp_email ?: null,

            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,

            'is_active' => (bool) $this->is_active,
            'expires_at' => $this->expires_at ? now()->parse($this->expires_at) : null,

            'video_path' => $this->video_path,
        ];

        $event = $this->event
            ? tap($this->event)->update($payload)
            : Event::create($payload);

        $this->event = $event;
        $this->slug = $event->slug;
        $this->token = $event->token;

        session()->flash('status', 'Event sačuvan.');
        $this->redirectRoute('events.edit', $event, navigate: true);
    }

    public function getInviteUrlProperty(): ?string
    {
        if (!$this->event) return null;
        return $this->event->invite_url;
    }

    public function render()
    {
        return view('livewire.events.form');
    }
}

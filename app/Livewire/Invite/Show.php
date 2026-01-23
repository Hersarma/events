<?php

namespace App\Livewire\Invite;

use App\Mail\RsvpReceived;
use App\Models\Event;
use App\Models\EventRsvp;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Show extends Component
{
    public Event $event;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public int $guests_count = 1;
    public string $status = 'yes'; // yes|maybe|no

    public bool $sent = false;

    public function mount(string $slug, string $token): void
    {
        $event = Event::query()
            ->where('slug', $slug)
            ->where('token', $token)
            ->firstOrFail();

        abort_unless($event->is_active, 404);

        if ($event->expires_at && now()->greaterThan($event->expires_at)) {
            abort(404);
        }

        $this->event = $event;
    }

    public function submit(): void
    {
        $this->validate([
            'status' => ['required', 'in:yes,maybe,no'],
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['nullable', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $rsvp = EventRsvp::create([
            'event_id' => $this->event->id,
            'status' => $this->status,
            'name' => trim($this->name),
            'email' => trim($this->email) ?: null,
            'phone' => trim($this->phone) ?: null,
            'guests_count' => (int) $this->guests_count,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if ($this->event->rsvp_email) {
            Mail::to($this->event->rsvp_email)->send(new RsvpReceived($rsvp));
        }

        $this->sent = true;

        $this->reset(['name', 'email', 'phone', 'guests_count']);
        $this->guests_count = 1;
    }

    public function render()
    {
        return view('livewire.invite.show');
    }
}

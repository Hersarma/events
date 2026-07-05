<?php

namespace App\Livewire\PublicGuests;

use App\Models\Event;
use App\Models\EventGuest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class CheckIn extends Component
{
    public Event $event;
    public EventGuest $guest;
    public int $checked_in_count = 1;

    public function mount(string $token, string $guestToken): void
    {
        $this->event = Event::where('token', $token)->firstOrFail();

        abort_unless(($this->event->enable_guest_list ?? true) && ($this->event->enable_qr_codes ?? false), 404);

        if (!session()->get('guest_list_access.' . $this->event->id)) {
            session()->put('url.intended', url()->current());
            redirect()->route('public.guests.pin', $token)->send();
        }

        $this->guest = EventGuest::where('event_id', $this->event->id)
            ->where('qr_token', $guestToken)
            ->with('rsvp')
            ->firstOrFail();

        abort_unless($this->guest->canReceiveQrCode(), 404);

        $this->checked_in_count = $this->guest->checked_in_count
            ?: ($this->guest->rsvp?->guests_count ?: 1);
    }

    public function confirm(): void
    {
        $max = max(1, (int) ($this->guest->rsvp?->guests_count ?: 1));

        $this->validate([
            'checked_in_count' => ['required', 'integer', 'min:1', 'max:' . $max],
        ]);

        $this->guest->update([
            'checked_in_at' => now(),
            'checked_in_count' => $this->checked_in_count,
        ]);

        $this->guest->refresh()->load('rsvp');

        $this->dispatch('flash', message: 'Dolazak je potvrđen.', type: 'success');
    }

    public function resetCheckIn(): void
    {
        $this->guest->update([
            'checked_in_at' => null,
            'checked_in_count' => null,
        ]);

        $this->guest->refresh()->load('rsvp');
        $this->checked_in_count = $this->guest->rsvp?->guests_count ?: 1;

        $this->dispatch('flash', message: 'Check-in je poništen.', type: 'success');
    }

    public function render()
    {
        return view('livewire.public-guests.check-in');
    }
}

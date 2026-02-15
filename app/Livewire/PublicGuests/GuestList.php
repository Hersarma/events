<?php

namespace App\Livewire\PublicGuests;

use App\Models\Event;
use App\Models\EventRsvp;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class GuestList extends Component
{
    public string $token;
    public string $filter = 'all'; // all | coming | not_coming
    public string $q = '';         // ✅ search

    public function mount(string $token): void
    {
        $this->token = $token;

        $event = Event::where('token', $token)->firstOrFail();

        if (!session()->get('guest_list_access.' . $event->id)) {
            redirect()->route('public.guests.pin', $token)->send();
        }
    }

    public function render()
    {
        $event = Event::where('token', $this->token)->firstOrFail();

        $query = EventRsvp::where('event_id', $event->id);

        // ✅ filter
        if ($this->filter === 'coming') {
            $query->whereIn('status', ['yes', 'couple']);
        } elseif ($this->filter === 'not_coming') {
            $query->where('status', 'no');
        }

        // ✅ search (ime / telefon / email)
        $term = trim($this->q);
        if ($term !== '') {
            $query->where(function ($qq) use ($term) {
                $qq->where('name', 'like', "%{$term}%")
                   ->orWhere('phone', 'like', "%{$term}%")
                   ->orWhere('email', 'like', "%{$term}%");
            });
        }

        // ✅ sortiraj praktično za vrata:
        // kad su "dolaze" ili "svi" -> abeceda, kad su "ne dolaze" može isto abeceda
        $rsvps = $query->orderBy('name')->get();

        $comingCount = EventRsvp::where('event_id', $event->id)
            ->whereIn('status', ['yes', 'couple'])
            ->sum('guests_count');

        return view('livewire.public-guests.guest-list', [
            'event' => $event,
            'rsvps' => $rsvps,
            'comingCount' => $comingCount,
        ]);
    }
}

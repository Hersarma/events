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
    public string $q = ''; // search

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

        // ✅ UVEK samo oni koji dolaze
        $query = EventRsvp::where('event_id', $event->id)
            ->whereIn('status', ['yes', 'couple']);

        // search (ime / telefon / email)
        $term = trim($this->q);
        if ($term !== '') {
            $query->where(function ($qq) use ($term) {
                $qq->where('name', 'like', "%{$term}%")
                   ->orWhere('phone', 'like', "%{$term}%")
                   ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $rsvps = $query->orderBy('name')->get();

        // statistika (koliko ukupno dolazi - po guests_count)
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

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
    public string $filter = 'all'; // default

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

    // filter
    if ($this->filter === 'coming') {
        $query->whereIn('status', ['yes', 'couple']);
    } elseif ($this->filter === 'not_coming') {
        $query->where('status', 'no');
    }

    $rsvps = $query->latest()->get();

    // ukupno dolaze
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

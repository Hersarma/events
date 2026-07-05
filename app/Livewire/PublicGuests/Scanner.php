<?php

namespace App\Livewire\PublicGuests;

use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Scanner extends Component
{
    public Event $event;

    public function mount(string $token): void
    {
        $this->event = Event::where('token', $token)->firstOrFail();

        abort_unless(($this->event->enable_guest_list ?? true) && ($this->event->enable_qr_codes ?? false), 404);

        if (!session()->get('guest_list_access.' . $this->event->id)) {
            session()->put('url.intended', url()->current());
            redirect()->route('public.guests.pin', $token)->send();
        }
    }

    public function render()
    {
        return view('livewire.public-guests.scanner');
    }
}

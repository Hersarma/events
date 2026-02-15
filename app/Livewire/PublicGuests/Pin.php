<?php

namespace App\Livewire\PublicGuests;

use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Pin extends Component
{
    public string $token;
    public string $pin = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    public function submit()
    {
        $event = Event::where('token', $this->token)->firstOrFail();

        if (!$event->guest_list_pin_hash) abort(403);

        $this->validate(['pin' => ['required', 'regex:/^\d{4}$/']]);

        $key = 'guest-pin:' . request()->ip() . ':' . $event->id;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->addError('pin', 'Previše pokušaja. Pokušaj kasnije.');
            return;
        }

        if (!Hash::check($this->pin, $event->guest_list_pin_hash)) {
            RateLimiter::hit($key, 30);
            $this->addError('pin', 'Pogrešan PIN.');
            return;
        }

        RateLimiter::clear($key);

        session()->put('guest_list_access.' . $event->id, true);

        return redirect()->route('public.guests.list', $event->token);
    }

    public function render()
    {
        return view('livewire.public-guests.pin');
    }
}


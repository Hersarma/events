<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.events.index', [
            'events' => Event::query()->latest()->get(),
        ]);
    }
}

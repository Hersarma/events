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
    public int $formKey = 1;

    public bool $sent = false;

  public function mount(string $token): void
{
    $event = Event::where('token', $token)->firstOrFail();

    abort_unless($event->is_active, 404);

    if ($event->expires_at && now()->greaterThan($event->expires_at)) {
        abort(404);
    }

    $this->event = $event;
}



    public function submit(): void
{
    $messages = [
        'name.required'   => data_get($this->event->content, 'rsvp_err_name_required', 'Molimo unesite ime i prezime.'),
        'phone.required'  => data_get($this->event->content, 'rsvp_err_phone_required', 'Molimo unesite broj telefona.'),
        'status.required' => data_get($this->event->content, 'rsvp_err_status_required', 'Molimo izaberite jednu opciju.'),
        'status.in'       => data_get($this->event->content, 'rsvp_err_status_in', 'Neispravan izbor.'),
        'email.email'     => data_get($this->event->content, 'rsvp_err_email_email', 'Email nije ispravan.'),
    ];

    $this->validate([
        'status' => ['required','in:yes,couple,no'],
        'name'   => ['required', 'string', 'min:2', 'max:80'],
        'email'  => ['nullable', 'email', 'max:120'],
        'phone'  => ['required', 'int', 'max:40'], // izbaci nullable
    ], $messages);

    $guests = match ($this->status) {
        'yes' => 1,
        'couple' => 2,
        'no' => 0,
    };

    $rsvp = EventRsvp::create([
        'event_id' => $this->event->id,
        'status' => $this->status,
        'name' => trim($this->name),
        'email' => trim($this->email) ?: null,
        'phone' => trim($this->phone) ?: null,
        'guests_count' => $guests,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    $this->dispatch('flash',
        message: 'Hvala! Potvrda je poslata.',
        type: 'success'
    );

    $this->reset(['name', 'email', 'phone']);
    $this->status = 'yes';
    $this->formKey++;
}


    public function render()
    {
        return view('livewire.invite.show');
    }
}

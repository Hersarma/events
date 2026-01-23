<?php

namespace App\Mail;

use App\Models\EventRsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RsvpReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EventRsvp $rsvp) {}

    public function build()
    {
        $event = $this->rsvp->event;

        return $this->subject('Nova potvrda dolaska: ' . $event->title)
            ->view('emails.rsvp-received', [
                'rsvp' => $this->rsvp,
                'event' => $event,
            ]);
    }
}

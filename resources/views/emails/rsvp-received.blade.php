<p><strong>Event:</strong> {{ $event->title }}</p>
<p><strong>Status:</strong> {{ strtoupper($rsvp->status) }}</p>
<p><strong>Ime:</strong> {{ $rsvp->name }}</p>
<p><strong>Email:</strong> {{ $rsvp->email ?? '—' }}</p>
<p><strong>Broj gostiju:</strong> {{ $rsvp->guests_count }}</p>
<p><strong>Vreme:</strong> {{ $rsvp->created_at->format('d.m.Y H:i') }}</p>

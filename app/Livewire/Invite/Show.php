<?php

namespace App\Livewire\Invite;

use App\Models\Event;
use App\Models\EventGuest;
use App\Models\EventRsvp;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Show extends Component
{
    public Event $event;

    public string $name = '';
    public string $email = '';

    // Kompletan broj koji se čuva u RSVP, npr. +385 91 1234567
    public string $phone = '';

    // Polja za prikaz u formi: + | pozivni | broj telefona
    public string $phone_country = '';
    public string $phone_number = '';

    public int $guests_count = 1;
    public string $status = 'yes'; // yes|couple|no
    public int $formKey = 1;

    public bool $sent = false;
    public ?string $successMessage = null;
    public string $qr_phone_country = '';
    public string $qr_phone_number = '';
    public ?string $qrDownloadUrl = null;
    public ?string $qrLookupMessage = null;

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
        abort_unless($this->event->enable_rsvp ?? true, 404);

        $this->successMessage = null;
        $messages = [
            'name.required' => data_get($this->event->content, 'rsvp_err_name_required', 'Molimo unesite ime i prezime.'),
            'phone_country.required' => data_get($this->event->content, 'rsvp_err_phone_country_required', 'Molimo unesite pozivni broj države.'),
            'phone_country.max' => data_get($this->event->content, 'rsvp_err_phone_country_max', 'Pozivni broj može imati najviše 3 znamenke.'),
            'phone_number.required' => data_get($this->event->content, 'rsvp_err_phone_required', 'Molimo unesite broj telefona.'),
            'status.required' => data_get($this->event->content, 'rsvp_err_status_required', 'Molimo izaberite jednu opciju.'),
            'status.in' => data_get($this->event->content, 'rsvp_err_status_in', 'Neispravan izbor.'),
            'email.email' => data_get($this->event->content, 'rsvp_err_email_email', 'Email nije ispravan.'),
        ];

        $this->validate([
            'status' => ['required', 'in:yes,couple,no'],
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['nullable', 'email', 'max:120'],
            'phone_country' => ['required', 'string', 'max:3'],
            'phone_number' => ['required', 'string', 'max:30'],
        ], $messages);

        $countryCode = preg_replace('/\D+/', '', $this->phone_country);
        $localNumber = preg_replace('/\D+/', '', $this->phone_number);

        // Ako gost unese 0 nakon pozivnog broja:
        // +385 091 123 4567 => +385 91 123 4567
        $localNumber = ltrim($localNumber, '0');

        if ($countryCode === '' || $localNumber === '') {
            $this->addError('phone_number', 'Broj telefona nije ispravan.');
            return;
        }

        $this->phone = '+' . $countryCode . ' ' . $localNumber;

        $normalizedPhone = $this->normalizePhone($this->phone);

        if ($normalizedPhone === '') {
            $this->addError('phone_number', 'Broj telefona nije ispravan.');
            return;
        }

        $invitedGuest = EventGuest::where('event_id', $this->event->id)
            ->where('phone_normalized', $normalizedPhone)
            ->first();

        if (!$invitedGuest) {
            $this->addError('phone_number', 'Pogrešan broj ili broj nije na listi gostiju..');
            return;
        }

        $guests = match ($this->status) {
            'yes' => 1,
            'couple' => 2,
            'no' => 0,
        };

        if ($guests > $invitedGuest->max_guests) {
            $this->addError('status', 'Za ovaj broj telefona nije dopuštena potvrda za toliko osoba.');
            return;
        }

        EventRsvp::updateOrCreate(
            [
                'event_id' => $this->event->id,
                'event_guest_id' => $invitedGuest->id,
            ],
            [
                'status' => $this->status,
                'name' => trim($this->name),
                'email' => trim($this->email) ?: null,
                'phone' => $this->phone,
                'guests_count' => $guests,
                'responded_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        );

        $this->qrDownloadUrl = null;

        if (($this->event->enable_qr_codes ?? false) && $guests > 0) {
            $this->qrDownloadUrl = route('invite.qr.download', [
                'token' => $this->event->token,
                'guestToken' => $invitedGuest->ensureQrToken(),
            ]);
        }

        $this->successMessage = 'Hvala! Vaša potvrda je uspješno poslana.';

        $this->resetRsvpForm();
    }

    public function retrieveQr(): void
    {
        abort_unless($this->event->enable_qr_codes ?? false, 404);

        $this->qrLookupMessage = null;
        $this->qrDownloadUrl = null;

        $this->validate([
            'qr_phone_country' => ['required', 'string', 'max:3'],
            'qr_phone_number' => ['required', 'string', 'max:30'],
        ], [
            'qr_phone_country.required' => 'Unesite pozivni broj države.',
            'qr_phone_number.required' => 'Unesite broj telefona.',
        ]);

        $countryCode = preg_replace('/\D+/', '', $this->qr_phone_country);
        $localNumber = preg_replace('/\D+/', '', $this->qr_phone_number);
        $localNumber = ltrim($localNumber, '0');

        if ($countryCode === '' || $localNumber === '') {
            $this->addError('qr_phone_number', 'Broj telefona nije ispravan.');
            return;
        }

        $normalizedPhone = $this->normalizePhone('+' . $countryCode . ' ' . $localNumber);

        $guest = EventGuest::where('event_id', $this->event->id)
            ->where('phone_normalized', $normalizedPhone)
            ->whereHas('rsvp', fn ($q) => $q->whereIn('status', ['yes', 'couple']))
            ->with('rsvp')
            ->first();

        if (!$guest) {
            $this->addError('qr_phone_number', 'Nije pronađena potvrda dolaska za ovaj broj.');
            return;
        }

        $this->qrDownloadUrl = route('invite.qr.download', [
            'token' => $this->event->token,
            'guestToken' => $guest->ensureQrToken(),
        ]);
        $this->qrLookupMessage = 'QR kod je pronađen.';
    }

    public function resetRsvpForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->phone_country = '';
        $this->phone_number = '';
        $this->status = 'yes';
        $this->guests_count = 1;

        $this->resetValidation();

        $this->formKey++;
    }

    private function normalizePhone(?string $phone): string
    {
        $phone = trim((string) $phone);

        // Skida početni +
        $phone = ltrim($phone, '+');

        // Očekivan format: 385 91 1234567
        $parts = preg_split('/\s+/', $phone);

        if (!$parts || count($parts) === 0) {
            return '';
        }

        // Prvi deo je pozivni broj države
        $countryCode = preg_replace('/\D+/', '', array_shift($parts));

        // Ostatak je lokalni broj
        $localNumber = preg_replace('/\D+/', '', implode('', $parts));

        // Briše nulu nakon pozivnog broja
        $localNumber = ltrim($localNumber, '0');

        if ($countryCode === '' || $localNumber === '') {
            return '';
        }

        return $countryCode . $localNumber;
    }

    public function render()
    {
        return view('livewire.invite.show');
    }
}

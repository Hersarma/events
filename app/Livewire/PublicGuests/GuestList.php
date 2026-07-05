<?php

namespace App\Livewire\PublicGuests;

use App\Models\Event;
use App\Models\EventGuest;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class GuestList extends Component
{
    public string $token;
    public string $q = '';

    public Event $event;

    public string $first_name = '';
    public string $last_name = '';

    // Čuva se kompletan broj, npr. +385 91 1234567
    public string $phone = '';

    // Polja za prikaz u formi: + | pozivni | broj telefona
    public string $phone_country = '';
    public string $phone_number = '';

    public int $max_guests = 2;
    public ?string $note = null;

    public ?int $editingId = null;
    public int $formKey = 1;

    public function mount(string $token): void
    {
        $this->token = $token;

        $this->event = Event::where('token', $token)->firstOrFail();

        if (!($this->event->enable_guest_list ?? true)) {
            abort(404);
        }

        if (!session()->get('guest_list_access.' . $this->event->id)) {
            redirect()->route('public.guests.pin', $token)->send();
        }
    }

    public function saveGuest(): void
    {
        $this->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'phone_country' => ['required', 'string', 'max:3'],
            'phone_number' => ['required', 'string', 'max:30'],
            
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'first_name.required' => 'Unesite ime gosta.',
            'phone_country.required' => 'Unesite pozivni broj države.',
            'phone_number.required' => 'Unesite broj telefona.',
            
        ]);

        $countryCode = preg_replace('/\D+/', '', $this->phone_country);
        $localNumber = preg_replace('/\D+/', '', $this->phone_number);

        // Briše nulu nakon pozivnog broja:
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

        $exists = EventGuest::where('event_id', $this->event->id)
            ->where('phone_normalized', $normalizedPhone)
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->exists();

        if ($exists) {
            $this->addError('phone_number', 'Ovaj broj telefona već postoji na popisu gostiju.');
            return;
        }

        $fullName = trim($this->first_name . ' ' . $this->last_name);

        EventGuest::updateOrCreate(
            [
                'id' => $this->editingId,
                'event_id' => $this->event->id,
            ],
            [
                'first_name' => trim($this->first_name),
                'last_name' => trim($this->last_name) ?: null,
                'full_name' => $fullName,
                'phone' => $this->phone,
                'phone_normalized' => $normalizedPhone,
                'max_guests' => 2,
                'note' => $this->note,
            ]
        );

        $this->resetGuestForm();

        $this->dispatch('flash', message: 'Gost je spremljen.', type: 'success');
    }

    public function editGuest(int $id): void
    {
        $guest = EventGuest::where('event_id', $this->event->id)
            ->where('id', $id)
            ->firstOrFail();

        $this->editingId = $guest->id;
        $this->first_name = $guest->first_name ?? '';
        $this->last_name = $guest->last_name ?? '';
        $this->phone = $guest->phone ?? '';
        $this->note = $guest->note;

        $this->fillPhoneParts($guest->phone);

        $this->resetValidation();

        $this->formKey++;
    }

    public function deleteGuest(int $id): void
    {
        $guest = EventGuest::where('event_id', $this->event->id)
            ->where('id', $id)
            ->firstOrFail();

        $guest->rsvp()->delete();

        $guest->delete();

        $this->dispatch('flash', message: 'Gost i njegova potvrda su obrisani.', type: 'success');
    }

    public function resetGuestForm(): void
    {
        $this->editingId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->phone = '';
        $this->phone_country = '';
        $this->phone_number = '';
        $this->max_guests = 2;
        $this->note = null;

        $this->resetValidation();

        $this->formKey++;
    }

    private function fillPhoneParts(?string $phone): void
    {
        $phone = trim((string) $phone);

        $this->phone_country = '';
        $this->phone_number = '';

        if ($phone === '') {
            return;
        }

        $phone = ltrim($phone, '+');

        $parts = preg_split('/\s+/', $phone);

        if (!$parts || count($parts) === 0) {
            return;
        }

        $this->phone_country = preg_replace('/\D+/', '', array_shift($parts));
        $this->phone_number = trim(implode(' ', $parts));
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
        $term = trim($this->q);

        $query = EventGuest::where('event_id', $this->event->id)
            ->with('rsvp');

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('full_name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        $guests = $query
            ->orderBy('full_name')
            ->get();

        $printGuests = EventGuest::where('event_id', $this->event->id)
            ->with('rsvp')
            ->orderBy('full_name')
            ->get();

        $comingCount = EventGuest::where('event_id', $this->event->id)
            ->whereHas('rsvp', function ($q) {
                $q->whereIn('status', ['yes', 'couple']);
            })
            ->with('rsvp')
            ->get()
            ->sum(fn ($guest) => $guest->rsvp?->guests_count ?? 0);

        $invitedCount = EventGuest::where('event_id', $this->event->id)->count();

        $answeredCount = EventGuest::where('event_id', $this->event->id)
            ->whereHas('rsvp')
            ->count();

        $notAnsweredCount = $invitedCount - $answeredCount;
        $checkedInCount = EventGuest::where('event_id', $this->event->id)
            ->whereNotNull('checked_in_at')
            ->count();

        return view('livewire.public-guests.guest-list', [
            'event' => $this->event,
            'guests' => $guests,
            'printGuests' => $printGuests,
            'comingCount' => $comingCount,
            'invitedCount' => $invitedCount,
            'answeredCount' => $answeredCount,
            'notAnsweredCount' => $notAnsweredCount,
            'checkedInCount' => $checkedInCount,
        ]);
    }
}

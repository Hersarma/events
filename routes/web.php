<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Invite\Show as InviteShow;
use App\Livewire\Events\Index as EventsIndex;
use App\Livewire\Events\Form as EventsForm;
use App\Livewire\PublicGuests\Pin;
use App\Livewire\PublicGuests\GuestList;

// HOME
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('events.index')
        : redirect()->route('login');
});

// PUBLIC INVITE
Route::get('/inv/{token}', InviteShow::class)->name('invite.show');
Route::get('/guests/{token}', Pin::class)->name('public.guests.pin');
Route::get('/guests/{token}/list', GuestList::class)->name('public.guests.list');

// ADMIN
Route::middleware(['auth'])->group(function () {
    Route::get('/events', EventsIndex::class)->name('events.index');
    Route::get('/events/create', EventsForm::class)->name('events.create');
    Route::get('/events/{event}/edit', EventsForm::class)->name('events.edit');
});

require __DIR__ . '/auth.php';

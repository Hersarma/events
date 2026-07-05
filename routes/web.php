<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Invite\Show as InviteShow;
use App\Livewire\Events\Index as EventsIndex;
use App\Livewire\Events\Form as EventsForm;
use App\Livewire\PublicGuests\Pin;
use App\Livewire\PublicGuests\GuestList;
use App\Livewire\PublicGuests\Scanner;
use App\Livewire\PublicGuests\CheckIn;
use App\Http\Controllers\GuestQrController;

// HOME
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('events.index')
        : redirect()->route('login');
});

// PUBLIC INVITE
Route::get('/inv/{token}', InviteShow::class)->name('invite.show');
Route::get('/inv/{token}/qr/{guestToken}', [GuestQrController::class, 'download'])->name('invite.qr.download');
Route::get('/guests/{token}', Pin::class)->name('public.guests.pin');
Route::get('/guests/{token}/list', GuestList::class)->name('public.guests.list');
Route::get('/guests/{token}/scan', Scanner::class)->name('public.guests.scan');
Route::get('/guests/{token}/check-in/{guestToken}', CheckIn::class)->name('public.guests.check-in');

// ADMIN
Route::middleware(['auth'])->group(function () {
    Route::get('/events', EventsIndex::class)->name('events.index');
    Route::get('/events/create', EventsForm::class)->name('events.create');
    Route::get('/events/{event}/edit', EventsForm::class)->name('events.edit');
});

require __DIR__ . '/auth.php';

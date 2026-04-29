<div class="min-h-screen bg-neutral-100 px-4 py-8 sm:px-6 sm:py-10">
    <div class="mx-auto w-full max-w-4xl space-y-6">

        {{-- HEADER --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $event->title }}</div>
                <div class="mt-1 text-sm text-gray-600">
                    Popis pozvanih gostiju i potvrda dolaska
                </div>
            </div>

            {{-- STATISTIKA --}}
            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div class="rounded-xl border border-gray-200 p-4">
                    <div class="text-xs text-gray-500">Pozvano</div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ $invitedCount }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4">
                    <div class="text-xs text-gray-500">Ukupno dolazi</div>
                    <div class="text-2xl font-bold text-green-600">
                        {{ $comingCount }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4">
                    <div class="text-xs text-gray-500">Odgovorili</div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $answeredCount }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 p-4">
                    <div class="text-xs text-gray-500">Nisu odgovorili</div>
                    <div class="text-2xl font-bold text-orange-500">
                        {{ $notAnsweredCount }}
                    </div>
                </div>
            </div>
        </div>

        {{-- FORMA ZA DODAVANJE GOSTA --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6" wire:key="guest-form-{{ $formKey }}">
            <div class="mb-4">
                <div class="text-lg font-bold text-gray-900">
                    {{ $editingId ? 'Uređivanje gosta' : 'Dodaj gosta' }}
                </div>
                <div class="mt-1 text-sm text-gray-600">
                    Unesite ime, prezime i broj telefona gosta koji može potvrditi dolazak.
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-gray-700">Ime</label>
                    <input
                        type="text"
                        wire:model="first_name"
                        class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm"
                        placeholder="npr. Marko"
                    >
                    @error('first_name')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Prezime</label>
                    <input
                        type="text"
                        wire:model="last_name"
                        class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm"
                        placeholder="npr. Petrović"
                    >
                    @error('last_name')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
    <label class="text-sm font-medium text-gray-700">Broj telefona</label>

    <div class="mt-1 flex overflow-hidden rounded-xl border border-gray-200 bg-white focus-within:ring-2 focus-within:ring-gray-900/10">
        <div class="flex items-center border-r border-gray-200 bg-gray-50 px-3 text-sm font-bold text-gray-700">
            +
        </div>

        <input
            type="text"
            wire:model="phone_country"
            inputmode="numeric"
            class="w-24 border-0 border-r border-gray-200 px-3 py-2 text-sm outline-none focus:ring-0"
            placeholder="pozivni"
        >

        <input
            type="text"
            wire:model="phone_number"
            inputmode="tel"
            autocomplete="tel"
            class="min-w-0 flex-1 border-0 px-3 py-2 text-sm outline-none focus:ring-0"
            placeholder="broj telefona"
        >
    </div>

    <p class="mt-1 text-xs text-gray-500">
        Unesite pozivni broj države i broj telefona. Primjer: +385 91 123 4567.
        Ako unesete 0 nakon pozivnog broja, sustav će je automatski ukloniti.
    </p>

    @error('phone_country')
        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
    @enderror

    @error('phone_number')
        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
    @enderror
</div>


                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Napomena</label>
                    <textarea
                        wire:model="note"
                        rows="2"
                        class="mt-1 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm"
                        placeholder="Neobavezna napomena..."
                    ></textarea>
                    @error('note')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                <button
                    type="button"
                    wire:click="saveGuest"
                    class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    {{ $editingId ? 'Spremi izmjene' : 'Dodaj gosta' }}
                </button>

                @if($editingId)
                    <button
                        type="button"
                        wire:click="resetGuestForm"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Otkaži uređivanje
                    </button>
                @endif
            </div>
        </div>

        {{-- SEARCH --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <label class="text-sm font-medium text-gray-700">Pretraga gostiju</label>
            <div class="mt-2 flex gap-2">
                <input
                    wire:model.live.debounce.300ms="q"
                    type="text"
                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm"
                    placeholder="npr. Marko ili 091..."
                />

                @if($q)
                    <button
                        type="button"
                        wire:click="$set('q','')"
                        class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold"
                    >
                        X
                    </button>
                @endif
            </div>
        </div>

        {{-- LISTA GOSTIJU --}}
        <div class="grid gap-3">
            @forelse($guests as $guest)
                @php
                    $rsvp = $guest->rsvp;
                @endphp

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <div class="font-semibold text-gray-900">
                                    {{ $guest->full_name }}
                                </div>

                                @if(!$rsvp)
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                        Nije odgovorio
                                    </span>
                                @elseif($rsvp->status === 'yes')
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Dolazi sam
                                    </span>
                                @elseif($rsvp->status === 'couple')
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Dolazi u paru
                                    </span>
                                @elseif($rsvp->status === 'no')
                                    <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Ne dolazi
                                    </span>
                                @endif
                            </div>

                            <div class="mt-1 text-sm text-gray-600">
                                {{ $guest->phone }}
                            </div>


                            @if($rsvp)
                                <div class="mt-2 text-sm text-gray-700">
                                    Potvrđeno osoba:
                                    <span class="font-semibold">{{ $rsvp->guests_count }}</span>
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    Vrijeme potvrde:
                                    {{ optional($rsvp->responded_at ?? $rsvp->created_at)->format('d.m.Y H:i') }}
                                </div>
                            @endif

                            @if($guest->note)
                                <div class="mt-3 rounded-xl bg-gray-50 px-3 py-2 text-sm text-gray-700">
                                    {{ $guest->note }}
                                </div>
                            @endif

                            @if($rsvp?->note)
                                <div class="mt-3 rounded-xl bg-blue-50 px-3 py-2 text-sm text-blue-800">
                                    Poruka gosta: {{ $rsvp->note }}
                                </div>
                            @endif
                        </div>

                        <div class="flex shrink-0 gap-2 sm:flex-col">
                            <button
                                type="button"
                                wire:click="editGuest({{ $guest->id }})"
                                onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                                class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            >
                                Uredi
                            </button>

                            <button
                                type="button"
                                wire:click="deleteGuest({{ $guest->id }})"
                                wire:confirm="Jeste li sigurni da želite obrisati ovog gosta?"
                                class="rounded-xl border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                            >
                                Obriši
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-gray-600">
                    Još nema unesenih gostiju.
                </div>
            @endforelse
        </div>

    </div>
</div>
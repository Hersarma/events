<div class="min-h-screen bg-neutral-100 px-4 py-8 sm:px-6">
    <div class="mx-auto w-full max-w-xl space-y-5">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                Check-in
            </div>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">
                {{ $guest->full_name }}
            </h1>
            <div class="mt-1 text-sm text-gray-600">
                {{ $event->title }}
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-xl border border-gray-200 p-4">
                    <div class="text-xs text-gray-500">Telefon</div>
                    <div class="mt-1 text-sm font-semibold text-gray-900">{{ $guest->phone }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 p-4">
                    <div class="text-xs text-gray-500">Potvrđeno osoba</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $guest->rsvp?->guests_count ?? 1 }}</div>
                </div>
            </div>

            @if($guest->checked_in_at)
                <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    <div class="font-semibold">Već čekirano</div>
                    <div class="mt-1 text-sm">
                        {{ $guest->checked_in_at->format('d.m.Y H:i') }}
                        @if($guest->checked_in_count)
                            · {{ $guest->checked_in_count }} osoba
                        @endif
                    </div>
                </div>
            @endif

            <div class="mt-5">
                <label class="text-sm font-medium text-gray-700">Broj osoba koje ulaze</label>
                <input
                    type="number"
                    min="1"
                    max="{{ max(1, (int) ($guest->rsvp?->guests_count ?? 1)) }}"
                    wire:model.defer="checked_in_count"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 text-lg font-semibold outline-none focus:ring-2 focus:ring-gray-200"
                >
                @error('checked_in_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex flex-col gap-2 sm:flex-row">
                <button
                    type="button"
                    wire:click="confirm"
                    class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Potvrdi dolazak
                </button>

                @if($guest->checked_in_at)
                    <button
                        type="button"
                        wire:click="resetCheckIn"
                        wire:confirm="Poništiti check-in za ovog gosta?"
                        class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-white px-5 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"
                    >
                        Poništi check-in
                    </button>
                @endif

                <a
                    href="{{ route('public.guests.scan', $event->token) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Skeniraj sledeći
                </a>
            </div>
        </div>
    </div>
</div>

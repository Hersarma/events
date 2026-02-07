<div class="max-w-6xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Događaji</h1>

        <a href="{{ route('events.create') }}"
           class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
            + Novi događaj
        </a>
    </div>

    <div class="mt-6 grid gap-4">
        @forelse($events as $event)
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="font-semibold">{{ $event->title }}</div>

                        <div class="text-sm text-gray-600">
                            {{ $event->date_at?->format('d.m.Y H:i') ?? '—' }}
                            • {{ $event->location_name ?? '—' }}
                        </div>

                        <div class="mt-2 text-xs text-gray-500 break-all">
                            Link pozivnice:
                            <a class="underline" href="{{ $event->invite_url }}" target="_blank">
                                {{ $event->invite_url }}
                            </a>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('events.edit', $event) }}"
                           class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold">
                            Izmeni
                        </a>

                        <button type="button"
                                class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white"
                                x-data
                                @click="navigator.clipboard.writeText(@js($event->invite_url))">
                            Kopiraj link
                        </button>
                        <button
                            type="button"
                            wire:click="confirmDelete({{ $event->id }})"
                            class="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                        >
                            Obriši
                        </button>

                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-gray-600">
                Nema kreiranih događaja. Klikni na „Novi događaj“.
            </div>
        @endforelse
    </div>
    <x-modal name="confirm-delete-event" maxWidth="md">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900">
            Obriši događaj
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            Da li si siguran da želiš da obrišeš događaj
            <span class="font-semibold">
                {{ $eventToDelete?->title }}
            </span>?
            <br>
            Ova akcija se ne može poništiti.
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button
                type="button"
                class="rounded-xl border border-gray-200 px-4 py-2 text-sm"
                x-on:click="$dispatch('close-modal', 'confirm-delete-event')"
            >
                Otkaži
            </button>

            <button
                type="button"
                wire:click="delete"
                class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white"
            >
                Obriši
            </button>
        </div>
    </div>
</x-modal>

</div>

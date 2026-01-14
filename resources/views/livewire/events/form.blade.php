<div class="max-w-5xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">
                {{ $event ? 'Izmena događaja' : 'Kreiranje događaja' }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Upload MP4 videa i podešavanje digitalne pozivnice
            </p>
        </div>

        <div class="flex gap-2">
            @if($this->inviteUrl)
                <a href="{{ $this->inviteUrl }}" target="_blank"
                   class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold">
                    Otvori pozivnicu
                </a>

                <button type="button"
                        class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white"
                        x-data
                        @click="navigator.clipboard.writeText(@js($this->inviteUrl))">
                    Kopiraj link
                </button>
            @endif
        </div>
    </div>

    @if (session('status'))
        <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="mt-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-gray-700">Naziv događaja</label>
                <input wire:model.live="title"
                       class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Datum i vreme</label>
                <input type="datetime-local" wire:model.defer="date_at"
                       class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                @error('date_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">RSVP email (prima potvrde)</label>
                <input wire:model.defer="rsvp_email"
                       class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                @error('rsvp_email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Naziv lokacije</label>
                <input wire:model.defer="location_name"
                       class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                @error('location_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Link lokacije (Google Maps)</label>
                <input wire:model.defer="location_url"
                       class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                @error('location_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <label class="text-sm font-medium text-gray-700">Primarna boja</label>
                <div class="mt-2 flex items-center gap-3">
                    <input type="color" wire:model.defer="primary_color" class="h-10 w-14 rounded-lg border border-gray-200" />
                    <input wire:model.defer="primary_color"
                           class="flex-1 rounded-2xl border border-gray-200 px-4 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
                @error('primary_color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <label class="text-sm font-medium text-gray-700">Sekundarna boja</label>
                <div class="mt-2 flex items-center gap-3">
                    <input type="color" wire:model.defer="secondary_color" class="h-10 w-14 rounded-lg border border-gray-200" />
                    <input wire:model.defer="secondary_color"
                           class="flex-1 rounded-2xl border border-gray-200 px-4 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
                @error('secondary_color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-semibold">Video (MP4)</div>
                    <div class="text-sm text-gray-600">Uploaduj video koji se prikazuje na vrhu pozivnice.</div>
                </div>
                <div class="text-sm text-gray-500">
                    Maksimalno 50 MB
                </div>
            </div>

            <input type="file" accept="video/mp4" wire:model="video"
                   class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />

            @error('video') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <div wire:loading wire:target="video" class="text-sm text-gray-600">
                Upload videa u toku...
            </div>

            @if($video_path)
                <div class="rounded-2xl border border-gray-200 overflow-hidden">
                    <video class="w-full" controls>
                        <source src="{{ asset('storage/'.$video_path) }}" type="video/mp4">
                    </video>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <label class="text-sm font-medium text-gray-700">Aktivan događaj</label>
                <div class="mt-3 flex items-center gap-3">
                    <input type="checkbox" wire:model.defer="is_active" class="h-5 w-5 rounded border-gray-300" />
                    <span class="text-sm text-gray-700">Događaj je dostupan putem linka</span>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <label class="text-sm font-medium text-gray-700">Datum isteka (opciono)</label>
                <input type="datetime-local" wire:model.defer="expires_at"
                       class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                @error('expires_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('events.index') }}"
               class="rounded-xl border border-gray-200 bg-white px-5 py-2 text-sm font-semibold">
                Otkaži
            </a>

            <button type="submit"
                    class="rounded-xl bg-gray-900 px-6 py-2 text-sm font-semibold text-white">
                Sačuvaj
            </button>
        </div>
    </form>
</div>

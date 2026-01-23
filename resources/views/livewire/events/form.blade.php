{{-- resources/views/livewire/events/form.blade.php --}}
<div class="max-w-5xl mx-auto px-6 py-8">
    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">
                {{ $event ? 'Izmena događaja' : 'Kreiranje događaja' }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                MP4/hero + podešavanja pozivnice + RSVP
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

    {{-- Flash --}}
    @if (session('status'))
        <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="mt-6 space-y-6">

        {{-- Osnovno --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div class="font-semibold">Osnovne informacije</div>
                <div class="text-xs text-gray-500">
                    Link: <span class="font-mono break-all">{{ $this->inviteUrl ?? '—' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Šablon</label>
                    <select wire:model.live="template"
                            class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-gray-200">
                        <option value="wedding">Svadba</option>
                        <option value="kids">Dečiji rođendan</option>
                        <option value="celebration">Proslava / godišnjica</option>
                    </select>
                    @error('template') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @if(!$event)
                        <p class="mt-1 text-xs text-gray-500">Kod novog događaja, promena šablona menja i podrazumevani tekst/boje.</p>
                    @endif
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Jezik</label>
                    <select wire:model.defer="language"
                            class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-gray-200">
                        <option value="sr">Srpski</option>
                        <option value="hr">Hrvatski</option>
                        <option value="en">Engleski</option>
                    </select>
                    @error('language') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Naziv događaja (naslov)</label>
                    <input wire:model.live="title"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                    <div class="mt-2 text-xs text-gray-500">
                        Slug: <span class="font-mono">{{ $slug ?: '—' }}</span> • Token: <span class="font-mono">{{ $token ?: '—' }}</span>
                    </div>
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
                    <label class="text-sm font-medium text-gray-700">Adresa lokacije</label>
                    <input wire:model.defer="location_address"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('location_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Link lokacije (Google Maps)</label>
                    <input wire:model.defer="location_url"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('location_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-200 p-4 space-y-4">
    <h3 class="font-semibold">Date sekcija – stil</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Background --}}
        <div>
            <label class="text-xs uppercase">Pozadina</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="date_bg" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="date_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Primary text --}}
        <div>
            <label class="text-xs uppercase">Glavni tekst</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="date_text_primary" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="date_text_primary" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Secondary text --}}
        <div>
            <label class="text-xs uppercase">Sekundarni tekst</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="date_text_secondary" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="date_text_secondary" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Lines --}}
        <div>
            <label class="text-xs uppercase">Linije</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="date_lines" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="date_lines" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

    </div>
</div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
    <div class="font-semibold">Lokacija (stil + slike)</div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- BG --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Pozadina</label>
            <div class="mt-2 flex items-center gap-3">
                <input type="color" wire:model.defer="location_bg" class="h-10 w-14 rounded-lg border border-gray-200" />
                <input wire:model.defer="location_bg" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
            </div>
        </div>

        {{-- Text --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Boja teksta (naziv)</label>
            <div class="mt-2 flex items-center gap-3">
                <input type="color" wire:model.defer="location_text" class="h-10 w-14 rounded-lg border border-gray-200" />
                <input wire:model.defer="location_text" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
            </div>
        </div>

        {{-- Sub text --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Boja teksta (adresa)</label>
            <div class="mt-2 flex items-center gap-3">
                <input type="color" wire:model.defer="location_sub_text" class="h-10 w-14 rounded-lg border border-gray-200" />
                <input wire:model.defer="location_sub_text" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
        {{-- Marker PNG --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Marker ikonica (PNG)</label>
            <input type="file" accept="image/png" wire:model="location_marker"
                   class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
            @error('location_marker') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

            @if($location_marker_path)
                <div class="mt-3">
                    <img src="{{ asset('storage/'.$location_marker_path) }}" class="h-12 w-12 object-contain" alt="Marker preview">
                </div>
            @endif
        </div>

        {{-- Location image --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Slika lokacije (ispod teksta)</label>
            <input type="file" accept="image/*" wire:model="location_image"
                   class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
            @error('location_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

            @if($location_image_path)
                <div class="mt-3 overflow-hidden rounded-2xl border border-gray-200">
                    <img src="{{ asset('storage/'.$location_image_path) }}" class="w-full object-cover" alt="Location preview">
                </div>
            @endif
        </div>
    </div>
</div>

        {{-- Status --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <label class="text-sm font-medium text-gray-700">Aktivan</label>
                <div class="mt-3 flex items-center gap-3">
                    <input type="checkbox" wire:model.defer="is_active" class="h-5 w-5 rounded border-gray-300" />
                    <span class="text-sm text-gray-700">Događaj je dostupan preko linka</span>
                </div>
                @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <label class="text-sm font-medium text-gray-700">Ističe (opciono)</label>
                <input type="datetime-local" wire:model.defer="expires_at"
                       class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                @error('expires_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- HERO --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-semibold">Hero (vrh pozivnice)</div>
                    <div class="text-sm text-gray-600">Video ili slika koja ide preko celog ekrana na vrhu.</div>
                </div>
                <div class="text-sm text-gray-500">
                    Video max 50MB
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Tip hero sekcije</label>
                    <select wire:model.defer="hero_type"
                            class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-gray-200">
                        <option value="video">Video</option>
                        <option value="image">Slika</option>
                    </select>
                    @error('hero_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="text-xs text-gray-500 flex items-end">
                    Preporuka: 1080×1920 (vertikalno) ili 1920×1080 (horizontalno), MP4.
                </div>
            </div>

            {{-- Video upload --}}
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Hero video (MP4)</label>
                <input type="file" accept="video/mp4" wire:model="hero_video"
                       class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
                @error('hero_video') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                <div wire:loading wire:target="hero_video" class="text-sm text-gray-600">
                    Uploadujem video...
                </div>

                @if($hero_video_path)
                    <div class="rounded-2xl border border-gray-200 overflow-hidden">
                        <video class="w-full" controls playsinline>
                            <source src="{{ asset('storage/'.$hero_video_path) }}" type="video/mp4">
                        </video>
                    </div>
                @endif
            </div>

            {{-- Image upload --}}
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Hero slika (JPG/PNG) – opciono</label>
                <input type="file" accept="image/*" wire:model="hero_image"
                       class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
                @error('hero_image') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                <div wire:loading wire:target="hero_image" class="text-sm text-gray-600">
                    Uploadujem sliku...
                </div>

                @if($hero_image_path)
                    <div class="rounded-2xl border border-gray-200 overflow-hidden">
                        <img src="{{ asset('storage/'.$hero_image_path) }}" alt="Hero slika" class="w-full object-cover">
                    </div>
                @endif
            </div>
        </div>

        {{-- MAPA I FOOTER --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-3">
                <div class="font-semibold">Slika mape (opciono)</div>
                <p class="text-sm text-gray-600">Ako klijent želi screenshot mape kao na Webflow primeru.</p>

                <input type="file" accept="image/*" wire:model="map_image"
                       class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
                @error('map_image') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                <div wire:loading wire:target="map_image" class="text-sm text-gray-600">Uploadujem sliku...</div>

                @if($map_image_path)
                    <div class="rounded-2xl border border-gray-200 overflow-hidden">
                        <img src="{{ asset('storage/'.$map_image_path) }}" alt="Mapa" class="w-full object-cover">
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-3">
                <div class="font-semibold">Logo u footeru (opciono)</div>
                <p class="text-sm text-gray-600">Logo dizajn studija / brenda.</p>

                <input type="file" wire:model="footer_logo"
                       class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
                @error('footer_logo') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                <div wire:loading wire:target="footer_logo" class="text-sm text-gray-600">Uploadujem logo...</div>

                @if($footer_logo_path)
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 flex items-center justify-center">
                        <img src="{{ asset('storage/'.$footer_logo_path) }}" alt="Footer logo" class="max-h-20">
                    </div>
                @endif
            </div>
        </div>

        {{-- TEKSTOVI (CONTENT JSON) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div>
                <div class="font-semibold">Tekstovi na stranici</div>
                <p class="text-sm text-gray-600">Ovo su promenljivi delovi koje klijent menja.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Naslov uvoda</label>
                    <input wire:model.defer="content.intro_title"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Footer: naziv brenda</label>
                    <input wire:model.defer="content.footer_brand"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>

                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Uvodni tekst</label>
                    <textarea wire:model.defer="content.intro_text" rows="4"
                              class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"></textarea>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">RSVP naslov</label>
                    <input wire:model.defer="content.rsvp_title"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">RSVP podnaslov</label>
                    <input wire:model.defer="content.rsvp_subtitle"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
                <h3 class="font-semibold">RSVP – radio dugmići</h3>

    <div>
        <label class="text-xs uppercase">Boja teksta (radio)</label>
        <div class="mt-1 flex gap-2">
            <input type="color" wire:model.live="rsvp_radio_label" class="h-10 w-12">
            <input type="text" wire:model.live="rsvp_radio_label" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
        </div>
    </div>

    <div>
        <label class="text-xs uppercase">Boja radio dugmeta</label>
        <div class="mt-1 flex gap-2">
            <input type="color" wire:model.live="rsvp_radio_accent" class="h-10 w-12">
            <input type="text" wire:model.live="rsvp_radio_accent" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
        </div>
    </div>
            </div>
        </div>

        {{-- BOJE (STYLE JSON) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div>
                <div class="font-semibold">Boje (stil)</div>
                <p class="text-sm text-gray-600">Boje su vezane za sekcije (intro, datum, lokacija, RSVP).</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Intro --}}
                <div class="rounded-2xl border border-gray-200 p-4">
                    <div class="font-semibold mb-3">Uvod</div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <label class="w-36 text-sm text-gray-700">Pozadina</label>
                            <input type="color" wire:model.defer="style.intro.bg" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.defer="style.intro.bg" class="flex-1 rounded-2xl border border-gray-200 px-3 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="w-36 text-sm text-gray-700">Naslov</label>
                            <input type="color" wire:model.defer="style.intro.title_color" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.defer="style.intro.title_color" class="flex-1 rounded-2xl border border-gray-200 px-3 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="w-36 text-sm text-gray-700">Tekst</label>
                            <input type="color" wire:model.defer="style.intro.text_color" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.defer="style.intro.text_color" class="flex-1 rounded-2xl border border-gray-200 px-3 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                        </div>
                    </div>
                </div>

                {{-- RSVP --}}
                <div class="rounded-2xl border border-gray-200 p-4 space-y-4">
    <h3 class="font-semibold">RSVP sekcija – stil</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- BG --}}
        <div>
            <label class="text-xs uppercase">Pozadina sekcije</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_bg" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Card BG --}}
        <div>
            <label class="text-xs uppercase">Pozadina kartice</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_card_bg" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_card_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Title --}}
        <div>
            <label class="text-xs uppercase">Naslov</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_title_color" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_title_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Subtitle --}}
        <div>
            <label class="text-xs uppercase">Podnaslov</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_subtitle_color" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_subtitle_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Labels --}}
        <div>
            <label class="text-xs uppercase">Label (tekst)</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_label_color" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_label_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Input BG --}}
        <div>
            <label class="text-xs uppercase">Input pozadina</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_input_bg" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_input_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Input border --}}
        <div>
            <label class="text-xs uppercase">Input border</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_input_border" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_input_border" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Input text --}}
        <div>
            <label class="text-xs uppercase">Input tekst</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_input_text" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_input_text" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Radio accent --}}
        <div>
            <label class="text-xs uppercase">Radio tačkica (accent)</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_radio_accent" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_radio_accent" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Button bg --}}
        <div>
            <label class="text-xs uppercase">Dugme pozadina</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_button_bg" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_button_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>

        {{-- Button text --}}
        <div>
            <label class="text-xs uppercase">Dugme tekst</label>
            <div class="mt-1 flex gap-2">
                <input type="color" wire:model.live="rsvp_button_text" class="h-10 w-12 rounded border p-1">
                <input type="text" wire:model.live="rsvp_button_text" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
            </div>
        </div>
    </div>
</div>

            </div>

            <p class="text-xs text-gray-500">
                Ako želiš, možemo dodati i boje za “Datum” i “Lokaciju” identično kao gore (samo proširimo style JSON).
            </p>
        </div>

        {{-- Akcije --}}
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

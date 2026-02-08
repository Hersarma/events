{{-- resources/views/livewire/events/form.blade.php --}}
<div class="max-w-5xl mx-auto px-6 py-8">
    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">
                {{ $event ? 'Izmena događaja' : 'Kreiranje događaja' }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Editor prati redosled pozivnice: Hero → Intro → Datum → Lokacija → RSVP → Footer
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

        {{-- 0) OSNOVNE INFORMACIJE (meta događaja) --}}
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
                        <option value="celebration">Proslava / godišnjica</option>
                    </select>
                    @error('template') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @if(!$event)
                        <p class="mt-1 text-xs text-gray-500">Kod novog događaja, promena šablona menja i podrazumevani tekst/boje.</p>
                    @endif
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

                {{-- DATE (data) --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Datum i vreme</label>
                    <input type="datetime-local" wire:model.defer="date_at"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('date_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- RSVP email (data) --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">RSVP email (prima potvrde)</label>
                    <input wire:model.defer="rsvp_email"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('rsvp_email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- LOCATION (data) --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Mesto</label>
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

        {{-- 1) HERO --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-semibold">1) Hero (vrh pozivnice)</div>
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
                        <video class="w-40 h-80" controls playsinline>
                            <source src="{{ asset('storage/'.$hero_video_path) }}" type="video/mp4">
                        </video>
                    </div>
                    <button type="button"
                        wire:click="removeHeroVideo"
                        wire:confirm="Obrisati hero video?"
                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
                    Ukloni video
                </button>
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
                        <img src="{{ asset('storage/'.$hero_image_path) }}" alt="Hero slika" class="object-cover w-40 h-80">
                    </div>
                    <button type="button"
            wire:click="removeHeroImage"
            wire:confirm="Obrisati hero sliku?"
            class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
        Ukloni sliku
    </button>
                @endif
            </div>
        </div>

        {{-- 2) INTRO (content + intro boje) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="font-semibold">2) Intro (uvodni tekst)</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Naslov uvoda</label>
                    <input wire:model.defer="content.intro_title"
                           class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Uvodni tekst</label>
                    <textarea wire:model.defer="content.intro_text" rows="4"
                              class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"></textarea>
                </div>
            </div>

            {{-- INTRO stil (iz tvog “Boje” bloka) --}}
            <div class="rounded-2xl border border-gray-200 p-4">
                <div class="font-semibold mb-3">Intro – stil</div>

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
        </div>

        {{-- 3) DATE (stil) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="font-semibold">3) Date sekcija – stil</div>

            {{-- tvoj ceo date stil blok --}}
            <div class="rounded-2xl border border-gray-200 p-4 space-y-4">
                <h3 class="font-semibold">Date sekcija – stil</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs uppercase">Pozadina</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="date_bg" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="date_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Glavni tekst</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="date_text_primary" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="date_text_primary" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Sekundarni tekst</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="date_text_secondary" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="date_text_secondary" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Linije</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="date_lines" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="date_lines" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4) LOCATION (stil + slike) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="font-semibold">4) Lokacija (stil + slike)</div>

            {{-- tvoj ceo location stil + slike blok --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
                <div class="font-semibold">Lokacija (stil + slike)</div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Pozadina</label>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="color" wire:model.defer="location_bg" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.defer="location_bg" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Boja teksta (naziv)</label>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="color" wire:model.defer="location_text" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.defer="location_text" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Boja teksta (adresa)</label>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="color" wire:model.defer="location_sub_text" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.defer="location_sub_text" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
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
        </div>

        {{-- 5) RSVP (tekst + stil) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div class="font-semibold">5) RSVP (tekst + stil)</div>

            {{-- RSVP tekstovi (iz tvog “Tekstovi” bloka) --}}
            <div class="space-y-4">
    <div>
        <label class="text-sm font-medium text-gray-700">RSVP tekst 1</label>
        <input wire:model.defer="content.rsvp_title"
               class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">RSVP tekst 2</label>
        <input wire:model.defer="content.rsvp_subtitle"
               class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700">RSVP tekst 3</label>
        <input wire:model.defer="content.rsvp_third"
               class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
    </div>
</div>

            {{-- RSVP radio + boje (tvoj blok) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs uppercase">Boja teksta (radio)</label>
                    <div class="mt-1 flex gap-2">
                        <input type="color" wire:model.live="rsvp_radio_accent" class="h-10 w-12">
                        <input type="text" wire:model.live="rsvp_radio_accent" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
                <div class="font-semibold">Footer – stil</div>

                <div>
                    <label class="text-xs uppercase">Boja teksta u footeru</label>
                    <div class="mt-1 flex gap-2">
                        <input type="color" wire:model.live="footer_text_color" class="h-10 w-12 rounded border p-1">
                        <input type="text" wire:model.live="footer_text_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                    </div>
                </div>
            </div>

            {{-- RSVP stil (tvoj veliki RSVP stil blok) --}}
            <div class="rounded-2xl border border-gray-200 p-4 space-y-4">
                <h3 class="font-semibold">RSVP sekcija – stil</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs uppercase">Pozadina sekcije</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_bg" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Pozadina kartice</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_card_bg" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_card_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">RSVP Naslov</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_title_color" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_title_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">RSVP Podnaslov</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_subtitle_color" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_subtitle_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">RSVP Treći tekst</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_third_color" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_third_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>


                    <div>
                        <label class="text-xs uppercase">Label (tekst)</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_label_color" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_label_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Input pozadina</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_input_bg" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_input_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Input border</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_input_border" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_input_border" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Input tekst</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_input_text" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_input_text" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase">Dugme pozadina</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_button_bg" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="rsvp_button_bg" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>

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

        {{-- 6) MAPA + FOOTER LOGO (opciono) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-3">
                <div class="font-semibold">Slika mape (opciono)</div>
                

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
        </div>

        {{-- 7) STATUS --}}
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

        {{-- 8) BOJE / STYLE JSON (ako želiš da ostane i “centralno”) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 space-y-4">
            <div>
                <div class="font-semibold">Boje (stil)</div>
                <p class="text-sm text-gray-600">Ovo je “centralno” mesto – većina stilova je gore po sekcijama, ali može da ostane i ovde za pregled.</p>
            </div>

            {{-- ovde možeš kasnije da dodaš još “date/location” stil u style JSON ako bude trebalo --}}
        </div>

        {{-- Akcije --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('events.index') }}" wire:navigate
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

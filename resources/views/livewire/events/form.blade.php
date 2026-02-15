{{-- resources/views/livewire/events/form.blade.php --}}
<div class="mx-auto px-6 py-8">
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

<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 items-start">
  {{-- LEFT: Editor --}}
  <div>
    <form wire:submit.prevent="save" class="mt-6 space-y-6">
        {{-- 0) OSNOVNE INFORMACIJE (meta događaja) --}}
       <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-4">

    <div class="text-center">
        <div class="font-semibold text-lg">Lista gostiju (PIN)</div>
        <p class="text-sm text-gray-600 mt-1">
            Klijent otvara link i unosi 4-cifreni PIN.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">

        {{-- PIN INPUT --}}
        <div>
            <label class="text-sm font-medium text-gray-700">
                PIN (4 cifre)
            </label>

            <input wire:model.defer="guest_list_pin"
                   inputmode="numeric"
                   maxlength="4"
                   class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                   placeholder="npr. 1234" />

            @error('guest_list_pin')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <p class="mt-1 text-xs text-gray-500">
                Ako ostane prazno, ne menja se postojeći PIN.
            </p>
        </div>

        {{-- LINK BLOK --}}
        @if($event)
        <div class="space-y-3">

            <div>
                <div class="text-sm font-medium text-gray-700">Link za klijenta</div>

                <div class="mt-2 flex flex-wrap gap-2">
                    <a href="{{ route('public.guests.pin', $event->token) }}"
                       target="_blank"
                       class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50 transition">
                        Otvori
                    </a>

                    <button type="button"
                        class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800 transition"
                        x-data
                        @click="navigator.clipboard.writeText(@js(route('public.guests.pin', $event->token)))">
                        Kopiraj
                    </button>
                </div>
            </div>

            {{-- FULL URL --}}
            <div class="rounded-xl bg-gray-50 border border-gray-200 px-3 py-2 text-xs text-gray-600 break-all">
                {{ route('public.guests.pin', $event->token) }}
            </div>

        </div>
        @endif

    </div>
</div>


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
                    <label class="text-sm font-medium text-gray-700">Naziv događaja (naslov) ili ime klijenta</label>
                    <input wire:model.live.debounce.500ms="title"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <div class="mt-2 text-xs text-gray-500">
                        Slug: <span class="font-mono">{{ $slug ?: '—' }}</span> • Token: <span class="font-mono">{{ $token ?: '—' }}</span>
                    </div>
                </div>
                {{-- DATE (data) --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Datum i vreme</label>
                    <input type="datetime-local" wire:model.live.debounce.500ms="date_at"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('date_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                {{-- RSVP email (data) --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">RSVP email (prima potvrde)</label>
                    <input wire:model.live.debounce.500ms="rsvp_email"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('rsvp_email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                {{-- LOCATION (data) --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Mesto</label>
                    <input wire:model.live.debounce.500ms="location_name"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('location_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Adresa lokacije</label>
                    <input wire:model.live.debounce.500ms="location_address"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('location_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Link lokacije (Google Maps)</label>
                    <input wire:model.live.debounce.500ms="location_url"
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
                    <select wire:model.live="hero_type"
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
                {{-- Preview odmah nakon izbora fajla --}}
                @if($hero_video)
                  @php $k = md5($hero_video->getFilename() . '|' . $hero_video->getSize()); @endphp

                  <div class="rounded-2xl border border-gray-200 overflow-hidden">
                    <video wire:key="hero-video-upload-{{ $k }}" class="w-40 h-80 object-cover" controls playsinline>
                      <source src="{{ $hero_video->temporaryUrl() }}" type="video/mp4">
                    </video>
                  </div>

                @elseif($hero_video_path)
                  <div class="rounded-2xl border border-gray-200 overflow-hidden">
                    <video wire:key="hero-video-stored-{{ md5($hero_video_path) }}" class="w-40 h-80 object-cover" controls playsinline>
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
                @if($hero_image)
                <div class="rounded-2xl border border-gray-200 overflow-hidden">
                    <img
                    src="{{ $hero_image->temporaryUrl() }}"
                    alt="Hero preview"
                    class="object-cover w-40 h-80"
                    >
                </div>
                @elseif($hero_image_path)
                <div class="rounded-2xl border border-gray-200 overflow-hidden">
                    <img
                    src="{{ asset('storage/'.$hero_image_path) }}"
                    alt="Hero slika"
                    class="object-cover w-40 h-80"
                    >
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
                    <input wire:model.live.debounce.500ms="content.intro_title"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Uvodni tekst</label>
                    <textarea wire:model.live.debounce.500ms="content.intro_text" rows="4"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"></textarea>
                </div>
            </div>
            {{-- INTRO stil (iz tvog “Boje” bloka) --}}
            <div class="rounded-2xl border border-gray-200 p-4">
                <div class="font-semibold mb-3">Intro – stil</div>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <label class="w-36 text-sm text-gray-700">Pozadina</label>
                        <input type="color" wire:model.live="style.intro.bg" class="h-10 w-14 rounded-lg border border-gray-200" />
                        <input wire:model.live.debounce.500ms="style.intro.bg" class="flex-1 rounded-2xl border border-gray-200 px-3 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="w-36 text-sm text-gray-700">Naslov</label>
                        <input type="color" wire:model.live="style.intro.title_color" class="h-10 w-14 rounded-lg border border-gray-200" />
                        <input wire:model.live.debounce.500ms="style.intro.title_color" class="flex-1 rounded-2xl border border-gray-200 px-3 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="w-36 text-sm text-gray-700">Tekst</label>
                        <input type="color" wire:model.live="style.intro.text_color" class="h-10 w-14 rounded-lg border border-gray-200" />
                        <input wire:model.live.debounce.500ms="style.intro.text_color" class="flex-1 rounded-2xl border border-gray-200 px-3 py-2 outline-none focus:ring-2 focus:ring-gray-200" />
                    </div>
                </div>
            </div>
        </div>
        {{-- 3) DATE (stil) --}}
        <div class="rounded-2xl border border-gray-200 p-4 space-y-4">
            <div class="font-semibold">Date tekst (ručno) – opciono</div>
            <p class="text-sm text-gray-600">
                Ako ostane prazno, koristi se automatski naziv dana/meseca iz datuma.
                Ovde možeš upisati npr. španski ili engleski tekst.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Naziv dana</label>
                    <input wire:model.live.debounce.500ms="content.date_day_name"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                    placeholder="npr. Monday" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Naziv meseca</label>
                    <input wire:model.live.debounce.500ms="content.date_month_name"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                    placeholder="npr. agosto" />
                </div>
            </div>
        </div>
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
                            <input type="color" wire:model.live.debounce.500ms="location_bg" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.live.debounce.500ms="location_bg" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Boja teksta (naziv)</label>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="color" wire:model.live.debounce.500ms="location_text" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.live.debounce.500ms="location_text" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Boja teksta (adresa)</label>
                        <div class="mt-2 flex items-center gap-3">
                            <input type="color" wire:model.live.debounce.500ms="location_sub_text" class="h-10 w-14 rounded-lg border border-gray-200" />
                            <input wire:model.live.debounce.500ms="location_sub_text" class="flex-1 rounded-2xl border border-gray-200 px-4 py-2" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Slika mape (PNG)</label>
                        <input type="file" accept="image/*" wire:model="map_image"
                        class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
                        @error('map_image') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="map_image" class="text-sm text-gray-600">Uploadujem sliku...</div>
                        @if($map_image)
                        <div class="rounded-2xl border border-gray-200 overflow-hidden">
                            <img
                            src="{{ $map_image->temporaryUrl() }}"
                            alt="Mapa preview"
                            class="w-full object-cover"
                            >
                        </div>
                        @elseif($map_image_path)
                        <div class="rounded-2xl border border-gray-200 overflow-hidden">
                            <img
                            src="{{ asset('storage/'.$map_image_path) }}"
                            alt="Mapa"
                            class="w-full object-cover"
                            >
                            <button type="button"
                            wire:click="removeMapImage"
                            wire:confirm="Obrisati sliku mape?"
                            class="mt-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
                            Ukloni mapu
                            </button>
                        </div>
                        @endif
                        
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Marker ikonica (PNG)</label>
                        <input type="file" accept="image/png" wire:model="location_marker"
                        class="mt-2 block w-full text-sm text-gray-700 file:mr-4 file:rounded-xl file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-white file:text-sm file:font-semibold" />
                        @error('location_marker') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($location_marker)
                        <div class="mt-3">
                            <img
                            src="{{ $location_marker->temporaryUrl() }}"
                            class="h-12 w-12 object-contain"
                            alt="Marker preview"
                            >
                        </div>
                        @elseif($location_marker_path)
                        <div class="mt-3">
                            <img
                            src="{{ asset('storage/'.$location_marker_path) }}"
                            class="h-12 w-12 object-contain"
                            alt="Marker preview"
                            >
                            <button type="button"
                            wire:click="removeLocationMarker"
                            wire:confirm="Obrisati marker?"
                            class="mt-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
                            Ukloni marker
                            </button>
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
                    <input wire:model.live.debounce.500ms="content.rsvp_title"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">RSVP tekst 2</label>
                    <input wire:model.live.debounce.500ms="content.rsvp_subtitle"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">RSVP tekst 3</label>
                    <input wire:model.live.debounce.500ms="content.rsvp_third"
                    class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                </div>
            </div>
            {{-- RSVP radio + boje (tvoj blok) --}}
            
            {{-- RSVP stil (tvoj veliki RSVP stil blok) --}}
            <div class="rounded-2xl border border-gray-200 p-4 space-y-4">
                <div class="font-semibold">RSVP forma – tekstovi</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Label za ime</label>
                        <input wire:model.live.debounce.500ms="content.rsvp_name_label"
                        class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                        placeholder="Ime i prezime" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Label za telefon</label>
                        <input wire:model.live.debounce.500ms="content.rsvp_phone_label"
                        class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                        placeholder="Broj mobitela" />
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Opcija: YES</label>
                        <input wire:model.live.debounce.500ms="content.rsvp_opt_yes"
                        class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                        placeholder="Dolazim sam" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Opcija: MAYBE</label>
                        <input wire:model.live.debounce.500ms="content.rsvp_opt_maybe"
                        class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                        placeholder="Dolazim u dvoje" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Opcija: NO</label>
                        <input wire:model.live.debounce.500ms="content.rsvp_opt_no"
                        class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                        placeholder="Ne dolazim" />
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tekst dugmeta</label>
                        <input wire:model.live.debounce.500ms="content.rsvp_btn_label"
                        class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                        placeholder="Pošalji" />
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tekst dok se šalje</label>
                        <input wire:model.live.debounce.500ms="content.rsvp_btn_loading"
                        class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200"
                        placeholder="Šaljem..." />
                    </div>
                </div>
            </div>
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
                    <div>
                        <label class="text-xs uppercase">Boja teksta (radio)</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="rsvp_radio_accent" class="h-10 w-12">
                            <input type="text" wire:model.live="rsvp_radio_accent" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs uppercase">Boja teksta u footeru</label>
                        <div class="mt-1 flex gap-2">
                            <input type="color" wire:model.live="footer_text_color" class="h-10 w-12 rounded border p-1">
                            <input type="text" wire:model.live="footer_text_color" class="h-10 w-full rounded-lg border px-3 text-sm uppercase">
                        </div>
                    </div>
                    
                </div>
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

  {{-- RIGHT: Preview --}}
  <div class="lg:sticky lg:top-6">
    @include('livewire.invite.preview._phone', [
  'event' => $this->previewEvent,
  'opened' => true,
  'requiresClick' => false,
])
  </div>
</div>
</div>
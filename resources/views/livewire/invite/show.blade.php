{{-- resources/views/livewire/invite/show.blade.php --}}
<div
    x-data="{
        opened: false,
        lock() { document.documentElement.classList.add('overflow-hidden'); },
        unlock() { document.documentElement.classList.remove('overflow-hidden'); },
        open() {
            this.opened = true;
            this.unlock();

            const v = document.getElementById('inviteVideo');
            if (v) {
                // sigurnije za mobilne: prvo play dok je muted, pa onda opcionalno unmute
                const p = v.play();
                if (p && p.catch) p.catch(() => {});
            }
        },
    }"
    x-init="lock()"
    class="min-h-screen scroll-smooth bg-white"
>

    @php
        $c = (array) ($event->content ?? []);
        $s = (array) ($event->style ?? []);
        $rsvpTitleText = data_get($c, 'rsvp_title', '');
        $rsvpSubText   = data_get($c, 'rsvp_subtitle', '');
        $introBg = data_get($s, 'intro.bg', '#ffffff');
        $introTitle = data_get($s, 'intro.title_color', '#111827');
        $introText = data_get($s, 'intro.text_color', '#374151');

        
        $btnBg = data_get($s, 'rsvp.button_bg', '#111827');
        $btnText = data_get($s, 'rsvp.button_text', '#ffffff');

        $introTitleText = data_get($c, 'intro_title', '');
        $introTextText  = data_get($c, 'intro_text', '');
        
        $footerBrand    = data_get($c, 'footer_brand', '');
        $dateBg = data_get($s, 'date.bg', '#ffffff');
        $dateTextPrimary = data_get($s, 'date.text_primary', '#111827');
        $dateTextSecondary = data_get($s, 'date.text_secondary', '#6b7280');
        $dateLines = data_get($s, 'date.lines', '#d1d5db');
         $locationBg = data_get($s, 'location.bg', '#ffffff');
        $locationText = data_get($s, 'location.text', '#111827');
        $locationSubText = data_get($s, 'location.sub_text', '#6b7280');

        $rsvpBg         = data_get($s, 'rsvp.bg', '#6F7C72');
        $rsvpTitleColor = data_get($s, 'rsvp.title_color', '#FFFFFF');
        $rsvpSubColor   = data_get($s, 'rsvp.subtitle_color', '#FFFFFF');

        $rsvpCardBg     = data_get($s, 'rsvp.card_bg', '#D8CDBD');
        $rsvpCardBorder = data_get($s, 'rsvp.card_border', 'rgba(255,255,255,0.15)');

        $rsvpLabelColor = data_get($s, 'rsvp.label_color', '#FFFFFF');

        $rsvpInputBg     = data_get($s, 'rsvp.input_bg', '#FFFFFF');
        $rsvpInputBorder = data_get($s, 'rsvp.input_border', '#CFC6B7');
        $rsvpInputText   = data_get($s, 'rsvp.input_text', '#111827');

        $rsvpRadioAccent = data_get($s, 'rsvp.radio_accent', '#6F7C72');
        $rsvpRadioBorder = data_get($s, 'rsvp.radio_border', '#FFFFFF');

        $rsvpBtnBg   = data_get($s, 'rsvp.button_bg', '#6F7C72');
        $rsvpBtnText = data_get($s, 'rsvp.button_text', '#FFFFFF');
        @endphp

    {{-- HERO: full screen --}}
    <section class="relative h-screen w-full overflow-hidden">
        @if($event->hero_type === 'image' && $event->hero_image_path)
            <img
                src="{{ asset('storage/'.$event->hero_image_path) }}"
                alt="Pozivnica"
                class="h-full w-full object-cover"
            />
        @elseif($event->hero_video_path)
            <video
                id="inviteVideo"
                class="h-full w-full object-cover"
                playsinline
                preload="metadata"
                autoplay
                muted
                loop
            >
                <source src="{{ asset('storage/'.$event->hero_video_path) }}" type="video/mp4">
            </video>
        @endif

        {{-- klik bilo gde: otključaj skrol + (ponovo) pusti video --}}
        <div x-show="!opened" class="absolute inset-0">
            <button
                type="button"
                class="absolute inset-0 cursor-pointer"
                aria-label="Otvori pozivnicu"
                @click="open()"
            ></button>
        </div>
    </section>

    {{-- CONTENT: tek nakon klika --}}
    <div
        x-show="opened"
        x-transition.opacity.duration.300ms
        class="pb-14"
    >
        {{-- INTRO (tekst kao u Webflow) --}}
        <section style="background: {{ $introBg }};">
            <div class="mx-auto max-w-5xl px-6 py-12">
                <div class="mx-auto max-w-3xl text-center space-y-6">
                    @if($introTitleText)
                        <div class="flex items-center justify-center gap-4">
                            <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
                            <p class="uppercase tracking-[0.25em] text-sm" style="color: {{ $introTitle }};">
                                {{ $introTitleText }}
                            </p>
                            <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
                        </div>
                    @endif

                    @if($introTextText)
                        <p class="px-6 sm:px-12 text-base leading-7" style="color: {{ $introText }};">
                            {{ $introTextText }}
                        </p>
                    @endif
                </div>
            </div>
        </section>

        {{-- DATE ROW (dan / mesec / broj / godina / vreme) --}}
@if($event->date_at)
    @php
        $dayName = $event->date_at->locale('sr')->isoFormat('dddd');
        $monthName = $event->date_at->locale('sr')->isoFormat('MMMM');
        $dayNum = $event->date_at->format('d');
        $year = $event->date_at->format('Y');
        $time = $event->date_at->format('H:i');
    @endphp

    <section style="background: {{ $dateBg }};">
        <div class="mx-auto max-w-5xl px-6 py-10">
            <div class="grid grid-cols-3 items-center gap-4 text-center">

                {{-- LEFT: DAN (linija / tekst / linija) --}}
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
                    <div class="uppercase tracking-widest text-xs" style="color: {{ $dateTextSecondary }};">
                        {{ $dayName }}
                    </div>
                    <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
                </div>

                {{-- CENTER --}}
                <div class="space-y-1">
                    <div class="uppercase tracking-widest text-xs" style="color: {{ $dateTextSecondary }};">
                        {{ $monthName }}
                    </div>

                    <div class="text-6xl font-semibold leading-none" style="color: {{ $dateTextPrimary }};">
                        {{ (int) $dayNum }}
                    </div>

                    <div class="text-sm" style="color: {{ $dateTextSecondary }};">
                        {{ $year }}
                    </div>
                </div>

                {{-- RIGHT: SAT (linija / tekst / linija) --}}
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
                    <div class="text-sm" style="color: {{ $dateTextPrimary }};">
                        {{ $time }}
                    </div>
                    <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
                </div>

            </div>
        </div>
    </section>
@endif



       {{-- LOCATION --}}
<section style="background: {{ $locationBg }};">
    <div class="mx-auto max-w-5xl py-10">
        <div class="text-center space-y-4">

            {{-- marker PNG --}}
            @if($event->location_marker_path)
                <img
                    src="{{ asset('storage/'.$event->location_marker_path) }}"
                    alt="Marker"
                    class="mx-auto h-10 w-10 object-contain"
                />
            @endif

            {{-- tekst --}}
            @if($event->location_name || $event->location_address)
                <div class="space-y-1">
                    @if($event->location_name)
                        <div class="text-base font-semibold" style="color: {{ $locationText }};">
                            {{ $event->location_name }}
                        </div>
                    @endif

                    @if($event->location_address)
                        <div class="text-sm" style="color: {{ $locationSubText }};">
                            {{ $event->location_address }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- dugme mapa (opciono) --}}
            @if($event->location_url)
                <a
                    href="{{ $event->location_url }}"
                    target="_blank"
                    class="inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-semibold"
                    style="background: {{ $btnBg }}; color: {{ $btnText }};"
                >
                    Otvori mapu
                </a>
            @endif
        </div>

        {{-- slika ispod teksta --}}
        @if($event->location_image_path)
            <div class="mt-8 overflow-hidden">
                <img
                    src="{{ asset('storage/'.$event->location_image_path) }}"
                    alt="Lokacija"
                    class="w-full object-cover"
                />
            </div>
        @endif
    </div>
</section>



        {{-- RSVP --}}
        <section style="background: {{ $rsvpBg }};">
    <div class="mx-auto max-w-5xl px-6 py-12">
        {{-- Title (kao na slici) --}}
        <div class="text-center space-y-2">
            <h2 class="uppercase tracking-[0.25em] text-sm" style="color: {{ $rsvpTitleColor }};">
                {!! nl2br(e($rsvpTitleText)) !!}
            </h2>

            @if($rsvpSubText)
                <p class="uppercase tracking-[0.25em] text-xs" style="color: {{ $rsvpSubColor }};">
                    {{ $rsvpSubText }}
                </p>
            @endif
        </div>

        {{-- Card --}}
        <div class="mx-auto mt-8 max-w-xl rounded-lg p-6 sm:p-8"
             style="background: {{ $rsvpCardBg }}; border: 1px solid {{ $rsvpCardBorder }};">
            
            @if($sent)
                <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800 text-center">
                    Hvala! Potvrda je poslata. ✅
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-5">
                {{-- Ime --}}
                <div>
                    <label class="block text-sm font-medium" style="color: {{ $rsvpLabelColor }};">
                        Ime i prezime
                    </label>
                    <input
                        wire:model.defer="name"
                        class="mt-2 w-full px-4 py-3 outline-none"
                        style="background: {{ $rsvpInputBg }}; border: 1px solid {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
                    />
                    @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                {{-- Telefon --}}
                <div>
                    <label class="block text-sm font-medium" style="color: {{ $rsvpLabelColor }};">
                        Broj mobitela
                    </label>
                    <input
                        wire:model.defer="phone"
                        class="mt-2 w-full px-4 py-3 outline-none"
                        style="background: {{ $rsvpInputBg }}; border: 1px solid {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
                    />
                    @error('phone') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                {{-- Radio status (kao na slici, levo poravnato) --}}
                <div class="space-y-3 pt-1">
                    @foreach([ 'yes' => 'Dolazim sam', 'maybe' => 'Dolazim u dvoje', 'no' => 'Ne dolazim' ] as $k => $label)
                        <label class="flex items-center gap-3 text-sm cursor-pointer" style="color: {{ $rsvpRadioAccent }};">
                            <input
                                type="radio"
                                name="rsvp_status"
                                value="{{ $k }}"
                                wire:model.live="status"
                                class="h-4 w-4"
                                style="accent-color: {{ $rsvpRadioAccent }};"
                            >
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                    @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                {{-- Submit (manje dugme, kao na slici) --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold shadow-sm"
                        style="background: {{ $rsvpBtnBg }}; color: {{ $rsvpBtnText }};"
                    >
                        Pošalji
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="mt-10 text-center space-y-3">
            @if($event->footer_logo_path)
                <img src="{{ asset('storage/'.$event->footer_logo_path) }}" alt="Logo" class="mx-auto max-h-16">
            @endif

            @if($footerBrand)
                <p class="text-xs uppercase tracking-widest" style="color: {{ $rsvpTitleColor }};">
                    {{ $footerBrand }}
                </p>
            @endif
        </div>
    </div>
</section>
    </div>
</div>

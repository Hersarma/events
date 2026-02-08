{{-- resources/views/livewire/invite/show.blade.php --}}
<div
    x-data="{
        opened: false,
        requiresClick: @js($event->hero_type === 'video' && (bool) $event->hero_video_path),

        lock() {
            document.documentElement.classList.add('overflow-hidden');
        },

        unlock() {
            document.documentElement.classList.remove('overflow-hidden');
        },

        open() {
            this.opened = true;
            this.unlock();

            const v = document.getElementById('inviteVideo');
            if (v) {
                const p = v.play();
                if (p && p.catch) p.catch(() => {});
            }

            // 👇 OVDE se aktivira animacija teksta
            this.$nextTick(() => this.revealTextInit());
        },

        revealTextInit() {
            const els = document.querySelectorAll('[data-reveal-text]');

            if (!('IntersectionObserver' in window)) {
                els.forEach(el => el.classList.add('is-visible'));
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: '0px 0px -10% 0px'
            });

            els.forEach(el => io.observe(el));
        },

        init() {
            if (this.requiresClick) {
                this.lock();
                this.opened = false;
            } else {
                // 👇 ako je SLIKA – odmah otključaj + animiraj
                this.unlock();
                this.opened = true;
                this.$nextTick(() => this.revealTextInit());
            }
        }
    }"
    x-init="init()"
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
        $rsvpThirdText  = data_get($c, 'rsvp_third', '');
        $rsvpThirdColor = data_get($s, 'rsvp.third_color', '#FFFFFF');

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
        $footerTextColor = data_get($s, 'footer.text_color', $rsvpTitleColor); // fallback
        $footerLogo = 'images/footer-logo.png'; // public/images/footer-logo.png
        

        @endphp
<div class="min-h-screen bg-neutral-100 lg:flex lg:items-center lg:justify-center">
    <div class="w-full bg-white lg:w-[460px] lg:rounded-[32px] lg:overflow-hidden lg:shadow-xl">
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
                muted
            >
                <source src="{{ asset('storage/'.$event->hero_video_path) }}" type="video/mp4">
            </video>
        @endif

        {{-- klik bilo gde: otključaj skrol + (ponovo) pusti video --}}
        <div x-show="requiresClick && !opened" class="absolute inset-0">
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
                        <p  class="px-6 sm:px-12 text-base leading-7" style="color: {{ $introText }};">
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
    <div class="flex items-center justify-center gap-3 text-center">
      
      {{-- LEFT --}}
      <div class="flex flex-col items-center justify-center gap-2 -mr-12">
        <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
        <div class="uppercase tracking-widest" style="color: {{ $dateTextSecondary }};">
          {{ $dayName }}
        </div>
        <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
      </div>

      {{-- CENTER --}}
      <div class="w-40 space-y-1">
        <div class="uppercase tracking-widest pb-2" style="color: {{ $dateTextSecondary }};">
          {{ $monthName }}
        </div>

        <div class="text-4xl font-semibold leading-none" style="color: {{ $dateTextPrimary }};">
          {{ (int) $dayNum }}
        </div>

        <div class="pt-2" style="color: {{ $dateTextSecondary }};">
          {{ $year }}
        </div>
      </div>

      {{-- RIGHT --}}
      <div class="flex flex-col items-center justify-center gap-2 -ml-12">
        <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
        <div class="" style="color: {{ $dateTextPrimary }};">
          {{ $time }}
        </div>
        <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
      </div>

    </div>
  </div>
</section>


@endif



      {{-- LOCATION (marker + naziv + adresa | mapa klikabilna) --}}
<section class="w-full">

    @php
        $h = 'h-[150px]'; // ista visina za obe strane
    @endphp

    <div class="grid grid-cols-1">

        {{-- LEVO: MARKER + TEKST --}}
        <div
            class="{{ $h }} flex items-center justify-center text-center px-6"
            style="background: {{ $locationBg }};"
        >
            <div class="space-y-4">

                {{-- MARKER (static image) --}}
                <img
                    src="{{ asset('images/location-pin.png') }}"
                    alt="Lokacija"
                    class="mx-auto h-12 w-12 object-contain opacity-90"
                />

                {{-- ADRESA U JEDNOJ LINIJI: ULICA → GRAD --}}
                    @if($event->location_address || $event->location_name)
                        <div
                            class="uppercase text-[11px] tracking-[0.15em] sm:text-sm"
                            style="color: {{ $locationText }};"
                        >
                            {{ collect([$event->location_address, $event->location_name])
                                ->filter()
                                ->implode(', ') }}
                        </div>
                    @endif



            </div>
        </div>

        {{-- DESNO: MAPA (KLIK → GOOGLE MAPS) --}}
        @if($event->map_image_path && $event->location_url)
            <a
                href="{{ $event->location_url }}"
                target="_blank"
                class="{{ $h }} block overflow-hidden group relative"
            >
                <img
                    src="{{ asset('storage/'.$event->map_image_path) }}"
                    alt="Mapa"
                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                />

                {{-- diskretan overlay na hover --}}
                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
        @elseif($event->map_image_path)
            <div class="{{ $h }} overflow-hidden">
                <img
                    src="{{ asset('storage/'.$event->map_image_path) }}"
                    alt="Mapa"
                    class="h-full w-full object-cover"
                />
            </div>
        @endif

    </div>
</section>





        {{-- RSVP --}}
        <section style="background: {{ $rsvpBg }};">
    <div class="mx-auto max-w-5xl px-6 py-12">
        {{-- Title (kao na slici) --}}
        <div class="text-center">
            <h2 class="uppercase tracking-[0.15em] text-sm" style="color: {{ $rsvpTitleColor }};">
                {!! nl2br(e($rsvpTitleText)) !!}
            </h2>

            @if($rsvpSubText)
                <p class="uppercase tracking-[0.15em] text-sm" style="color: {{ $rsvpSubColor }};">
                    {{ $rsvpSubText }}
                </p>
            @endif
            @if($rsvpThirdText)
                <p class="uppercase tracking-[0.15em] text-sm" style="color: {{ $rsvpThirdColor }};">
                    {{ $rsvpThirdText }}
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
<div class="mt-10 text-center space-y-2">
    {{-- LOGO (uvek iz images foldera) --}}
    <img
        src="{{ asset('images/logo.png') }}"
        alt="Logo"
        class="mx-auto h-14 w-14 object-contain rounded-full p-2 ring-1 ring-emerald-300/20"
    >

    {{-- FOOTER TEKST --}}
    <p class="text-xs uppercase tracking-widest"
       style="color: {{ $footerTextColor }};">
        invitations by
    </p>

    <p class="text-xs uppercase tracking-widest"
       style="color: {{ $footerTextColor }};">
        dianas garden studio
    </p>
</div>

    </div>
</section>
    </div>
    <style>
  .reveal-text {
    opacity: 0;
    transform: translateY(8px);
    transition: opacity .6s ease, transform .6s ease;
    will-change: opacity, transform;
  }

  .reveal-text.is-visible {
    opacity: 1;
    transform: translateY(0);
  }
</style>
</div>
</div>
</div>

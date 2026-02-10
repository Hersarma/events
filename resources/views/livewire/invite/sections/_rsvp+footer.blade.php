{{-- resources/views/livewire/invite/sections/_rsvp.blade.php --}}
@php
    $rsvpTitleText = data_get($c, 'rsvp_title', '');
    $rsvpSubText   = data_get($c, 'rsvp_subtitle', '');
    $rsvpThirdText = data_get($c, 'rsvp_third', '');

    $rsvpBg         = data_get($s, 'rsvp.bg', '#6F7C72');
    $rsvpTitleColor = data_get($s, 'rsvp.title_color', '#FFFFFF');
    $rsvpSubColor   = data_get($s, 'rsvp.subtitle_color', '#FFFFFF');
    $rsvpThirdColor = data_get($s, 'rsvp.third_color', '#FFFFFF');

    $rsvpCardBg     = data_get($s, 'rsvp.card_bg', '#D8CDBD');
    $rsvpCardBorder = data_get($s, 'rsvp.card_border', 'rgba(255,255,255,0.15)');

    $rsvpLabelColor = data_get($s, 'rsvp.label_color', '#FFFFFF');

    $rsvpInputBg     = data_get($s, 'rsvp.input_bg', '#FFFFFF');
    $rsvpInputBorder = data_get($s, 'rsvp.input_border', '#CFC6B7');
    $rsvpInputText   = data_get($s, 'rsvp.input_text', '#111827');

    $rsvpRadioAccent = data_get($s, 'rsvp.radio_accent', '#6F7C72');

    $rsvpBtnBg   = data_get($s, 'rsvp.button_bg', '#6F7C72');
    $rsvpBtnText = data_get($s, 'rsvp.button_text', '#FFFFFF');
    $rsvpTitleColor = data_get($s, 'rsvp.title_color', '#FFFFFF');
    $footerTextColor = data_get($s, 'footer.text_color', $rsvpTitleColor);
@endphp

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
             <div wire:key="rsvp-form-{{ $formKey }}">
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
                                wire:model.defer="status"
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
        </div>

        
        {{-- Footer --}}
<div class="mt-10 text-center">
    {{-- LOGO (uvek iz images foldera) --}}
    <img
        src="{{ asset('images/logo.png') }}"
        alt="Logo"
        class="mx-auto h-14 w-14 object-contain rounded-full p-2 ring-1 ring-emerald-300/20"
    >

    {{-- FOOTER TEKST --}}
    <p class="text-xs uppercase tracking-widest pt-2"
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

{{-- resources/views/livewire/invite/sections/_rsvp.blade.php --}}
@php
$rsvpTitleText = data_get($c, 'rsvp_title', '');
$rsvpSubText   = data_get($c, 'rsvp_subtitle', '');
$rsvpThirdText = data_get($c, 'rsvp_third', '');
$nameLabel  = data_get($c, 'rsvp_name_label', 'Ime i prezime');
$phoneLabel = data_get($c, 'rsvp_phone_label', 'Broj mobitela');
$optYes    = data_get($c, 'rsvp_opt_yes', 'Dolazim sam');
$optCouple = data_get($c, 'rsvp_opt_couple', data_get($c, 'rsvp_opt_maybe', 'Dolazim u dvoje'));
$optNo     = data_get($c, 'rsvp_opt_no', 'Ne dolazim');

$btnLabel = data_get($c, 'rsvp_btn_label', 'Pošalji');
$btnLoading = data_get($c, 'rsvp_btn_loading', 'Šaljem...');
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
            <h2 class="uppercase tracking-widest text-base" style="color: {{ $rsvpTitleColor }};">
            {!! nl2br(e($rsvpTitleText)) !!}
            </h2>
            @if($rsvpSubText)
            <p class="uppercase tracking-widest text-base" style="color: {{ $rsvpSubColor }};">
                {{ $rsvpSubText }}
            </p>
            @endif
            @if($rsvpThirdText)
            <p class="uppercase tracking-widest text-base" style="color: {{ $rsvpThirdColor }};">
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
                        <label class="block text-base font-medium" style="color: {{ $rsvpLabelColor }};">
                            {{ $nameLabel }}
                        </label>
                        <input
                        wire:model.defer="name"
                        class="mt-2 w-full px-4 py-3 outline-none"
                        style="background: {{ $rsvpInputBg }}; border: 1px solid {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
                        />
                        @error('name') <p class="mt-1 text-base text-red-700">{{ $message }}</p> @enderror
                    </div>
                    {{-- Telefon --}}
                    <div>
                        <label class="block text-base font-medium" style="color: {{ $rsvpLabelColor }};">
                            {{ $phoneLabel }}
                        </label>
                        <input
                        wire:model.defer="phone"
                        class="mt-2 w-full px-4 py-3 outline-none"
                        style="background: {{ $rsvpInputBg }}; border: 1px solid {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
                        />
                        @error('phone') <p class="mt-1 text-base text-red-700">{{ $message }}</p> @enderror
                    </div>
                    {{-- Radio status (kao na slici, levo poravnato) --}}
                    <div class="space-y-3 pt-1">
                        @foreach([ 'yes' => $optYes, 'couple' => $optCouple, 'no' => $optNo ] as $k => $label)
                        <label class="flex items-center gap-3 text-base cursor-pointer select-none"
                            style="color: {{ $rsvpRadioAccent }};">
                            <span class="relative h-4 w-4 inline-flex items-center justify-center">
                                {{-- pravi radio (nevidljiv, ali klik radi) --}}
                                <input
                                type="radio"
                                name="rsvp_status"
                                value="{{ $k }}"
                                wire:model.defer="status"
                                class="peer absolute inset-0 h-full w-full opacity-0 cursor-pointer"
                                />
                                {{-- custom ring --}}
                                <span
                                    class="h-4 w-4 rounded-full transition"
                                    style="border: 1.5px solid {{ $rsvpRadioAccent }};"
                                ></span>
                                {{-- dot --}}
                                <span
                                    class="absolute h-2 w-2 rounded-full opacity-0 scale-75 transition
                                    peer-checked:opacity-100 peer-checked:scale-100"
                                    style="background: {{ $rsvpRadioAccent }};"
                                ></span>
                            </span>
                            <span>{{ $label }}</span>
                        </label>
                        @endforeach
                        @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    {{-- Submit (manje dugme, kao na slici) --}}
                    <div class="pt-2">
                        <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                        class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold shadow-sm disabled:opacity-60"
                        style="background: {{ $rsvpBtnBg }}; color: {{ $rsvpBtnText }};"
                        >
                        <span wire:loading.remove wire:target="submit">{{ $btnLabel }}</span>
                        <span wire:loading wire:target="submit">{{ $btnLoading }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-10 text-center">
            {{-- LOGO (uvek iz images foldera) --}}
            <img
            src="{{ asset('images/logo2.png') }}"
            alt="Logo"
            class="mx-auto h-16 w-16 object-contain p-2"
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
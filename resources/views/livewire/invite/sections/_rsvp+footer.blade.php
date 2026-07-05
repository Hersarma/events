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
            style="background: {{ $rsvpCardBg }}; border: 1px solid {{ $rsvpCardBg }};">
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

    <div
        class="mt-2 flex overflow-hidden"
        style="background: {{ $rsvpInputBg }}; border: 1px solid {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
    >
        <div
            class="flex items-center border-r px-4 py-3 text-base font-semibold"
            style="border-color: {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
        >
            +
        </div>

        <input
            wire:model.defer="phone_country"
            type="text"
            inputmode="numeric"
            class="rsvp-phone-input w-24 border-0 border-r px-3 py-3 text-base outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus:shadow-none"
            style="background: {{ $rsvpInputBg }}; border-right-color: {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
            placeholder="pozivni"
        />

        <input
            wire:model.defer="phone_number"
            type="text"
            inputmode="tel"
            autocomplete="tel"
            class="rsvp-phone-input min-w-0 flex-1 border-0 px-3 py-3 text-base outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus:shadow-none"
            style="background: {{ $rsvpInputBg }}; color: {{ $rsvpInputText }};"
            placeholder="broj telefona"
        />
    </div>

    <p class="mt-2 text-sm" style="color: {{ $rsvpLabelColor }};">
        Unesite pozivni broj države i broj telefona. Primjer: +385 91 123 4567.
    </p>

    @error('phone_country')
        <p class="mt-1 text-base text-red-700">{{ $message }}</p>
    @enderror

    @error('phone_number')
        <p class="mt-1 text-base text-red-700">{{ $message }}</p>
    @enderror
    @if($successMessage)
    <p class="mt-2 text-base font-medium text-green-700">
        {{ $successMessage }}
    </p>
@endif
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

        @if($event->enable_qr_codes ?? false)
            <div class="mx-auto mt-6 max-w-xl rounded-lg p-6 sm:p-8"
                style="background: {{ $rsvpCardBg }}; border: 1px solid {{ $rsvpCardBg }};">
                @if($qrDownloadUrl)
                    <div class="text-center">
                        <div class="text-base font-semibold" style="color: {{ $rsvpLabelColor }};">
                            Vaš QR kod je spreman.
                        </div>
                        @if($qrLookupMessage)
                            <p class="mt-1 text-sm" style="color: {{ $rsvpLabelColor }};">
                                {{ $qrLookupMessage }}
                            </p>
                        @endif
                        <a
                            href="{{ $qrDownloadUrl }}"
                            class="mt-4 inline-flex items-center justify-center px-6 py-3 text-base font-semibold shadow-sm"
                            style="background: {{ $rsvpBtnBg }}; color: {{ $rsvpBtnText }};"
                        >
                            Preuzmi QR kod
                        </a>
                    </div>
                @else
                    <div class="text-center">
                        <div class="text-base font-semibold" style="color: {{ $rsvpLabelColor }};">
                            Već ste potvrdili dolazak?
                        </div>
                        <p class="mt-1 text-sm" style="color: {{ $rsvpLabelColor }};">
                            Unesite broj telefona i ponovo preuzmite QR kod.
                        </p>
                    </div>

                    <div class="mt-5">
                        <label class="block text-base font-medium" style="color: {{ $rsvpLabelColor }};">
                            {{ $phoneLabel }}
                        </label>

                        <div
                            class="mt-2 flex overflow-hidden"
                            style="background: {{ $rsvpInputBg }}; border: 1px solid {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
                        >
                            <div
                                class="flex items-center border-r px-4 py-3 text-base font-semibold"
                                style="border-color: {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
                            >
                                +
                            </div>

                            <input
                                wire:model.defer="qr_phone_country"
                                type="text"
                                inputmode="numeric"
                                class="rsvp-phone-input w-24 border-0 border-r px-3 py-3 text-base outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus:shadow-none"
                                style="background: {{ $rsvpInputBg }}; border-right-color: {{ $rsvpInputBorder }}; color: {{ $rsvpInputText }};"
                                placeholder="pozivni"
                            />

                            <input
                                wire:model.defer="qr_phone_number"
                                type="text"
                                inputmode="tel"
                                autocomplete="tel"
                                class="rsvp-phone-input min-w-0 flex-1 border-0 px-3 py-3 text-base outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus:shadow-none"
                                style="background: {{ $rsvpInputBg }}; color: {{ $rsvpInputText }};"
                                placeholder="broj telefona"
                            />
                        </div>

                        @error('qr_phone_country')
                            <p class="mt-1 text-base text-red-700">{{ $message }}</p>
                        @enderror

                        @error('qr_phone_number')
                            <p class="mt-1 text-base text-red-700">{{ $message }}</p>
                        @enderror

                        <button
                            type="button"
                            wire:click="retrieveQr"
                            wire:loading.attr="disabled"
                            wire:target="retrieveQr"
                            class="mt-4 inline-flex items-center justify-center px-6 py-3 text-base font-semibold shadow-sm disabled:opacity-60"
                            style="background: {{ $rsvpBtnBg }}; color: {{ $rsvpBtnText }};"
                        >
                            <span wire:loading.remove wire:target="retrieveQr">Pronađi QR kod</span>
                            <span wire:loading wire:target="retrieveQr">Tražim...</span>
                        </button>
                    </div>
                @endif
            </div>
        @endif
        
        {{-- Footer --}}
        <div class="mt-10 text-center">
    {{-- LOGO --}}
    <a
        href="https://www.instagram.com/dianasgardendesign?igsh=eGg2ZDVzcGs5N201"
        target="_blank"
        rel="noopener noreferrer"
        class="inline-block"
    >
        <img
            src="{{ asset('images/logo2.png') }}"
            alt="Diana's Garden Design Instagram"
            class="mx-auto h-16 w-16 object-contain p-2"
        >
    </a>

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

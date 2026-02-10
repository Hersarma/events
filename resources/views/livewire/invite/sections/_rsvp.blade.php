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
@endphp

<section style="background: {{ $rsvpBg }};">
    <div class="mx-auto max-w-5xl px-6 py-12">

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

        <div class="mx-auto mt-8 max-w-xl rounded-lg p-6 sm:p-8"
             style="background: {{ $rsvpCardBg }}; border: 1px solid {{ $rsvpCardBorder }};">

            <div wire:key="rsvp-form-{{ $formKey }}">
                <form wire:submit.prevent="submit" class="space-y-5">

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

                    <div class="pt-2">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold shadow-sm disabled:opacity-60"
                            style="background: {{ $rsvpBtnBg }}; color: {{ $rsvpBtnText }};"
                        >
                            <span wire:loading.remove wire:target="submit">Pošalji</span>
                            <span wire:loading wire:target="submit">Šaljem...</span>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

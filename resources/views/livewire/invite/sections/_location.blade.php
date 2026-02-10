{{-- resources/views/livewire/invite/sections/_location.blade.php --}}
@php
$locationBg = data_get($s, 'location.bg', '#ffffff');
$locationText = data_get($s, 'location.text', '#111827');
@endphp
<section class="w-full" style="background: {{ $locationBg }};">
    <div class="mx-auto max-w-5xl px-6 {{ $py }}">
        <div class="{{ $h }} flex items-center justify-center text-center">
            <div class="space-y-4">
                <img
                src="{{ asset('images/location-pin.png') }}"
                alt="Lokacija"
                class="mx-auto h-12 w-12 object-contain opacity-90"
                />
                @if($event->location_address || $event->location_name)
                <div
                    class="uppercase text-[11px] tracking-[0.15em] sm:text-sm"
                    style="color: {{ $locationText }};"
                    >
                    {{ collect([$event->location_address, $event->location_name])->filter()->implode(', ') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
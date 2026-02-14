{{-- resources/views/livewire/invite/sections/_location.blade.php --}}
@php
$locationBg = data_get($s, 'location.bg', '#ffffff');
$locationText = data_get($s, 'location.text', '#111827');
@endphp
@if($event->map_image_path)
<section class="w-full" style="background: {{ $locationBg }};">
    <div class="mx-auto max-w-5xl px-6 py-12">
        <div class="{{ $h }} flex items-center justify-center text-center">
            <div class="space-y-4">
                <img
                src="{{ asset('storage/'.$event->location_marker_path) }}"
                alt="Lokacija"
                class="mx-auto h-12 w-12 object-contain opacity-90"
                />
                <div class="py-0.5"></div>
                @if($event->location_address || $event->location_name)
                <div
                    class="uppercase tracking-widest"
                    style="color: {{ $locationText }};"
                    >
                    {{ collect([$event->location_address, $event->location_name])->filter()->implode(', ') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
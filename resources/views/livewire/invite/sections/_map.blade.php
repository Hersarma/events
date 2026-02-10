{{-- resources/views/livewire/invite/sections/_map.blade.php --}}
@php
    $locationBg = data_get($s, 'location.bg', '#ffffff');
@endphp

@if($event->map_image_path)
    <section class="w-full" style="background: {{ $locationBg }};">
        <div class="{{ $himg }} w-full relative overflow-hidden">

            @if($event->location_url)
                <a
                    href="{{ $event->location_url }}"
                    target="_blank"
                    class="absolute inset-0 block group"
                >
                    <img
                        src="{{ asset('storage/'.$event->map_image_path) }}"
                        alt="Mapa"
                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>
            @else
                <img
                    src="{{ asset('storage/'.$event->map_image_path) }}"
                    alt="Mapa"
                    class="h-full w-full object-cover"
                />
            @endif

        </div>
    </section>
@endif

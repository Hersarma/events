{{-- resources/views/livewire/invite/sections/_location.blade.php --}}
@php
  $locationBg = data_get($s, 'location.bg', '#ffffff');
  $locationText = data_get($s, 'location.text', '#111827');

  $markerSrc = $event->location_marker_path
    ? (str_contains($event->location_marker_path, '://')
        ? $event->location_marker_path
        : asset('storage/'.$event->location_marker_path))
    : null;
@endphp

@if($markerSrc || $event->location_address || $event->location_name)
<section class="w-full" style="background: {{ $locationBg }};">
  <div class="mx-auto max-w-5xl px-6 py-12">
    <div class="{{ $h }} flex items-center justify-center text-center">
      <div class="space-y-4">
        @if($markerSrc)
          <img
            src="{{ $markerSrc }}"
            alt="Lokacija"
            class="mx-auto h-12 w-12 object-contain opacity-90"
          />
        @endif

        <div class="py-0.5"></div>

        @if($event->location_address || $event->location_name)
          <div class="uppercase tracking-widest" style="color: {{ $locationText }};">
            {{ collect([$event->location_address, $event->location_name])->filter()->implode(', ') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endif

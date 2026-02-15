@php
  $locationBg = data_get($s, 'location.bg', '#ffffff');
  $locationText = data_get($s, 'location.text', '#111827');

  $markerSrc = $event->location_marker_path
    ? (str_contains($event->location_marker_path, '://')
        ? $event->location_marker_path
        : asset('storage/'.$event->location_marker_path))
    : null;

  $hasLocation = $markerSrc || $event->location_address || $event->location_name;
@endphp

<section class="w-full" style="background: {{ $locationBg }};">
  <div class="mx-auto max-w-5xl px-6 py-12">
    <div class="{{ $h }} flex items-center justify-center text-center">
      <div class="space-y-4">

        @if($hasLocation)

            @if($markerSrc)
              <img
                src="{{ $markerSrc }}"
                alt="Lokacija"
                class="mx-auto h-12 w-12 object-contain opacity-90"
              />
            @endif

            @if($event->location_address || $event->location_name)
              <div class="uppercase tracking-widest" style="color: {{ $locationText }};">
                {{ collect([$event->location_address, $event->location_name])->filter()->implode(', ') }}
              </div>
            @endif

        @else

            {{-- ✅ Placeholder --}}
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full border border-gray-300 opacity-50">
              📍
            </div>

            <div class="uppercase tracking-widest opacity-60" style="color: {{ $locationText }};">
              Adresa lokacije
            </div>

            <div class="text-sm opacity-50" style="color: {{ $locationText }};">
              Ovde će se prikazivati naziv i adresa događaja
            </div>

        @endif

      </div>
    </div>
  </div>
</section>

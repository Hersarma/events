{{-- resources/views/livewire/invite/sections/_hero.blade.php --}}
<section class="relative h-screen w-full overflow-hidden">

  @if($event->hero_type === 'image' && $event->hero_image_path)
    <img
      src="{{ asset('storage/'.$event->hero_image_path) }}"
      alt="Pozivnica"
      class="h-full w-full object-cover"
    />
  @else

    {{-- VIDEO LAYER WRAPPER --}}
    <div class="absolute inset-0">

      {{-- VIDEO 1 (koverta) --}}
      @if($event->hero_video_path)
        <video
          id="inviteVideo"
          class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500"
          :class="(opened && afterFirst) ? 'opacity-0' : 'opacity-100'"
          playsinline
          preload="metadata"
        >
          <source src="{{ asset('storage/'.$event->hero_video_path) }}#t=0.001" type="video/mp4">
        </video>
      @endif

      {{-- VIDEO 2 (posle prve, loop) --}}
      @if($event->hero_video_2_path)
        <video
          id="inviteVideo2"
          x-cloak
          class="absolute inset-0 h-full w-full object-cover transition-opacity duration-700"
          :class="(opened && afterFirst) ? 'opacity-100' : 'opacity-0'"
          playsinline
          preload="metadata"
          loop
        >
          <source src="{{ asset('storage/'.$event->hero_video_2_path) }}#t=0.001" type="video/mp4">
        </video>
      @endif

      {{-- mali “veil” za mekši prelaz (opciono ali mnogo pomaže) --}}
      <div
        class="absolute inset-0 pointer-events-none transition-opacity duration-700 bg-black/10"
        :class="(opened && afterFirst) ? 'opacity-0' : 'opacity-0'"
      ></div>

    </div>

    {{-- Scroll hint --}}
    <div
      x-show="opened"
      x-transition.opacity
      class="pointer-events-none absolute bottom-20 left-0 right-0 flex justify-center"
    >
      <svg class="h-8 w-8 animate-bounce text-white/90" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>

  @endif

  {{-- klik bilo gde --}}
  <div x-show="requiresClick && !opened" @click.stop.prevent="open()" class="absolute inset-0">
    <button
      type="button"
      class="absolute inset-0 cursor-pointer"
      aria-label="Otvori pozivnicu"
      @click="open()"
    ></button>
  </div>
</section>

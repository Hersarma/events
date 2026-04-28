{{-- resources/views/livewire/invite/sections/_hero.blade.php --}}
<section class="relative h-screen w-full overflow-hidden">

  @if($event->hero_video_path)
    <div class="absolute inset-0">

      {{-- VIDEO 1: intro / koverta --}}
      <video
        id="inviteVideo"
        class="absolute inset-0 h-full w-full object-cover transition-opacity duration-700"
        :class="afterFirst ? 'opacity-0' : 'opacity-100'"
        playsinline
        preload="metadata"
      >
        <source src="{{ asset('storage/'.$event->hero_video_path) }}#t=0.001" type="video/mp4">
      </video>

      {{-- POSLE PRVOG: ili video 2 ili slika --}}
      @if($event->hero_type === 'video' && $event->hero_video_2_path)
        <video
          id="inviteVideo2"
          x-cloak
          class="absolute inset-0 h-full w-full object-cover transition-opacity duration-700"
          :class="afterFirst ? 'opacity-100' : 'opacity-0'"
          playsinline
          preload="metadata"
          loop
        >
          <source src="{{ asset('storage/'.$event->hero_video_2_path) }}#t=0.001" type="video/mp4">
        </video>
      @elseif($event->hero_type === 'image' && $event->hero_image_path)
        <img
          src="{{ asset('storage/'.$event->hero_image_path) }}"
          alt="Pozivnica"
          class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-700"
          :class="afterFirst ? 'opacity-100' : 'opacity-0'"
        />
      @endif

    </div>

  @elseif($event->hero_type === 'image' && $event->hero_image_path)
    {{-- fallback: stara varijanta samo sa slikom --}}
    <img
      src="{{ asset('storage/'.$event->hero_image_path) }}"
      alt="Pozivnica"
      class="h-full w-full object-cover"
    />

  @elseif($event->hero_type === 'video' && $event->hero_video_2_path)
    {{-- fallback ako nekad nema intro videa, a postoji drugi video --}}
    <video
      id="inviteVideo2"
      class="h-full w-full object-cover"
      playsinline
      preload="metadata"
      loop
      autoplay
    >
      <source src="{{ asset('storage/'.$event->hero_video_2_path) }}#t=0.001" type="video/mp4">
    </video>
  @endif

  {{-- Scroll hint --}}
  <div
  x-show="opened && afterFirst"
  x-transition.opacity
  class="pointer-events-none absolute bottom-20 left-0 right-0 flex flex-col items-center justify-center"
>
  <span class="mb-1 block text-xs font-bold uppercase tracking-[0.25em] text-white/80">
  scroll
</span>

  <svg class="h-8 w-8 animate-bounce text-white/90" viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
  </svg>
</div>

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
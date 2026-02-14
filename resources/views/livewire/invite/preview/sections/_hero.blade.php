{{-- resources/views/livewire/invite/sections/_hero.blade.php --}}
@php
  $videoSrc = $event->hero_video_path
    ? (str_contains($event->hero_video_path, '://')
        ? $event->hero_video_path
        : asset('storage/'.$event->hero_video_path))
    : null;

  $imageSrc = $event->hero_image_path
    ? (str_contains($event->hero_image_path, '://')
        ? $event->hero_image_path
        : asset('storage/'.$event->hero_image_path))
    : null;
@endphp

<section class="relative h-screen w-full overflow-hidden">
  @if($event->hero_type === 'image' && $imageSrc)
    <img src="{{ $imageSrc }}" alt="Pozivnica" class="h-full w-full object-cover" />
  @elseif($videoSrc)
    <video
      id="inviteVideo"
      class="h-full w-full object-cover"
      playsinline
      preload="metadata"
      muted
    >
      <source src="{{ $videoSrc }}" type="video/mp4">
    </video>

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
</section>

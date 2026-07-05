@php
  // 1) video2 (loop) — preferiraj za preview
  $video2Src = $event->hero_video_2_path
    ? (str_contains($event->hero_video_2_path, '://')
        ? $event->hero_video_2_path
        : asset('storage/'.$event->hero_video_2_path))
    : null;

  $video2Key = $event->hero_video_2_path ? md5($event->hero_video_2_path) : 'no-video2';

  // 2) video1 (ako nema video2)
  $video1Src = $event->hero_video_path
    ? (str_contains($event->hero_video_path, '://')
        ? $event->hero_video_path
        : asset('storage/'.$event->hero_video_path))
    : null;

  $video1Key = $event->hero_video_path ? md5($event->hero_video_path) : 'no-video1';

  // 3) image
  $imageSrc = $event->hero_image_path
    ? (str_contains($event->hero_image_path, '://')
        ? $event->hero_image_path
        : asset('storage/'.$event->hero_image_path))
    : null;
@endphp

<section class="relative h-screen w-full overflow-hidden">
  @if($event->hero_type === 'image' && $imageSrc)

    <img src="{{ $imageSrc }}" alt="Pozivnica" class="h-full w-full object-cover" />

  @elseif($video2Src)

    {{-- ✅ Preview: odmah drugi video (loop) --}}
    <video
      wire:key="hero-video2-{{ $video2Key }}"
      class="h-full w-full object-cover"
      playsinline
      preload="metadata"
      autoplay
      loop
      muted
      x-effect="$el.muted = previewMuted; $el.volume = previewMuted ? 0 : 1"
      x-on:loadedmetadata="applyPreviewMute()"
    >
      <source src="{{ $video2Src }}" type="video/mp4">
    </video>

    {{-- Scroll hint --}}
    <div x-show="opened" x-transition.opacity class="pointer-events-none absolute bottom-20 left-0 right-0 flex justify-center">
      <svg class="h-8 w-8 animate-bounce text-white/90" viewBox="0 0 24 24" fill="none">
        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>

  @elseif($video1Src)

    {{-- fallback: prvi video --}}
    <video
      wire:key="hero-video1-{{ $video1Key }}"
      class="h-full w-full object-cover"
      playsinline
      preload="metadata"
      autoplay
      loop
      muted
      x-effect="$el.muted = previewMuted; $el.volume = previewMuted ? 0 : 1"
      x-on:loadedmetadata="applyPreviewMute()"
    >
      <source src="{{ $video1Src }}" type="video/mp4">
    </video>

    {{-- Scroll hint --}}
    <div
  x-show="opened"
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

  @else

    {{-- placeholder --}}
    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
      <div class="text-center px-6">
        <div class="text-3xl font-semibold tracking-wide mb-4">
          {{ $event->title ?? 'Naziv događaja' }}
        </div>
        <div class="text-sm opacity-80">
          Ovde će biti hero slika ili video
        </div>
      </div>
    </div>

  @endif
</section>

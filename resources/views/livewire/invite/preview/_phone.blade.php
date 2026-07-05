@props(['event'])

@php
  $c = (array) ($event->content ?? []);
  $s = (array) ($event->style ?? []);
  $h = 'h-[100px]';
  $himg = 'h-[196px]';
  $py = 'py-12';
@endphp

<div
  x-data="{
    opened: true,
    requiresClick: false,
    previewMuted: true,
    applyPreviewMute() {
      this.$root.querySelectorAll('video').forEach((video) => {
        video.muted = this.previewMuted;
        video.volume = this.previewMuted ? 0 : 1;
      });
    },
    togglePreviewMute() {
      this.previewMuted = !this.previewMuted;
      this.$nextTick(() => this.applyPreviewMute());
    }
  }"
  x-init="$nextTick(() => {
    applyPreviewMute();
    new MutationObserver(() => applyPreviewMute()).observe($root, { childList: true, subtree: true });
  })"
  class="mx-auto w-full max-w-[560px] bg-white
         lg:rounded-[32px] lg:shadow-xl lg:border
         lg:h-[calc(100vh-1.5rem)] lg:overflow-y-auto overflow-x-hidden relative"
>
  @if($event->hero_video_path || $event->hero_video_2_path)
    <div class="sticky top-3 z-50 -mb-12 flex justify-end px-3 pointer-events-none">
      <button
        type="button"
        x-on:click="togglePreviewMute()"
        class="pointer-events-auto rounded-full bg-black/70 px-3 py-2 text-xs font-semibold text-white shadow-lg backdrop-blur hover:bg-black/80"
      >
        <span x-text="previewMuted ? 'Pusti zvuk' : 'Utišaj video'"></span>
      </button>
    </div>
  @endif

  @include('livewire.invite.preview.sections._hero', ['event' => $event])
  @include('livewire.invite.preview.sections._intro', ['event' => $event, 'c' => $c, 's' => $s])
  @include('livewire.invite.preview.sections._date', ['event' => $event, 'c' => $c, 's' => $s, 'h' => $h, 'py' => $py])
  @include('livewire.invite.preview.sections._location', ['event' => $event, 's' => $s, 'h' => $h, 'py' => $py])
  @include('livewire.invite.preview.sections._map', ['event' => $event, 's' => $s, 'himg' => $himg])
  @if($event->enable_rsvp ?? true)
    @include('livewire.invite.preview.sections._rsvp+footer', ['event' => $event, 'c' => $c, 's' => $s])
  @endif
</div>

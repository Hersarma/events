@props(['event'])

@php
  $c = (array) ($event->content ?? []);
  $s = (array) ($event->style ?? []);
  $h = 'h-[100px]';
  $himg = 'h-[196px]';
  $py = 'py-12';
@endphp

<div
  x-data="{ opened: true, requiresClick: false }"
  class="mx-auto w-full max-w-[560px] bg-white
         lg:rounded-[32px] lg:shadow-xl lg:border
         lg:h-[calc(100vh-1.5rem)] lg:overflow-y-auto overflow-x-hidden"
>
  @include('livewire.invite.preview.sections._hero', ['event' => $event])
  @include('livewire.invite.preview.sections._intro', ['event' => $event, 'c' => $c, 's' => $s])
  @include('livewire.invite.preview.sections._date', ['event' => $event, 'c' => $c, 's' => $s, 'h' => $h, 'py' => $py])
  @include('livewire.invite.preview.sections._location', ['event' => $event, 's' => $s, 'h' => $h, 'py' => $py])
  @include('livewire.invite.preview.sections._map', ['event' => $event, 's' => $s, 'himg' => $himg])
  @include('livewire.invite.preview.sections._rsvp+footer', ['event' => $event, 'c' => $c, 's' => $s])
</div>

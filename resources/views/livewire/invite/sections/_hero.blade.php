{{-- resources/views/livewire/invite/sections/_hero.blade.php --}}
<section class="relative h-screen w-full overflow-hidden">
    @if($event->hero_type === 'image' && $event->hero_image_path)
    <img
    src="{{ asset('storage/'.$event->hero_image_path) }}"
    alt="Pozivnica"
    class="h-full w-full object-cover"
    />
    @elseif($event->hero_video_path)
    <video
        id="inviteVideo"
        class="h-full w-full object-cover"
        playsinline
        preload="metadata"
        muted
        >
        <source src="{{ asset('storage/'.$event->hero_video_path) }}" type="video/mp4">
    </video>
    @endif
    {{-- klik bilo gde: otključaj skrol + (ponovo) pusti video --}}
    <div x-show="requiresClick && !opened" class="absolute inset-0">
        <button
        type="button"
        class="absolute inset-0 cursor-pointer"
        aria-label="Otvori pozivnicu"
        @click="open()"
        ></button>
    </div>
</section>
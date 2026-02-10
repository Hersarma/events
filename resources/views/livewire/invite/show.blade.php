{{-- resources/views/livewire/invite/show.blade.php --}}
<div
    x-data="{
    opened: false,
    requiresClick: @js($event->hero_type === 'video' && (bool) $event->hero_video_path),
    lock() { document.documentElement.classList.add('overflow-hidden'); },
    unlock() { document.documentElement.classList.remove('overflow-hidden'); },
    open() {
    this.opened = true;
    this.unlock();
    const v = document.getElementById('inviteVideo');
    if (v) {
    const p = v.play();
    if (p && p.catch) p.catch(() => {});
    }
    this.$nextTick(() => this.revealTextInit());
    },
    revealTextInit() {
    const els = document.querySelectorAll('[data-reveal-text]');
    if (!('IntersectionObserver' in window)) {
    els.forEach(el => el.classList.add('is-visible'));
    return;
    }
    const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
    if (entry.isIntersecting) {
    entry.target.classList.add('is-visible');
    io.unobserve(entry.target);
    }
    });
    }, { threshold: 0.15, rootMargin: '0px 0px -10% 0px' });
    els.forEach(el => io.observe(el));
    },
    init() {
    if (this.requiresClick) {
    this.lock();
    this.opened = false;
    } else {
    this.unlock();
    this.opened = true;
    this.$nextTick(() => this.revealTextInit());
    }
    }
    }"
    x-init="init()"
    class="min-h-screen scroll-smooth bg-white"
    >
    @php
    $c = (array) ($event->content ?? []);
    $s = (array) ($event->style ?? []);
    // shared dimenzije (koje si već koristio)
    $h = 'h-[105px]';
    $himg = 'h-[185px]';
    $py = 'py-12';
    @endphp
    <div class="min-h-screen bg-neutral-100 lg:flex lg:items-center lg:justify-center">
        <div class="w-full bg-white lg:w-[460px] lg:rounded-[32px] lg:overflow-hidden lg:shadow-xl">
            {{-- HERO --}}
            @include('livewire.invite.sections._hero', ['event' => $event])
            {{-- CONTENT (tek nakon klika) --}}
            <div x-show="opened" x-transition.opacity.duration.300ms class="">
                @include('livewire.invite.sections._intro', ['event' => $event, 'c' => $c, 's' => $s])
                @include('livewire.invite.sections._date', [
                'event' => $event, 's' => $s, 'c' => $c, 'h' => $h, 'py' => $py
                ])
                @include('livewire.invite.sections._location', [
                'event' => $event, 's' => $s, 'h' => $h, 'py' => $py
                ])
                @include('livewire.invite.sections._map', [
                'event' => $event, 's' => $s, 'himg' => $himg
                ])
                @include('livewire.invite.sections._rsvp+footer', [
                'event' => $event, 'c' => $c, 's' => $s, 'formKey' => $formKey
                ])
                @include('livewire.invite.sections._styles')
            </div>
        </div>
    </div>
</div>
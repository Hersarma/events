{{-- resources/views/livewire/invite/show.blade.php --}}
<div
x-data="{
    afterFirst: false,
    opened: false,
    requiresClick: @js((bool) $event->hero_video_path),

    lock() { document.documentElement.classList.add('overflow-hidden'); },
    unlock() { document.documentElement.classList.remove('overflow-hidden'); },

    revealTextInit() {
      const els = document.querySelectorAll('[data-reveal-text]');
      if (!els.length) return;

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

    open() {
  this.opened = true;
  this.unlock();

  const v1 = document.getElementById('inviteVideo');
  const v2 = document.getElementById('inviteVideo2');

  this.afterFirst = false;

  if (v2) {
    v2.pause();
    v2.currentTime = 0;
    v2.muted = false;
    v2.volume = 1;
  }

  if (v1) {
    v1.currentTime = 0;
    v1.muted = false;
    v1.volume = 1;

    const onEnd = () => {
      this.afterFirst = true;

      this.$nextTick(() => {
        if (v2) {
          v2.muted = false;
          v2.volume = 1;

          const p2 = v2.play();
          if (p2 && p2.catch) p2.catch(() => {});
        }
      });

      v1.removeEventListener('ended', onEnd);
    };

    v1.addEventListener('ended', onEnd);

    const p1 = v1.play();
    if (p1 && p1.catch) p1.catch(() => {});
  }

  this.$nextTick(() => this.revealTextInit());
},

    init() {
      this.afterFirst = false;

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
    $h = 'h-[100px]';
    $himg = 'h-[196px]';
    $py = 'py-12';
    @endphp
    <div class="min-h-screen bg-neutral-100 lg:flex lg:items-center lg:justify-center">
        <div class="w-full bg-white lg:w-[560px] lg:rounded-[32px] lg:overflow-hidden lg:shadow-xl">
            {{-- HERO --}}
            @include('livewire.invite.sections._hero', ['event' => $event])
            {{-- CONTENT (tek nakon klika) --}}
            <div x-cloak x-show="opened" x-transition.opacity.duration.300ms>
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
{{-- resources/views/livewire/invite/sections/_styles.blade.php --}}
<style>
  .reveal-text {
    opacity: 0;
    transform: translateY(8px);
    transition: opacity .6s ease, transform .6s ease;
    will-change: opacity, transform;
  }

  .reveal-text.is-visible {
    opacity: 1;
    transform: translateY(0);
  }
</style>

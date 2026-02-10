{{-- resources/views/livewire/invite/sections/_footer.blade.php --}}
@php
    $rsvpTitleColor = data_get($s, 'rsvp.title_color', '#FFFFFF');
    $footerTextColor = data_get($s, 'footer.text_color', $rsvpTitleColor);
@endphp

<div class="mt-10 text-center">
    <img
        src="{{ asset('images/logo.png') }}"
        alt="Logo"
        class="mx-auto h-14 w-14 object-contain rounded-full p-2 ring-1 ring-emerald-300/20"
    >

    <p class="text-xs uppercase tracking-widest pt-2" style="color: {{ $footerTextColor }};">
        invitations by
    </p>

    <p class="text-xs uppercase tracking-widest" style="color: {{ $footerTextColor }};">
        dianas garden studio
    </p>
</div>

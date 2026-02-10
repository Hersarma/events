{{-- resources/views/livewire/invite/sections/_intro.blade.php --}}
@php
$introBg = data_get($s, 'intro.bg', '#ffffff');
$introTitle = data_get($s, 'intro.title_color', '#111827');
$introText = data_get($s, 'intro.text_color', '#374151');
$introTitleText = data_get($c, 'intro_title', '');
$introTextText  = data_get($c, 'intro_text', '');
@endphp
<section style="background: {{ $introBg }};">
    <div class="mx-auto max-w-5xl px-6 py-12">
        <div class="mx-auto max-w-3xl text-center space-y-6">
            @if($introTitleText)
            <div class="flex items-center justify-center gap-4">
                <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
                <p class="uppercase tracking-[0.25em] text-sm" style="color: {{ $introTitle }};">
                    {{ $introTitleText }}
                </p>
                <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
            </div>
            @endif
            @if($introTextText)
            <p class="px-6 sm:px-12 text-base leading-7" style="color: {{ $introText }};">
                {{ $introTextText }}
            </p>
            @endif
        </div>
    </div>
</section>
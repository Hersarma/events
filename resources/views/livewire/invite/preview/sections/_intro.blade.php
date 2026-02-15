@php
$introBg = data_get($s, 'intro.bg', '#ffffff');
$introTitle = data_get($s, 'intro.title_color', '#111827');
$introText = data_get($s, 'intro.text_color', '#374151');
$introTitleText = data_get($c, 'intro_title', '');
$introTextText  = data_get($c, 'intro_text', '');

$hasIntro = $introTitleText || $introTextText;
@endphp

<section style="background: {{ $introBg }};">
    <div class="mx-auto max-w-5xl px-6 py-12">
        <div class="mx-auto max-w-3xl text-center space-y-6">

            @if($hasIntro)

                @if($introTitleText)
                    <div class="flex items-center justify-center gap-4">
                        <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
                        <p class="uppercase tracking-widest font-bold" style="color: {{ $introTitle }};">
                            {{ $introTitleText }}
                        </p>
                        <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
                    </div>
                @endif

                @if($introTextText)
                    <p class="px-6 sm:px-12 text-lg leading-7" style="color: {{ $introText }};">
                        {{ $introTextText }}
                    </p>
                @endif

            @else

                {{-- ✅ Placeholder --}}
                <div class="flex items-center justify-center gap-4 opacity-60">
                    <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
                    <p class="uppercase tracking-widest font-bold" style="color: {{ $introTitle }};">
                        Uvodni naslov
                    </p>
                    <div class="h-px w-14" style="background: {{ $introTitle }};"></div>
                </div>

                <p class="px-6 sm:px-12 text-lg leading-7 opacity-70" style="color: {{ $introText }};">
                    Ovde će se prikazivati uvodni tekst pozivnice.
                    Unesite naslov i tekst u editoru kako biste prilagodili ovu sekciju.
                </p>

            @endif

        </div>
    </div>
</section>

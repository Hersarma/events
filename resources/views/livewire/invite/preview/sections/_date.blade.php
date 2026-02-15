@php
  $dateBg = data_get($s, 'date.bg', '#ffffff');
  $dateTextPrimary = data_get($s, 'date.text_primary', '#111827');
  $dateTextSecondary = data_get($s, 'date.text_secondary', '#6b7280');
  $dateLines = data_get($s, 'date.lines', '#d1d5db');

  // ✅ fallback: ako nema datuma, koristi sada
  $dt = $event->date_at ?: now();

  $autoDayName   = $dt->locale('sr')->isoFormat('dddd');
  $autoMonthName = $dt->locale('sr')->isoFormat('MMMM');
  $dayNum = (int) $dt->format('d');
  $year   = $dt->format('Y');
  $time   = $dt->format('H:i');

  $dayName   = data_get($c, 'date_day_name', $autoDayName);
  $monthName = data_get($c, 'date_month_name', $autoMonthName);
@endphp

<section class="w-full" style="background: {{ $dateBg }};">
  <div class="mx-auto max-w-5xl px-6 {{ $py }}">
    <div class="{{ $h }} flex items-center justify-center">
      <div class="flex items-center justify-center gap-3 text-center">
        <div class="flex flex-col items-center justify-center gap-2 -mr-12">
          <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
          <div class="uppercase tracking-widest" style="color: {{ $dateTextSecondary }};">
            {{ $dayName }}
          </div>
          <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
        </div>

        <div class="w-40 space-y-1">
          <div class="uppercase tracking-widest pb-2" style="color: {{ $dateTextSecondary }};">
            {{ $monthName }}
          </div>
          <div class="text-4xl font-semibold leading-none" style="color: {{ $dateTextPrimary }};">
            {{ $dayNum }}
          </div>
          <div class="pt-2 tracking-widest text-lg" style="color: {{ $dateTextSecondary }};">
            {{ $year }}
          </div>
        </div>

        <div class="flex flex-col items-center justify-center gap-2 -ml-12">
          <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
          <div class="tracking-widest text-lg" style="color: {{ $dateTextPrimary }};">
            {{ $time }}
          </div>
          <div class="h-px w-24" style="background: {{ $dateLines }};"></div>
        </div>
      </div>
    </div>
  </div>
</section>

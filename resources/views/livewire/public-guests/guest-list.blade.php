<div class="min-h-screen bg-neutral-100 px-6 py-10">
  <div class="mx-auto w-full max-w-3xl">

    {{-- HEADER + STATISTIKA --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
      <div class="text-xl font-bold">{{ $event->title }}</div>
      <div class="text-sm text-gray-600 mt-1">Lista gostiju</div>

      {{-- FILTER --}}
      <div class="mt-4 flex items-center justify-between">
        <div>
          <label class="text-sm text-gray-600">Prikaži:</label>
          <select wire:model.live="filter"
              class="ml-2 rounded-xl border border-gray-200 px-3 py-2 text-sm">
              <option value="all">Svi</option>
              <option value="coming">Dolaze</option>
              <option value="not_coming">Ne dolaze</option>
              
          </select>
        </div>
      </div>

      {{-- STATISTIKA --}}
      <div class="mt-5 grid grid-cols-2 gap-3">
        <div class="rounded-xl border border-gray-200 p-4">
          <div class="text-xs text-gray-500">Ukupno dolazi</div>
          <div class="text-2xl font-bold text-green-600">
            {{ $comingCount }}
          </div>
        </div>

      </div>
    </div>

    {{-- LISTA GOSTIJU --}}
    <div class="mt-6 grid gap-3">
      @forelse($rsvps as $r)
        <div class="rounded-2xl border border-gray-200 bg-white p-5 {{ $r->status === 'no' ? 'opacity-50 bg-gray-50' : '' }}">
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="font-semibold">{{ $r->name }}</div>

              <div class="text-sm text-gray-600 mt-1">
                @if($r->status==='yes')
                  Dolazi sam
                @elseif($r->status==='couple')
                  Dolazi u dvoje
                @else
                  Ne dolazi
                @endif

                • {{ $r->guests_count }} osoba
              </div>

              <div class="text-xs text-gray-500 mt-2">
                {{ $r->created_at->format('d.m.Y H:i') }}
              </div>
            </div>

            <div class="text-right text-sm text-gray-700 space-y-1">
  @if($r->phone)
    <div>{{ $r->phone }}</div>
  @endif

  @if($r->email)
    <div>{{ $r->email }}</div>
  @endif

  @if($r->status === 'no')
  <div class="flex justify-end">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-6 w-6 text-red-500"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M6 18L18 6M6 6l12 12"/>
    </svg>
  </div>
@endif
</div>

          </div>

          @if($r->note)
            <div class="mt-3 text-sm text-gray-700 border-t border-gray-100 pt-3">
              {{ $r->note }}
            </div>
          @endif
        </div>
      @empty
        <div class="rounded-2xl border border-gray-200 bg-white p-6 text-gray-600">
          Nema još unetih potvrda.
        </div>
      @endforelse
    </div>

  </div>
</div>

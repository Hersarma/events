<div class="min-h-screen bg-neutral-100 px-6 py-10">
  <div class="mx-auto w-full max-w-3xl">

    <div class="rounded-2xl border border-gray-200 bg-white p-6">
      <div class="text-xl font-bold">{{ $event->title }}</div>
      <div class="text-sm text-gray-600 mt-1">Lista gostiju (dolaze)</div>

      {{-- SEARCH --}}
      <div class="mt-4">
        <label class="text-sm text-gray-600">Pretraga (ime / telefon):</label>
        <div class="mt-2 flex gap-2">
          <input
            wire:model.live.debounce.300ms="q"
            type="text"
            class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm"
            placeholder="npr. Marko ili 062..."
          />

          @if($q)
            <button
              type="button"
              wire:click="$set('q','')"
              class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-semibold"
            >
              X
            </button>
          @endif
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

    {{-- LISTA --}}
    <div class="mt-6 grid gap-3">
      @forelse($rsvps as $r)
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
          <div class="flex items-start justify-between gap-4">
            <div>
              <div class="font-semibold">{{ $r->name }}</div>

              <div class="text-sm text-gray-600 mt-1">
                @if($r->status==='yes')
                  Dolazi sam
                @else
                  Dolazi u dvoje
                @endif
                • {{ $r->guests_count }} osoba
              </div>

              <div class="text-xs text-gray-500 mt-2">
                {{ $r->created_at->format('d.m.Y H:i') }}
              </div>
            </div>

            <div class="text-right text-sm text-gray-700 space-y-1">
              @if($r->phone) <div>{{ $r->phone }}</div> @endif
              @if($r->email) <div>{{ $r->email }}</div> @endif
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

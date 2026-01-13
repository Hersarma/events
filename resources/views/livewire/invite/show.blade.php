<div
    class="min-h-screen"
    style="--p: {{ $event->primary_color }}; --s: {{ $event->secondary_color }};"
>
    {{-- HERO --}}
    <section class="relative h-[85vh] w-full overflow-hidden">
        @if($event->video_path)
            <video
                id="inviteVideo"
                class="h-full w-full object-cover"
                playsinline
                preload="metadata"
                muted
            >
                <source src="{{ asset('storage/'.$event->video_path) }}" type="video/mp4">
            </video>
        @endif

        {{-- overlay --}}
        <div
            x-data="{ opened: false }"
            class="absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-sm"
            x-show="!opened"
            x-transition.opacity.duration.300ms
        >
           <button
    type="button"
    class="absolute inset-0 cursor-pointer"
    aria-label="Open invitation"
    @click="
        opened = true;
        const v = document.getElementById('inviteVideo');
        if (v) {
            v.muted = false;
            v.play();
        }
    "
></button>

        </div>

        {{-- title overlay --}}
        <div class="absolute bottom-8 left-0 right-0">
            <div class="mx-auto max-w-5xl px-6">
                <div class="rounded-3xl bg-white/70 backdrop-blur-md border border-gray-200 p-6 shadow">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                        {{ $event->title }}
                    </h1>
                    <div class="mt-2 flex flex-wrap gap-3 text-sm text-gray-700">
                        @if($event->date_at)
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full" style="background: var(--p)"></span>
                                {{ $event->date_at->format('d.m.Y. H:i') }}
                            </span>
                        @endif
                        @if($event->location_name)
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full" style="background: var(--s)"></span>
                                {{ $event->location_name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="mx-auto max-w-5xl px-6 py-10 space-y-8">
        {{-- Location --}}
        @if($event->location_url)
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Lokacija</h2>
                <p class="mt-2 text-gray-700">{{ $event->location_name ?? 'Pogledaj mapu' }}</p>
                <a
                    href="{{ $event->location_url }}"
                    target="_blank"
                    class="mt-4 inline-flex items-center justify-center rounded-2xl px-4 py-2 text-sm font-semibold text-white"
                    style="background: var(--p)"
                >
                    Otvori u mapama
                </a>
            </div>
        @endif

        {{-- RSVP --}}
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold">Potvrda dolaska</h2>

            @if($sent)
                <div class="mt-4 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                    Hvala! Potvrda je poslata. ✅
                </div>
            @endif

            <form wire:submit.prevent="submit" class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-2 flex gap-2">
                        @foreach([ 'yes' => 'Dolazim', 'maybe' => 'Možda', 'no' => 'Ne dolazim' ] as $k => $label)
                            <button
                                type="button"
                                wire:click="$set('status','{{ $k }}')"
                                class="rounded-2xl border px-4 py-2 text-sm font-semibold transition"
                                :class="''"
                                style="{{ $status === $k ? 'border-color: var(--p); background: color-mix(in oklab, var(--p) 12%, white);' : 'border-color: #E5E7EB; background: white;' }}"
                            >
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Ime i prezime</label>
                    <input wire:model.defer="name" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email (opciono)</label>
                    <input wire:model.defer="email" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Broj osoba</label>
                    <input type="number" min="1" max="20" wire:model.defer="guests_count" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-3 outline-none focus:ring-2 focus:ring-gray-200" />
                    @error('guests_count') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-2xl px-5 py-3 text-sm font-bold text-white shadow-sm"
                        style="background: var(--p)"
                    >
                        Pošalji potvrdu
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>

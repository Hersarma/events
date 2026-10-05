<div class="min-h-screen bg-neutral-100 px-4 py-8 sm:px-6">
    <div class="mx-auto w-full max-w-xl space-y-5">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Skeniraj QR</h1>
                    <div class="mt-1 text-sm text-gray-600">{{ $event->title }}</div>
                </div>

                <a
                    href="{{ route('public.guests.list', $event->token) }}"
                    class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Lista
                </a>
            </div>

            <div class="relative mt-5 overflow-hidden rounded-2xl bg-black">
                <video
                    id="qr-scanner-video"
                    data-check-in-prefix="/guests/{{ $event->token }}/check-in/"
                    class="aspect-[3/4] w-full object-cover"
                    autoplay
                    muted
                    playsinline
                ></video>
            </div>

            <div id="qr-scanner-message" class="mt-4 rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-700">
                Pokrećem kameru...
            </div>

            <div class="mt-4 text-xs text-gray-500">
                Ako kamera u browseru ne radi, možeš skenirati QR direktno običnom kamerom telefona. QR vodi na istu check-in stranicu.
            </div>
        </div>
    </div>
</div>

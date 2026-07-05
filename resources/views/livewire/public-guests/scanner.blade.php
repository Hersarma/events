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

            <div class="mt-5 overflow-hidden rounded-2xl bg-black">
                <video id="qr-scanner-video" class="aspect-[3/4] w-full object-cover" autoplay muted playsinline></video>
            </div>

            <div id="qr-scanner-message" class="mt-4 rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-700">
                Pokrećem kameru...
            </div>

            <div class="mt-4 text-xs text-gray-500">
                Ako kamera u browseru ne radi, možeš skenirati QR direktno običnom kamerom telefona. QR vodi na istu check-in stranicu.
            </div>
        </div>
    </div>

    <script>
        (() => {
            const video = document.getElementById('qr-scanner-video');
            const message = document.getElementById('qr-scanner-message');

            if (!video || !message) return;

            const setMessage = (text) => {
                message.textContent = text;
            };

            if (!('BarcodeDetector' in window)) {
                setMessage('Ovaj browser nema ugrađen QR scanner. Koristi običnu kameru telefona za skeniranje QR koda.');
                return;
            }

            navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' },
                audio: false,
            }).then((stream) => {
                video.srcObject = stream;
                const detector = new BarcodeDetector({ formats: ['qr_code'] });
                setMessage('Usmeri kameru ka QR kodu.');

                const scan = async () => {
                    if (!video.srcObject) return;

                    try {
                        const codes = await detector.detect(video);

                        if (codes.length && codes[0].rawValue) {
                            const url = new URL(codes[0].rawValue, window.location.origin);
                            const expectedPath = @js('/guests/' . $event->token . '/check-in/');

                            if (url.origin === window.location.origin && url.pathname.startsWith(expectedPath)) {
                                setMessage('QR je pronađen. Otvaram check-in...');
                                window.location.href = url.href;
                                return;
                            }

                            setMessage('Ovaj QR ne pripada ovoj listi gostiju.');
                            return;
                        }
                    } catch (error) {
                        setMessage('Scanner trenutno ne može da pročita sliku. Pokušaj ponovo ili koristi običnu kameru telefona.');
                    }

                    requestAnimationFrame(scan);
                };

                video.addEventListener('loadedmetadata', () => requestAnimationFrame(scan), { once: true });
            }).catch(() => {
                setMessage('Kamera nije dostupna. Proveri dozvolu za kameru ili skeniraj QR običnom kamerom telefona.');
            });
        })();
    </script>
</div>

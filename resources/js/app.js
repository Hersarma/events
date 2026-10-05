import './bootstrap';

const initQrScanner = async () => {
    const video = document.getElementById('qr-scanner-video');
    const message = document.getElementById('qr-scanner-message');

    if (!video || !message) return;

    const setMessage = (text) => {
        message.textContent = text;
    };

    let QrScanner;

    try {
        ({ default: QrScanner } = await import('qr-scanner'));
    } catch {
        setMessage('Scanner trenutno nije dostupan. Koristi običnu kameru telefona za skeniranje QR koda.');
        return;
    }

    if (!await QrScanner.hasCamera()) {
        setMessage('Kamera nije pronađena. QR možeš skenirati običnom kamerom telefona.');
        return;
    }

    let isOpeningCheckIn = false;

    const scanner = new QrScanner(video, (result) => {
        if (isOpeningCheckIn) return;

        let url;

        try {
            url = new URL(result.data, window.location.origin);
        } catch {
            setMessage('QR kod ne sadrži ispravan link.');
            return;
        }

        const expectedPrefix = new URL(video.dataset.checkInPrefix, window.location.origin);

        if (url.origin !== expectedPrefix.origin || !url.pathname.startsWith(expectedPrefix.pathname)) {
            setMessage('Ovaj QR ne pripada ovoj listi gostiju.');
            return;
        }

        isOpeningCheckIn = true;
        setMessage('QR je pronađen. Otvaram check-in...');
        scanner.stop();
        window.location.assign(url.href);
    }, {
        preferredCamera: 'environment',
        maxScansPerSecond: 10,
        highlightScanRegion: true,
        highlightCodeOutline: true,
        returnDetailedScanResult: true,
        onDecodeError: () => {},
    });

    window.addEventListener('pagehide', () => scanner.destroy(), { once: true });

    try {
        await scanner.start();
        setMessage('Usmeri kameru ka QR kodu.');
    } catch {
        scanner.destroy();
        setMessage('Kamera nije dostupna. Proveri dozvolu za kameru ili skeniraj QR običnom kamerom telefona.');
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initQrScanner, { once: true });
} else {
    initQrScanner();
}

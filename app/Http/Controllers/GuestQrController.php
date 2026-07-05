<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventGuest;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class GuestQrController extends Controller
{
    public function download(string $token, string $guestToken): Response
    {
        $event = Event::where('token', $token)->firstOrFail();

        abort_unless($event->enable_qr_codes, 404);

        $guest = EventGuest::where('event_id', $event->id)
            ->where('qr_token', $guestToken)
            ->with('rsvp')
            ->firstOrFail();

        abort_unless($guest->canReceiveQrCode(), 404);

        $result = (new Builder(
            writer: new SvgWriter(),
            data: route('public.guests.check-in', [
                'token' => $event->token,
                'guestToken' => $guest->ensureQrToken(),
            ]),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 420,
            margin: 16,
        ))->build();

        $fileName = Str::slug($event->title . '-' . $guest->full_name) . '-qr.svg';
        $svg = $this->makeQrCardSvg($result->getString(), $event, $guest);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function makeQrCardSvg(string $qrSvg, Event $event, EventGuest $guest): string
    {
        $eventTitle = $this->svgText(Str::limit($event->title, 48));
        $guestName = $this->svgText(Str::limit($guest->full_name, 42));
        $guestPhone = $this->svgText($guest->phone);
        $guestCount = (int) ($guest->rsvp?->guests_count ?: 1);
        $guestCountText = $this->svgText($guestCount . ' osoba');
        $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="760" height="980" viewBox="0 0 760 980">
  <rect width="760" height="980" fill="#f7f2ec"/>
  <rect x="44" y="44" width="672" height="892" rx="28" fill="#ffffff" stroke="#d8cdbd" stroke-width="3"/>

  <text x="380" y="108" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="20" font-weight="700" letter-spacing="4" fill="#6f7c72">
    QR KOD ZA ULAZ
  </text>

  <text x="380" y="160" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="34" font-weight="800" fill="#111827">
    {$guestName}
  </text>

  <text x="380" y="202" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="20" fill="#6b7280">
    {$eventTitle}
  </text>

  <rect x="142" y="254" width="476" height="476" rx="22" fill="#ffffff" stroke="#111827" stroke-width="2"/>
  <image href="{$qrDataUri}" x="170" y="282" width="420" height="420"/>

  <g font-family="Arial, Helvetica, sans-serif" fill="#111827">
    <text x="380" y="790" text-anchor="middle" font-size="22" font-weight="700">
      Pokažite ovaj kod na ulazu
    </text>
    <text x="380" y="832" text-anchor="middle" font-size="18" fill="#6b7280">
      Telefon: {$guestPhone}
    </text>
    <text x="380" y="866" text-anchor="middle" font-size="18" fill="#6b7280">
      Potvrđeno: {$guestCountText}
    </text>
  </g>

  <text x="380" y="918" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="13" letter-spacing="2" fill="#9ca3af">
    DIANA'S GARDEN STUDIO
  </text>
</svg>
SVG;
    }

    private function svgText(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

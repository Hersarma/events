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
        $eventTitle = $this->svgText(Str::upper(Str::limit($event->title, 36)));
        $guestName = $this->svgText(Str::upper(Str::limit($guest->full_name, 32)));
        $guestCount = (int) ($guest->rsvp?->guests_count ?: 1);
        $guestCountText = $this->svgText('Potvrđeno: ' . $guestCount . ' ' . ($guestCount === 1 ? 'osoba' : 'osobe'));
        $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="760" height="980" viewBox="0 0 760 980">
  <rect width="760" height="980" fill="#ffffff"/>
  <rect x="40" y="40" width="680" height="900" rx="86" fill="#ffffff" stroke="#000000" stroke-width="2.4"/>

  <text x="380" y="154" text-anchor="middle" font-family="Montserrat, Arial, Helvetica, sans-serif" font-size="34" font-weight="500" letter-spacing="8" fill="#000000">
    {$guestName}
  </text>

  <text x="380" y="196" text-anchor="middle" font-family="Montserrat, Arial, Helvetica, sans-serif" font-size="22" font-weight="500" letter-spacing="6" fill="#000000">
    {$eventTitle}
  </text>

  <rect x="140" y="276" width="480" height="480" rx="22" fill="#ffffff" stroke="#303640" stroke-width="2"/>
  <image href="{$qrDataUri}" x="170" y="306" width="420" height="420"/>

  <g font-family="Montserrat, Arial, Helvetica, sans-serif" fill="#000000">
    <text x="380" y="838" text-anchor="middle" font-size="24" font-weight="500" letter-spacing="6">
      POKAŽITE KOD NA ULAZU
    </text>
    <text x="380" y="884" text-anchor="middle" font-size="23" font-weight="500" letter-spacing="2.5">
      {$guestCountText}
    </text>
  </g>
</svg>
SVG;
    }

    private function svgText(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

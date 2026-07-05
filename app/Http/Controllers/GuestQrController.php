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

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}

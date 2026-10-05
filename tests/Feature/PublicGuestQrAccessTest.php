<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventGuest;
use App\Models\EventRsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicGuestQrAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_scanner_redirects_to_pin_without_guest_list_session(): void
    {
        $event = $this->createQrEvent();

        $response = $this->get(route('public.guests.scan', $event->token));

        $response->assertRedirect(route('public.guests.pin', $event->token));
        $this->assertSame(
            route('public.guests.scan', $event->token),
            session('url.intended'),
        );
    }

    public function test_scanner_is_rendered_after_pin_access_is_granted(): void
    {
        $event = $this->createQrEvent();

        $response = $this
            ->withSession(['guest_list_access.' . $event->id => true])
            ->get(route('public.guests.scan', $event->token));

        $response
            ->assertOk()
            ->assertSee('id="qr-scanner-video"', false)
            ->assertSee(
                'data-check-in-prefix="/guests/' . $event->token . '/check-in/"',
                false,
            );
    }

    public function test_check_in_redirects_to_pin_without_guest_list_session(): void
    {
        $event = $this->createQrEvent();

        $response = $this->get(route('public.guests.check-in', [
            'token' => $event->token,
            'guestToken' => 'guest-qr-token',
        ]));

        $response->assertRedirect(route('public.guests.pin', $event->token));
        $this->assertSame(
            route('public.guests.check-in', [
                'token' => $event->token,
                'guestToken' => 'guest-qr-token',
            ]),
            session('url.intended'),
        );
    }

    public function test_guest_list_redirects_to_pin_without_guest_list_session(): void
    {
        $event = $this->createQrEvent();

        $response = $this->get(route('public.guests.list', $event->token));

        $response->assertRedirect(route('public.guests.pin', $event->token));
    }

    public function test_confirmed_guest_qr_code_can_be_downloaded_as_svg(): void
    {
        $event = $this->createQrEvent();
        $guest = EventGuest::create([
            'event_id' => $event->id,
            'first_name' => 'Test',
            'last_name' => 'Guest',
            'full_name' => 'Test Guest',
            'phone' => '+381 60 1234567',
            'phone_normalized' => '381601234567',
        ]);

        EventRsvp::create([
            'event_id' => $event->id,
            'event_guest_id' => $guest->id,
            'status' => 'yes',
            'name' => $guest->full_name,
            'phone' => $guest->phone,
            'guests_count' => 1,
        ]);

        $response = $this->get(route('invite.qr.download', [
            'token' => $event->token,
            'guestToken' => $guest->qr_token,
        ]));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml')
            ->assertDownload('qr-test-event-test-guest-qr.svg');

        $this->assertStringContainsString('<svg', $response->getContent());
    }

    private function createQrEvent(): Event
    {
        return Event::create([
            'user_id' => User::factory()->create()->id,
            'title' => 'QR test event',
            'slug' => 'qr-test-event',
            'token' => Event::makeToken(),
            'enable_guest_list' => true,
            'enable_qr_codes' => true,
        ]);
    }
}

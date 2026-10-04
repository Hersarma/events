<?php

namespace Tests\Feature;

use App\Livewire\Invite\Show;
use App\Models\Event;
use App\Models\EventGuest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RsvpOptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_event_without_option_settings_keeps_all_rsvp_choices(): void
    {
        $event = $this->makeEvent();

        Livewire::test(Show::class, ['token' => $event->token])
            ->assertSet('status', 'yes')
            ->assertSee('Dolazim sam')
            ->assertSee('Dolazim u dvoje')
            ->assertSee('Ne dolazim');
    }

    public function test_hidden_rsvp_choice_cannot_be_submitted_manually(): void
    {
        $event = $this->makeEvent([
            'rsvp_options' => [
                'yes' => false,
                'couple' => false,
                'no' => true,
            ],
        ]);
        $this->makeGuest($event);

        Livewire::test(Show::class, ['token' => $event->token])
            ->assertSet('status', 'no')
            ->set('name', 'Test Gost')
            ->set('phone_country', '381')
            ->set('phone_number', '064 123 4567')
            ->set('status', 'yes')
            ->call('submit')
            ->assertHasErrors(['status']);

        $this->assertDatabaseCount('event_rsvps', 0);
    }

    public function test_enabled_rsvp_choice_is_saved_normally(): void
    {
        $event = $this->makeEvent([
            'rsvp_options' => [
                'yes' => false,
                'couple' => false,
                'no' => true,
            ],
        ]);
        $guest = $this->makeGuest($event);

        Livewire::test(Show::class, ['token' => $event->token])
            ->set('name', 'Test Gost')
            ->set('phone_country', '381')
            ->set('phone_number', '064 123 4567')
            ->set('status', 'no')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('event_rsvps', [
            'event_id' => $event->id,
            'event_guest_id' => $guest->id,
            'status' => 'no',
            'guests_count' => 0,
        ]);
    }

    private function makeEvent(array $content = []): Event
    {
        return Event::create([
            'user_id' => User::factory()->create()->id,
            'template' => 'celebration',
            'language' => 'sr',
            'title' => 'Test događaj',
            'slug' => 'test-dogadjaj',
            'token' => Event::makeToken(),
            'is_active' => true,
            'enable_rsvp' => true,
            'content' => $content,
            'style' => [],
        ]);
    }

    private function makeGuest(Event $event): EventGuest
    {
        return EventGuest::create([
            'event_id' => $event->id,
            'full_name' => 'Test Gost',
            'phone' => '+381 64 123 4567',
            'phone_normalized' => '381641234567',
            'max_guests' => 2,
        ]);
    }
}

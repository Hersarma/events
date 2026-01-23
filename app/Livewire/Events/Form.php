<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Form extends Component
{
    use WithFileUploads;

    public ?Event $event = null;

    public string $template = 'wedding'; // wedding|kids|celebration
    public string $language = 'sr';

    public string $title = '';
    public ?string $date_at = null;

    public string $location_name = '';
    public string $location_address = '';
    public string $location_url = '';
    public string $rsvp_email = '';

    public bool $is_active = true;
    public ?string $expires_at = null;

    public string $hero_type = 'video'; // video|image
    public string $location_bg = '#ffffff';
    public string $location_text = '#111827';
    public string $location_sub_text = '#6b7280';
    // DATE section colors
    public string $date_bg = '#ffffff';
    public string $date_text_primary = '#111827';
    public string $date_text_secondary = '#6b7280';
    public string $date_lines = '#d1d5db';

    // RSVP sekcija – stil
    public string $rsvp_bg = '#6F7C72';
    public string $rsvp_title_color = '#FFFFFF';
    public string $rsvp_subtitle_color = '#FFFFFF';

    public string $rsvp_card_bg = '#D8CDBD';
    public string $rsvp_card_border = 'rgba(255,255,255,0.15)';

    public string $rsvp_label_color = '#FFFFFF';

    public string $rsvp_input_bg = '#FFFFFF';
    public string $rsvp_input_border = '#CFC6B7';
    public string $rsvp_input_text = '#111827';

    public string $rsvp_radio_accent = '#6F7C72';
    public string $rsvp_radio_border = '#FFFFFF';

    public string $rsvp_button_bg = '#6F7C72';
    public string $rsvp_button_text = '#FFFFFF';


    /** uploads */
    public $hero_video = null;   // mp4
    public $hero_image = null;   // jpg/png
    public $map_image  = null;   // jpg/png
    public $footer_logo = null;  // png/svg
    public $location_marker = null; // png
    public $location_image = null;  // jpg/png/webp

    /** stored paths */
    public ?string $location_marker_path = null;
    public ?string $location_image_path = null;
    public ?string $hero_video_path = null;
    public ?string $hero_image_path = null;
    public ?string $map_image_path = null;
    public ?string $footer_logo_path = null;

    public string $token = '';
    public string $slug = '';

    public array $content = [];
    public array $style = [];

    public function mount(?Event $event = null): void
    {
        $this->event = $event;

        if ($event) {
            $this->template = $event->template;
            $this->language = $event->language;

            $this->title = $event->title;
            $this->slug = $event->slug;
            $this->token = $event->token;

            $this->date_at = $event->date_at?->format('Y-m-d\TH:i');

            $this->location_name = $event->location_name ?? '';
            $this->location_address = $event->location_address ?? '';
            $this->location_url = $event->location_url ?? '';
            $this->rsvp_email = $event->rsvp_email ?? '';

            $this->is_active = (bool) $event->is_active;
            $this->expires_at = $event->expires_at?->format('Y-m-d\TH:i');

            $this->hero_type = $event->hero_type;

            $this->hero_video_path = $event->hero_video_path;
            $this->hero_image_path = $event->hero_image_path;
            $this->map_image_path = $event->map_image_path;
            $this->footer_logo_path = $event->footer_logo_path;

            $this->location_marker_path = $event->location_marker_path;
            $this->location_image_path  = $event->location_image_path;

            $this->location_bg = data_get($event->style, 'location.bg', '#ffffff');
            $this->location_text = data_get($event->style, 'location.text', '#111827');
            $this->location_sub_text = data_get($event->style, 'location.sub_text', '#6b7280');

            $this->date_bg = data_get($event->style, 'date.bg', '#ffffff');
            $this->date_text_primary = data_get($event->style, 'date.text_primary', '#111827');
            $this->date_text_secondary = data_get($event->style, 'date.text_secondary', '#6b7280');
            $this->date_lines = data_get($event->style, 'date.lines', '#d1d5db');

            $this->rsvp_bg = data_get($event->style, 'rsvp.bg', '#6F7C72');
            $this->rsvp_title_color = data_get($event->style, 'rsvp.title_color', '#FFFFFF');
            $this->rsvp_subtitle_color = data_get($event->style, 'rsvp.subtitle_color', '#FFFFFF');

            $this->rsvp_card_bg = data_get($event->style, 'rsvp.card_bg', '#D8CDBD');
            $this->rsvp_card_border = data_get($event->style, 'rsvp.card_border', 'rgba(255,255,255,0.15)');

            $this->rsvp_label_color = data_get($event->style, 'rsvp.label_color', '#FFFFFF');

            $this->rsvp_input_bg = data_get($event->style, 'rsvp.input_bg', '#FFFFFF');
            $this->rsvp_input_border = data_get($event->style, 'rsvp.input_border', '#CFC6B7');
            $this->rsvp_input_text = data_get($event->style, 'rsvp.input_text', '#111827');

            $this->rsvp_radio_accent = data_get($event->style, 'rsvp.radio_accent', '#6F7C72');
            $this->rsvp_radio_border = data_get($event->style, 'rsvp.radio_border', '#FFFFFF');

            $this->rsvp_button_bg = data_get($event->style, 'rsvp.button_bg', '#6F7C72');
            $this->rsvp_button_text = data_get($event->style, 'rsvp.button_text', '#FFFFFF');


            $this->content = $event->content ?? $this->defaultContent($this->template);
            $this->style   = $event->style   ?? $this->defaultStyle($this->template);
        } else {
            $this->token = Event::makeToken();
            $this->content = $this->defaultContent($this->template);
            $this->style   = $this->defaultStyle($this->template);
        }
    }

    public function updatedTitle(): void
    {
        if (! $this->event) {
            $this->slug = Event::makeSlug($this->title);
        }
    }

    public function updatedTemplate(): void
    {
        if (! $this->event) {
            $this->content = $this->defaultContent($this->template);
            $this->style   = $this->defaultStyle($this->template);
        }
    }

    public function save(): void
    {
        $this->validate([
            'template' => ['required', 'in:wedding,kids,celebration'],
            'language' => ['required', 'string', 'max:10'],

            'title' => ['required', 'string', 'min:3', 'max:120'],
            'date_at' => ['nullable', 'date'],

            'location_name' => ['nullable', 'string', 'max:120'],
            'location_address' => ['nullable', 'string', 'max:190'],
            'location_url' => ['nullable', 'string', 'max:255'],
            'rsvp_email' => ['nullable', 'email', 'max:190'],

            'is_active' => ['boolean'],
            'expires_at' => ['nullable', 'date'],

            'hero_type' => ['required', 'in:video,image'],

            'hero_video' => ['nullable', 'file', 'mimetypes:video/mp4', 'max:51200'],
            'hero_image' => ['nullable', 'image', 'max:10240'],
            'map_image'  => ['nullable', 'image', 'max:10240'],
            'footer_logo'=> ['nullable', 'file', 'max:5120'],

            'location_bg' => ['required','string','max:20'],
            'location_text' => ['required','string','max:20'],
            'location_sub_text' => ['required','string','max:20'],
            'date_bg' => ['required','string','max:20'],
            'date_text_primary' => ['required','string','max:20'],
            'date_text_secondary' => ['required','string','max:20'],
            'date_lines' => ['required','string','max:20'],

            'rsvp_bg' => ['required','string','max:30'],
            'rsvp_title_color' => ['required','string','max:30'],
            'rsvp_subtitle_color' => ['required','string','max:30'],
            'rsvp_card_bg' => ['required','string','max:30'],
            'rsvp_card_border' => ['required','string','max:60'],
            'rsvp_label_color' => ['required','string','max:30'],
            'rsvp_input_bg' => ['required','string','max:30'],
            'rsvp_input_border' => ['required','string','max:30'],
            'rsvp_input_text' => ['required','string','max:30'],
            'rsvp_radio_accent' => ['required','string','max:30'],
            'rsvp_radio_border' => ['required','string','max:30'],
            'rsvp_button_bg' => ['required','string','max:30'],
            'rsvp_button_text' => ['required','string','max:30'],


            'location_marker' => ['nullable','image','mimes:png','max:2048'], // 2MB
            'location_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:10240'], // 10MB


            'content' => ['array'],
            'style' => ['array'],
        ]);

        $slug = $this->event?->slug ?: Event::makeSlug($this->title);
        $token = $this->event?->token ?: ($this->token ?: Event::makeToken());
        data_set($this->style, 'location.bg', $this->location_bg);
        data_set($this->style, 'location.text', $this->location_text);
        data_set($this->style, 'location.sub_text', $this->location_sub_text);
        data_set($this->style, 'date.bg', $this->date_bg);
        data_set($this->style, 'date.text_primary', $this->date_text_primary);
        data_set($this->style, 'date.text_secondary', $this->date_text_secondary);
        data_set($this->style, 'date.lines', $this->date_lines);

        data_set($this->style, 'rsvp.bg', $this->rsvp_bg);
        data_set($this->style, 'rsvp.title_color', $this->rsvp_title_color);
        data_set($this->style, 'rsvp.subtitle_color', $this->rsvp_subtitle_color);

        data_set($this->style, 'rsvp.card_bg', $this->rsvp_card_bg);
        data_set($this->style, 'rsvp.card_border', $this->rsvp_card_border);

        data_set($this->style, 'rsvp.label_color', $this->rsvp_label_color);

        data_set($this->style, 'rsvp.input_bg', $this->rsvp_input_bg);
        data_set($this->style, 'rsvp.input_border', $this->rsvp_input_border);
        data_set($this->style, 'rsvp.input_text', $this->rsvp_input_text);

        data_set($this->style, 'rsvp.radio_accent', $this->rsvp_radio_accent);
        data_set($this->style, 'rsvp.radio_border', $this->rsvp_radio_border);

        data_set($this->style, 'rsvp.button_bg', $this->rsvp_button_bg);
        data_set($this->style, 'rsvp.button_text', $this->rsvp_button_text);


        // upload marker
        if ($this->location_marker) {
            if ($this->event?->location_marker_path) {
                Storage::disk('public')->delete($this->event->location_marker_path);
            }
            $this->location_marker_path = $this->location_marker->store('invites', 'public');
        }

        // upload location image
        if ($this->location_image) {
            if ($this->event?->location_image_path) {
                Storage::disk('public')->delete($this->event->location_image_path);
            }
            $this->location_image_path = $this->location_image->store('invites', 'public');
        }
        // uploads + delete old
        if ($this->hero_video) {
            if ($this->event?->hero_video_path) Storage::disk('public')->delete($this->event->hero_video_path);
            $this->hero_video_path = $this->hero_video->store('invites', 'public');
        }

        if ($this->hero_image) {
            if ($this->event?->hero_image_path) Storage::disk('public')->delete($this->event->hero_image_path);
            $this->hero_image_path = $this->hero_image->store('invites', 'public');
        }

        if ($this->map_image) {
            if ($this->event?->map_image_path) Storage::disk('public')->delete($this->event->map_image_path);
            $this->map_image_path = $this->map_image->store('invites', 'public');
        }

        if ($this->footer_logo) {
            if ($this->event?->footer_logo_path) Storage::disk('public')->delete($this->event->footer_logo_path);
            $this->footer_logo_path = $this->footer_logo->store('invites', 'public');
        }

        $payload = [
            'user_id' => auth()->id(),

            'template' => $this->template,
            'language' => $this->language,

            'title' => $this->title,
            'slug' => $slug,
            'token' => $token,

            'date_at' => $this->date_at ? now()->parse($this->date_at) : null,
            'location_name' => $this->location_name ?: null,
            'location_address' => $this->location_address ?: null,
            'location_url' => $this->location_url ?: null,
            'rsvp_email' => $this->rsvp_email ?: null,

            'is_active' => (bool) $this->is_active,
            'expires_at' => $this->expires_at ? now()->parse($this->expires_at) : null,

            'hero_type' => $this->hero_type,
            'hero_video_path' => $this->hero_video_path,
            'hero_image_path' => $this->hero_image_path,
            'map_image_path' => $this->map_image_path,
            'footer_logo_path' => $this->footer_logo_path,


            'location_marker_path' => $this->location_marker_path,
            'location_image_path'  => $this->location_image_path,


            'content' => $this->content,
            'style' => $this->style,
        ];

        $event = $this->event
            ? tap($this->event)->update($payload)
            : Event::create($payload);

        $this->event = $event;
        $this->slug = $event->slug;
        $this->token = $event->token;

        session()->flash('status', 'Događaj je sačuvan.');
        $this->redirectRoute('events.edit', $event, navigate: true);
    }

    public function getInviteUrlProperty(): ?string
    {
        return $this->event?->invite_url;
    }

    private function defaultContent(string $template): array
    {
        return match ($template) {
            'kids' => [
                'intro_title' => 'ROĐENDANSKA POZIVNICA',
                'intro_text' => 'Dođite da se igramo i slavimo!',
                'rsvp_title' => 'Potvrdite dolazak',
                'rsvp_subtitle' => 'Molimo odgovorite do (unesi datum)',
                'footer_by' => 'INVITATIONS BY',
                'footer_brand' => 'VAŠ BRAND',
            ],
            'celebration' => [
                'intro_title' => 'OBITELJ PRODAN',
                'intro_text' => 'Poziva vas da zajedno s nama proslavite...',
                'rsvp_title' => 'Molimo potvrdite vaš dolazak',
                'rsvp_subtitle' => 'Pozivnica vrijedi za dvoje',
                'footer_by' => 'INVITATIONS BY',
                'footer_brand' => 'VAŠ STUDIO',
            ],
            default => [
                'intro_title' => 'POZIVNICA',
                'intro_text' => 'Pozivamo vas da budete deo našeg posebnog dana.',
                'rsvp_title' => 'Molimo potvrdite vaš dolazak',
                'rsvp_subtitle' => 'Pozivnica važi za dvoje',
                'footer_by' => 'INVITATIONS BY',
                'footer_brand' => 'VAŠ BRAND',
            ],
        };
    }

    private function defaultStyle(string $template): array
    {
        return match ($template) {
            'kids' => [
                'intro' => ['bg' => '#FFE7A3', 'title_color' => '#2F2A24', 'text_color' => '#2F2A24', 'line_color' => '#2F2A24'],
                'date' => ['bg' => '#7CC6FF', 'text_color' => '#0B1B2B', 'line_color' => '#0B1B2B'],
                'rsvp' => ['bg' => '#7CC6FF', 'card_bg' => '#FFF3CC', 'button_bg' => '#FF6B6B', 'button_text' => '#FFFFFF'],
            ],
            default => [
                'intro' => ['bg' => '#D8CDBD', 'title_color' => '#3C3A36', 'text_color' => '#FFFFFF', 'line_color' => '#3C3A36'],
                'date' => ['bg' => '#6F7C72', 'text_color' => '#FFFFFF', 'line_color' => '#FFFFFF'],
                'location' => ['bg' => '#D8CDBD', 'text_color' => '#3C3A36', 'icon_color' => '#6F7C72'],
                'rsvp' => [
                    'bg' => '#6F7C72',
                    'title_color' => '#FFFFFF',
                    'subtitle_color' => '#FFFFFF',
                    'card_bg' => '#D8CDBD',
                    'input_bg' => '#FFFFFF',
                    'input_border' => '#CFC6B7',
                    'button_bg' => '#6F7C72',
                    'button_text' => '#FFFFFF',
                ],
            ],
        };
    }

    public function render()
    {
        return view('livewire.events.form');
    }
}

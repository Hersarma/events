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
// ✅ samo proslava (možeš kasnije i ovo da izbaciš skroz ako hoćeš)
public string $template = 'celebration';
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
// LOCATION colors
public string $location_bg = '#ffffff';
public string $location_text = '#111827';
public string $location_sub_text = '#6b7280';
// DATE section colors
public string $date_bg = '#ffffff';
public string $date_text_primary = '#111827';
public string $date_text_secondary = '#6b7280';
public string $date_lines = '#e5e7eb';
// RSVP sekcija – stil (neutral default)
public string $rsvp_bg = '#ffffff';
public string $rsvp_title_color = '#111827';
public string $rsvp_subtitle_color = '#6b7280';
public string $rsvp_third_color = '#6b7280';
public string $rsvp_card_bg = '#ffffff';
public string $rsvp_card_border = '#e5e7eb';
public string $rsvp_label_color = '#111827';
public string $rsvp_input_bg = '#ffffff';
public string $rsvp_input_border = '#d1d5db';
public string $rsvp_input_text = '#111827';
public string $rsvp_radio_accent = '#111827';
public string $rsvp_radio_border = '#d1d5db';
public string $rsvp_button_bg = '#111827';
public string $rsvp_button_text = '#ffffff';
public string $footer_text_color = '#ffffff';
/** uploads */
public $hero_video = null;     // mp4
public $hero_image = null;     // jpg/png
public $map_image  = null;     // jpg/png
public $location_marker = null; // png

/** stored paths */
public ?string $location_marker_path = null;
public ?string $hero_video_path = null;
public ?string $hero_image_path = null;
public ?string $map_image_path = null;
public string $token = '';
public string $slug = '';
// ✅ content prazan po defaultu (nema više auto-teksta)
public array $content = [];
// ✅ style postoji (neutralno)
public array $style = [];
public function mount(?Event $event = null): void
{
$this->event = $event;
if ($event) {
$this->template = $event->template ?: 'celebration';
$this->language = $event->language ?: 'sr';
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
$this->hero_type = $event->hero_type ?: 'video';
$this->hero_video_path = $event->hero_video_path;
$this->hero_image_path = $event->hero_image_path;
$this->map_image_path = $event->map_image_path;
$this->location_marker_path = $event->location_marker_path;
// style -> properties (fallback neutral)
$this->location_bg = data_get($event->style, 'location.bg', '#ffffff');
$this->location_text = data_get($event->style, 'location.text', '#111827');
$this->location_sub_text = data_get($event->style, 'location.sub_text', '#6b7280');
$this->date_bg = data_get($event->style, 'date.bg', '#ffffff');
$this->date_text_primary = data_get($event->style, 'date.text_primary', '#111827');
$this->date_text_secondary = data_get($event->style, 'date.text_secondary', '#6b7280');
$this->date_lines = data_get($event->style, 'date.lines', '#e5e7eb');
$this->rsvp_bg = data_get($event->style, 'rsvp.bg', '#ffffff');
$this->rsvp_title_color = data_get($event->style, 'rsvp.title_color', '#111827');
$this->rsvp_subtitle_color = data_get($event->style, 'rsvp.subtitle_color', '#6b7280');
$this->rsvp_third_color = data_get($event->style, 'rsvp.third_color', '#6b7280');
$this->rsvp_card_bg = data_get($event->style, 'rsvp.card_bg', '#ffffff');
$this->rsvp_card_border = data_get($event->style, 'rsvp.card_border', '#e5e7eb');
$this->rsvp_label_color = data_get($event->style, 'rsvp.label_color', '#111827');
$this->rsvp_input_bg = data_get($event->style, 'rsvp.input_bg', '#ffffff');
$this->rsvp_input_border = data_get($event->style, 'rsvp.input_border', '#d1d5db');
$this->rsvp_input_text = data_get($event->style, 'rsvp.input_text', '#111827');
$this->rsvp_radio_accent = data_get($event->style, 'rsvp.radio_accent', '#111827');
$this->rsvp_radio_border = data_get($event->style, 'rsvp.radio_border', '#d1d5db');
$this->rsvp_button_bg = data_get($event->style, 'rsvp.button_bg', '#111827');
$this->rsvp_button_text = data_get($event->style, 'rsvp.button_text', '#ffffff');
$this->footer_text_color = data_get($event->style, 'footer.text_color', '#ffffff');
// ✅ content/style iz baze (bez default teksta)
$this->content = is_array($event->content) ? $event->content : [];
$this->style   = is_array($event->style) ? $event->style : $this->whiteStyle();
} else {
// ✅ NOV EVENT: prazan content, neutral style
$this->token = Event::makeToken();
$this->content = [];
$this->style   = $this->whiteStyle();
// ✅ obavezno i properties da se podudare sa style-om
$this->applyStyleToProps($this->style);
}
}
public function updatedTitle(): void
{
if (! $this->event) {
$this->slug = Event::makeSlug($this->title);
}
}
// ✅ više ne resetujemo content na default teksteve
public function updatedTemplate(): void
{
if (! $this->event) {
$this->content = [];
$this->style   = $this->whiteStyle();
$this->applyStyleToProps($this->style);
}
}
public function save(): void
{
$this->validate([
'template' => ['required', 'in:celebration'],
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
'rsvp_third_color' => ['required','string','max:30'],
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
'footer_text_color' => ['required','string','max:30'],
'location_marker' => ['nullable','image','mimes:png','max:2048'],

'content' => ['array'],
'style' => ['array'],
]);
$slug = $this->event?->slug ?: Event::makeSlug($this->title);
$token = $this->event?->token ?: ($this->token ?: Event::makeToken());
// sync props -> style json
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
data_set($this->style, 'rsvp.third_color', $this->rsvp_third_color);
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
data_set($this->style, 'footer.text_color', $this->footer_text_color);
// upload marker
if ($this->location_marker) {
if ($this->event?->location_marker_path) {
Storage::disk('public')->delete($this->event->location_marker_path);
}
$this->location_marker_path = $this->location_marker->store('invites', 'public');
}
// upload location image
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

'location_marker_path' => $this->location_marker_path,

'content' => $this->content,
'style' => $this->style,
];
$event = $this->event
? tap($this->event)->update($payload)
: Event::create($payload);
$this->event = $event;
$this->slug = $event->slug;
$this->token = $event->token;
$this->reset('hero_video', 'hero_image', 'map_image', 'location_marker');
session()->flash('status', 'Događaj je sačuvan.');
$this->redirectRoute('events.edit', $event, navigate: true);
}
public function getInviteUrlProperty(): ?string
{
return $this->event?->invite_url;
}
private function whiteStyle(): array
{
return [
'intro' => [
'bg' => '#ffffff',
'title_color' => '#111827',
'text_color' => '#374151',
'line_color' => '#e5e7eb',
],
'date' => [
'bg' => '#ffffff',
'text_primary' => '#111827',
'text_secondary' => '#6b7280',
'lines' => '#e5e7eb',
],
'location' => [
'bg' => '#ffffff',
'text' => '#111827',
'sub_text' => '#6b7280',
],
'rsvp' => [
'bg' => '#ffffff',
'title_color' => '#111827',
'subtitle_color' => '#6b7280',
'third_color' => '#6b7280',
'card_bg' => '#ffffff',
'card_border' => '#e5e7eb',
'label_color' => '#111827',
'input_bg' => '#ffffff',
'input_border' => '#d1d5db',
'input_text' => '#111827',
'radio_accent' => '#111827',
'radio_border' => '#d1d5db',
'button_bg' => '#111827',
'button_text' => '#ffffff',
],
'footer' => [
'text_color' => '#ffffff',
],
];
}
private function applyStyleToProps(array $style): void
{
$this->location_bg = data_get($style, 'location.bg', '#ffffff');
$this->location_text = data_get($style, 'location.text', '#111827');
$this->location_sub_text = data_get($style, 'location.sub_text', '#6b7280');
$this->date_bg = data_get($style, 'date.bg', '#ffffff');
$this->date_text_primary = data_get($style, 'date.text_primary', '#111827');
$this->date_text_secondary = data_get($style, 'date.text_secondary', '#6b7280');
$this->date_lines = data_get($style, 'date.lines', '#e5e7eb');
$this->rsvp_bg = data_get($style, 'rsvp.bg', '#ffffff');
$this->rsvp_title_color = data_get($style, 'rsvp.title_color', '#111827');
$this->rsvp_subtitle_color = data_get($style, 'rsvp.subtitle_color', '#6b7280');
$this->rsvp_third_color = data_get($style, 'rsvp.third_color', '#6b7280');
$this->rsvp_card_bg = data_get($style, 'rsvp.card_bg', '#ffffff');
$this->rsvp_card_border = data_get($style, 'rsvp.card_border', '#e5e7eb');
$this->rsvp_label_color = data_get($style, 'rsvp.label_color', '#111827');
$this->rsvp_input_bg = data_get($style, 'rsvp.input_bg', '#ffffff');
$this->rsvp_input_border = data_get($style, 'rsvp.input_border', '#d1d5db');
$this->rsvp_input_text = data_get($style, 'rsvp.input_text', '#111827');
$this->rsvp_radio_accent = data_get($style, 'rsvp.radio_accent', '#111827');
$this->rsvp_radio_border = data_get($style, 'rsvp.radio_border', '#d1d5db');
$this->rsvp_button_bg = data_get($style, 'rsvp.button_bg', '#111827');
$this->rsvp_button_text = data_get($style, 'rsvp.button_text', '#ffffff');
$this->footer_text_color = data_get($style, 'footer.text_color', '#ffffff');
}
public function removeHeroVideo(): void
{
if (! $this->event) return;
if ($this->event->hero_video_path) {
Storage::disk('public')->delete($this->event->hero_video_path);
}
$this->hero_video = null;
$this->hero_video_path = null;
$this->event->update([
'hero_video_path' => null,
]);
$this->dispatch('flash', message: 'Hero video je uklonjen.', type: 'success');
}
public function removeHeroImage(): void
{
if (! $this->event) return;
if ($this->event->hero_image_path) {
Storage::disk('public')->delete($this->event->hero_image_path);
}
$this->hero_image = null;
$this->hero_image_path = null;
$this->event->update([
'hero_image_path' => null,
]);
$this->dispatch('flash', message: 'Hero slika je uklonjena.', type: 'success');
}
public function removeMapImage(): void
{
if (! $this->event) return;
if ($this->event->map_image_path) {
Storage::disk('public')->delete($this->event->map_image_path);
}
$this->map_image = null;
$this->map_image_path = null;
$this->event->update(['map_image_path' => null]);
$this->dispatch('flash', message: 'Mapa je uklonjena.', type: 'success');
}
public function removeLocationMarker(): void
{
if (! $this->event) return;
if ($this->event->location_marker_path) {
Storage::disk('public')->delete($this->event->location_marker_path);
}
$this->location_marker = null;
$this->location_marker_path = null;
$this->event->update(['location_marker_path' => null]);
$this->dispatch('flash', message: 'Marker je uklonjen.', type: 'success');
}
public function render()
{
return view('livewire.events.form');
}
}
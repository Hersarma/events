<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->first(); // imaš jednog admina (AdminSeeder već radi)

        if (! $admin) {
            $this->command?->warn('Nema user-a u bazi. Pokreni prvo AdminSeeder.');
            return;
        }

        $templates = [
            'wedding' => $this->weddingPreset(),
            'kids' => $this->kidsPreset(),
            'celebration' => $this->celebrationPreset(),
        ];

        foreach ($templates as $key => $preset) {
            // pravi po 1 demo event po šablonu (možeš obrisati ako nećeš demo)
            Event::query()->firstOrCreate(
                ['slug' => $preset['slug']],
                array_merge($preset, [
                    'user_id' => $admin->id,
                    'template' => $key,
                    'token' => $this->niceToken(),
                    'is_active' => true,
                ])
            );
        }
    }

    private function niceToken(): string
    {
        // lep token za URL, bez specijalnih znakova
        return Str::lower(Str::random(6) . '-' . Str::random(6));
    }

    private function weddingPreset(): array
    {
        return [
            'language' => 'sr',
            'title' => 'Ana & Marko',
            'slug' => 'ana-i-marko',
            'hero_type' => 'video',

            'date_at' => now()->addMonths(3)->setTime(15, 30),
            'location_name' => 'Restoran (unesi naziv)',
            'location_address' => 'Ulica 1, Grad',
            'location_url' => 'https://maps.app.goo.gl/',

            'rsvp_email' => 'rsvp@primer.rs',

            'content' => [
                'intro_title' => 'POZIVNICA',
                'intro_text' => 'Pozivamo vas da budete deo našeg posebnog dana. Biće nam čast da zajedno proslavimo.',

                'date_month' => 'JUN',
                'date_day_name' => 'SUBOTA',
                'date_time' => '15:30',

                'location_label' => 'LOKACIJA',

                'rsvp_title' => 'Molimo potvrdite vaš dolazak',
                'rsvp_subtitle' => 'Pozivnica važi za dvoje',
                'footer_by' => 'INVITATIONS BY',
                'footer_brand' => 'VAŠ STUDIO / BRAND',
            ],

            'style' => [
                // inspirisano tvojim webflow primerom (bež + maslinasto)
                'page' => ['font' => 'Montserrat'],

                'intro' => [
                    'bg' => '#D8CDBD',
                    'title_color' => '#3C3A36',
                    'text_color' => '#FFFFFF',
                    'line_color' => '#3C3A36',
                ],

                'date' => [
                    'bg' => '#6F7C72',
                    'text_color' => '#FFFFFF',
                    'line_color' => '#FFFFFF',
                ],

                'location' => [
                    'bg' => '#D8CDBD',
                    'text_color' => '#3C3A36',
                    'icon_color' => '#6F7C72',
                    'border_color' => '#D8CDBD',
                ],

                'rsvp' => [
                    'bg' => '#6F7C72',
                    'title_color' => '#FFFFFF',
                    'subtitle_color' => '#FFFFFF',
                    'card_bg' => '#D8CDBD',
                    'input_bg' => '#FFFFFF',
                    'input_border' => '#CFC6B7',
                    'label_color' => '#FFFFFF',
                    'radio_color' => '#FFFFFF',
                    'button_bg' => '#6F7C72',
                    'button_text' => '#FFFFFF',
                ],
            ],
        ];
    }

    private function kidsPreset(): array
    {
        return [
            'language' => 'sr',
            'title' => 'Rođendan – Luka (6)',
            'slug' => 'rodjendan-luka',
            'hero_type' => 'video',

            'date_at' => now()->addMonths(1)->setTime(17, 0),
            'location_name' => 'Igraonica (unesi naziv)',
            'location_address' => 'Ulica 2, Grad',
            'location_url' => 'https://maps.app.goo.gl/',

            'rsvp_email' => 'rsvp@primer.rs',

            'content' => [
                'intro_title' => 'ROĐENDANSKA POZIVNICA',
                'intro_text' => 'Dođite da se igramo i slavimo! Čekaju vas muzika, torta i iznenađenja.',

                'date_month' => 'FEB',
                'date_day_name' => 'NEDELJA',
                'date_time' => '17:00',

                'rsvp_title' => 'Potvrdite dolazak',
                'rsvp_subtitle' => 'Molimo odgovorite do (unesi datum)',
                'footer_by' => 'INVITATIONS BY',
                'footer_brand' => 'VAŠ STUDIO / BRAND',
            ],

            'style' => [
                'page' => ['font' => 'Montserrat'],

                // veselije boje
                'intro' => [
                    'bg' => '#FFE7A3',
                    'title_color' => '#2F2A24',
                    'text_color' => '#2F2A24',
                    'line_color' => '#2F2A24',
                ],
                'date' => [
                    'bg' => '#7CC6FF',
                    'text_color' => '#0B1B2B',
                    'line_color' => '#0B1B2B',
                ],
                'location' => [
                    'bg' => '#FFF3CC',
                    'text_color' => '#2F2A24',
                    'icon_color' => '#FF6B6B',
                    'border_color' => '#FFF3CC',
                ],
                'rsvp' => [
                    'bg' => '#7CC6FF',
                    'title_color' => '#0B1B2B',
                    'subtitle_color' => '#0B1B2B',
                    'card_bg' => '#FFF3CC',
                    'input_bg' => '#FFFFFF',
                    'input_border' => '#E7D8A8',
                    'label_color' => '#0B1B2B',
                    'radio_color' => '#0B1B2B',
                    'button_bg' => '#FF6B6B',
                    'button_text' => '#FFFFFF',
                ],
            ],
        ];
    }

    private function celebrationPreset(): array
    {
        return [
            'language' => 'sr',
            'title' => 'Godišnjica – Porodica Prodan',
            'slug' => 'godisnjica-prodan',
            'hero_type' => 'video',

            'date_at' => now()->addMonths(2)->setTime(15, 30),
            'location_name' => 'Konoba (unesi naziv)',
            'location_address' => 'Umaška ul. 35, Umag',
            'location_url' => 'https://maps.app.goo.gl/oJLfTfjLs4bJwwJP7?g_st=ic',

            'rsvp_email' => 'rsvp@primer.rs',

            'content' => [
                'intro_title' => 'OBITELJ PRODAN',
                'intro_text' => 'Poziva vas da zajedno s nama proslavite obljetnicu naše konobe...',

                'date_month' => 'LIPANJ',
                'date_day_name' => 'SUBOTA',
                'date_time' => '15:30',

                'rsvp_title' => '',
                'rsvp_subtitle' => '',
                'rsvp_third' => '',
                'footer_by' => 'INVITATIONS BY',
                'footer_brand' => 'DIANAS GARDEN DESIGN STUDIO',
            ],

            // skoro isto kao webflow primer
            'style' => [
                'page' => ['font' => 'Montserrat'],

                'intro' => [
                    'bg' => '#D8CDBD',
                    'title_color' => '#3C3A36',
                    'text_color' => '#FFFFFF',
                    'line_color' => '#3C3A36',
                ],
                'date' => [
                    'bg' => '#6F7C72',
                    'text_color' => '#FFFFFF',
                    'line_color' => '#FFFFFF',
                ],
                'location' => [
                    'bg' => '#D8CDBD',
                    'text_color' => '#3C3A36',
                    'icon_color' => '#6F7C72',
                    'border_color' => '#D8CDBD',
                ],
                'rsvp' => [
                    'bg' => '#6F7C72',
                    'title_color' => '#FFFFFF',
                    'subtitle_color' => '#FFFFFF',
                    'third_color' => '#FFFFFF',
                    'card_bg' => '#D8CDBD',
                    'input_bg' => '#FFFFFF',
                    'input_border' => '#CFC6B7',
                    'label_color' => '#FFFFFF',
                    'radio_color' => '#FFFFFF',
                    'button_bg' => '#6F7C72',
                    'button_text' => '#FFFFFF',
                ],
            ],
        ];
    }
}

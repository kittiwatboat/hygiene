<?php

namespace Database\Seeders;

use App\Models\KioskTheme;
use Illuminate\Database\Seeder;

class KioskThemeSeeder extends Seeder
{
    public function run(): void
    {
        $theme = KioskTheme::updateOrCreate(
            [
                'slug' => 'hygiene-default',
            ],
            [
                'name' => 'Hygiene Default',

                'primary_color' => '#00AEEF',
                'secondary_color' => '#FFFFFF',
                'accent_color' => '#F7941D',

                'background_color' => '#FFFFFF',
                'text_color' => '#111827',
                'muted_text_color' => '#6B7280',

                'button_background_color' => '#00AEEF',
                'button_text_color' => '#FFFFFF',
                'button_border_color' => null,
                'button_hover_background_color' => '#0099D6',
                'button_hover_text_color' => '#FFFFFF',

                'card_background_color' => '#FFFFFF',
                'card_text_color' => '#111827',
                'card_border_color' => '#E5E7EB',

                'success_color' => '#22C55E',
                'warning_color' => '#F59E0B',
                'danger_color' => '#EF4444',
                'info_color' => '#3B82F6',

                'font_family' => 'Prompt',

                'button_radius' => 24,
                'card_radius' => 28,
                'input_radius' => 16,

                'settings_json' => [
                    'overlay_color' => 'rgba(0, 0, 0, 0.25)',
                    'shadow' => '0 16px 40px rgba(0, 0, 0, 0.12)',
                    'disabled_color' => '#D1D5DB',
                ],

                'is_default' => true,
                'is_active' => true,
            ]
        );

        KioskTheme::where('id', '!=', $theme->id)
            ->update([
                'is_default' => false,
            ]);
    }
}

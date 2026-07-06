<?php

namespace Database\Seeders;

use App\Models\KioskLayout;
use App\Models\KioskTheme;
use Illuminate\Database\Seeder;

class KioskLayoutSeeder extends Seeder
{
    public function run(): void
    {
        $theme = KioskTheme::where('is_default', true)
            ->where('is_active', true)
            ->first();

        $layout = KioskLayout::updateOrCreate(
            [
                'name' => 'Default Kiosk Layout',
            ],
            [
                'theme_id' => $theme?->id,

                'show_top_section' => true,
                'top_section_type' => 'image',
                'top_section_height' => 220,

                'top_section_image' => null,
                'top_section_video' => null,

                'show_overlay_logo' => true,
                'overlay_logo' => null,

                'show_overlay_icon' => false,
                'overlay_icon' => null,

                'overlay_position' => 'center',

                'overlay_width' => 180,
                'overlay_height' => null,

                'show_media_overlay' => false,
                'media_overlay_color' => 'rgba(0, 0, 0, 0.2)',

                'settings_json' => [
                    'object_fit' => 'cover',
                    'video_autoplay' => true,
                    'video_muted' => true,
                    'video_loop' => true,
                ],

                'is_default' => true,
                'is_active' => true,
            ]
        );

        KioskLayout::where('id', '!=', $layout->id)
            ->update([
                'is_default' => false,
            ]);
    }
}

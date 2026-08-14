<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FrontendTheme;
use App\Models\Machine;
use Illuminate\Http\JsonResponse;

class MachineThemeController extends Controller
{
    /**
     * ตรวจสอบว่าเครื่องอยู่กลุ่มไหน และส่ง Theme ของกลุ่มนั้นกลับไป
     *
     * GET /api/frontend/machines/{machine}/theme
     */
    public function show(Machine $machine): JsonResponse
    {
        $machine->load([
            'group.theme',
        ]);

        $group = $machine->group;

        $theme = null;
        $themeSource = null;

        /*
        |--------------------------------------------------------------------------
        | 1. ใช้ Theme จากกลุ่มของเครื่องก่อน
        |--------------------------------------------------------------------------
        */
        if (
            $group &&
            (bool) $group->is_active &&
            $group->theme &&
            (bool) $group->theme->is_active
        ) {
            $theme = $group->theme;
            $themeSource = 'machine_group';
        }

        /*
        |--------------------------------------------------------------------------
        | 2. ถ้าเครื่องไม่มีกลุ่ม / กลุ่มไม่มี Theme / Theme ปิดใช้งาน
        |    ให้ใช้ Theme สำรอง
        |--------------------------------------------------------------------------
        */
        if (! $theme) {
            $theme = FrontendTheme::query()
                ->where('is_active', 1)
                ->where('is_default', 1)
                ->first();

            $themeSource = $theme ? 'default_theme' : null;
        }

        if (! $theme) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบ Theme ที่สามารถใช้งานได้',
                'data' => [
                    'machine' => [
                        'id' => $machine->id,
                    ],
                    'machine_group' => $group ? [
                        'id' => $group->id,
                        'name' => $group->name,
                        'code' => $group->code,
                    ] : null,
                    'theme' => null,
                ],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'โหลด Theme ของเครื่องสำเร็จ',
            'data' => [
                'machine' => [
                    'id' => $machine->id,
                    'machine_group_id' => $machine->machine_group_id,
                ],

                'machine_group' => $group ? [
                    'id' => $group->id,
                    'name' => $group->name,
                    'code' => $group->code,
                    'frontend_theme_id' => $group->frontend_theme_id,
                    'is_active' => (bool) $group->is_active,
                ] : null,

                'theme_source' => $themeSource,

                'theme' => $this->formatTheme($theme),
            ],
        ]);
    }

    private function formatTheme(FrontendTheme $theme): array
    {
        return [
            'id' => $theme->id,
            'name' => $theme->name,
            'slug' => $theme->slug,

            'background' => [
                'type' => $theme->background_type,
                'color' => $theme->background_color,
                'image' => $this->assetUrl(
                    $theme->background_image,
                    'assets/img/frontend/themes/'
                ),
                'video' => $this->assetUrl(
                    $theme->background_video,
                    'assets/videos/frontend/themes/'
                ),
            ],

            'text_color' => $theme->text_color,

            'button' => [
                'color' => $theme->button_color,
                'text_color' => $theme->button_text_color,
                'hover_border_color' => $theme->button_hover_border_color,
            ],

            'header' => [
                'type' => $theme->header_type,
                'height' => (int) ($theme->header_height ?? 82),
                'background_color' => $theme->header_background_color,
                'background_image' => $this->assetUrl(
                    $theme->header_background_image,
                    'assets/img/frontend/themes/'
                ),
                'background_video' => $this->assetUrl(
                    $theme->header_background_video,
                    'assets/videos/frontend/themes/'
                ),
                'logo_right_1' => $this->assetUrl(
                    $theme->header_logo_right_1,
                    'assets/img/frontend/themes/'
                ),
                'logo_right_2' => $this->assetUrl(
                    $theme->header_logo_right_2,
                    'assets/img/frontend/themes/'
                ),

                // ถ้าตารางมี field เหล่านี้อยู่แล้ว จะถูกส่งไปด้วย
                'title' => $theme->header_title ?? null,
                'title_color' => $theme->header_title_color ?? null,
                'title_size' => isset($theme->header_title_size)
                    ? (int) $theme->header_title_size
                    : null,
            ],

            'menu' => [
                'show_home_button' => (bool) $theme->show_home_button,
                'home_button_text' => $theme->home_button_text,
            ],

            'is_active' => (bool) $theme->is_active,
            'is_default' => (bool) $theme->is_default,
        ];
    }

    private function assetUrl(?string $filename, string $directory): ?string
    {
        if (! $filename) {
            return null;
        }

        return asset($directory . $filename);
    }
}

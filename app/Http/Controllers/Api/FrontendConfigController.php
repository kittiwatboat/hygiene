<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FrontendPage;
use App\Models\FrontendTheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class FrontendConfigController extends Controller
{
    /**
     * ส่งข้อมูล Theme และหน้าแรกของตู้
     */
    public function firstPage(): JsonResponse
    {
        try {
            $theme = FrontendTheme::query()
                ->orderByDesc('id')
                ->first();

            $page = FrontendPage::query()
                ->with([
                    'media' => function ($query) {
                        $query
                            ->orderBy('sort_order')
                            ->orderBy('id');
                    },
                ])
                ->where('screen_key', 'first_page')
                ->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลหน้าแรก',
                    'data' => null,
                ], 404);
            }

            $settings = $page->settings_json ?? [];

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลหน้าแรกสำเร็จ',

                'data' => [
                    'theme' => $theme
                        ? $this->formatTheme($theme)
                        : null,

                    'page' => [
                        'id' => $page->id,
                        'screen_key' => $page->screen_key,
                        'name' => $page->name,
                        'title' => $page->title,
                        'subtitle' => $page->subtitle,
                        'remark' => $page->remark,
                        'is_active' => (bool) $page->is_active,

                        'settings' => [
                            'show_start_button' => (bool) (
                                $settings['show_start_button']
                                ?? true
                            ),

                            'start_button_text_key' =>
                                'first_page.start_button',

                            'start_button_icon' =>
                                $settings['start_button_icon']
                                ?? 'tabler-bottle',

                            'start_button_action' =>
                                $settings['start_button_action']
                                ?? 'phone_verify_page',
                        ],

                        'media' => $page->media
                            ->map(function ($media) {
                                return [
                                    'id' => $media->id,
                                    'type' => $media->media_type,
                                    'url' => $media->file_url,
                                    'title' => $media->title,
                                    'subtitle' => $media->subtitle,

                                    'duration_seconds' => (int) (
                                        $media->duration_seconds
                                        ?? 5
                                    ),

                                    'object_fit' =>
                                        $media->object_fit
                                        ?? 'cover',

                                    'sort_order' => (int) (
                                        $media->sort_order
                                        ?? 0
                                    ),

                                    'is_active' => isset(
                                        $media->is_active
                                    )
                                        ? (bool) $media->is_active
                                        : true,
                                ];
                            })
                            ->values(),
                    ],
                ],
            ]);
        } catch (Throwable $exception) {
            Log::error('Frontend first page API error', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถดึงข้อมูลหน้าแรกได้',
                'error' => config('app.debug')
                    ? $exception->getMessage()
                    : null,
            ], 500);
        }
    }

    /**
     * จัดรูปแบบ Theme ที่ส่งให้หน้าตู้
     */
    private function formatTheme(
        FrontendTheme $theme
    ): array {
        return [
            'id' => $theme->id,
            'name' => $theme->name,

            'background_type' =>
                $theme->background_type
                ?? 'color',

            'background_color' =>
                $theme->background_color
                ?? '#DFF8FF',

            'background_image_url' =>
                $theme->background_image_url
                ?? null,

            'primary_color' =>
                $theme->primary_color
                ?? '#0877C9',

            'secondary_color' =>
                $theme->secondary_color
                ?? '#6F63F6',

            'text_color' =>
                $theme->text_color
                ?? '#111827',

            'button_text_color' =>
                $theme->button_text_color
                ?? '#FFFFFF',

            'header_logo_left_url' =>
                $theme->header_logo_left_url
                ?? null,

            'header_logo_right_1_url' =>
                $theme->header_logo_right_1_url
                ?? null,

            'header_logo_right_2_url' =>
                $theme->header_logo_right_2_url
                ?? null,

            'show_home_button' => (bool) (
                $theme->show_home_button
                ?? true
            ),

            'home_button_text' =>
                $theme->home_button_text
                ?? 'หน้าหลัก',

            'is_active' => true,
        ];
    }
}

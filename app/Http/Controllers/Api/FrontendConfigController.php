<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FrontendLanguage;
use App\Models\FrontendPage;
use App\Models\FrontendTheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\FrontendTranslation;

class FrontendConfigController extends Controller
{
    public function bootstrap(): JsonResponse
    {
        try {
            $themeData = $this->getThemeWithLanguages();
            $translations = $this->getTranslations();
            $pages = FrontendPage::query()
                ->with([
                    'media' => function ($query) {
                        $query->orderBy('sort_order')->orderBy('id');
                    },
                ])
                ->orderBy('id')
                ->get()
                ->map(fn (FrontendPage $page) => $this->formatPage($page))
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลหน้าบ้านทั้งหมดสำเร็จ',
                'data' => [
                    'theme' => $themeData['theme'],
                    'languages' => $themeData['languages'],
                    'pages' => $pages,
                    'translations' => $translations,
                ],
            ]);
        } catch (Throwable $exception) {
            return $this->errorResponse(
                'Frontend bootstrap API error',
                'ไม่สามารถดึงข้อมูลหน้าบ้านทั้งหมดได้',
                $exception
            );
        }
    }

    public function theme(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูล Theme สำเร็จ',
                'data' => $this->getThemeWithLanguages(),
            ]);
        } catch (Throwable $exception) {
            return $this->errorResponse(
                'Frontend theme API error',
                'ไม่สามารถดึงข้อมูล Theme ได้',
                $exception
            );
        }
    }

    public function pages(): JsonResponse
    {
        try {
            $pages = FrontendPage::query()
                ->with([
                    'media' => function ($query) {
                        $query->orderBy('sort_order')->orderBy('id');
                    },
                ])
                ->orderBy('id')
                ->get()
                ->map(fn (FrontendPage $page) => $this->formatPage($page))
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลหน้าทั้งหมดสำเร็จ',
                'data' => [
                    'pages' => $pages,
                ],
            ]);
        } catch (Throwable $exception) {
            return $this->errorResponse(
                'Frontend pages API error',
                'ไม่สามารถดึงข้อมูลหน้าทั้งหมดได้',
                $exception
            );
        }
    }

    public function page(string $screenKey): JsonResponse
    {
        try {
            $page = FrontendPage::query()
                ->with([
                    'media' => function ($query) {
                        $query->orderBy('sort_order')->orderBy('id');
                    },
                ])
                ->where('screen_key', $screenKey)
                ->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลหน้าที่ต้องการ',
                    'data' => null,
                ], 404);
            }

            $themeData = $this->getThemeWithLanguages();

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลหน้าสำเร็จ',
                'data' => [
                    'page' => $this->formatPage($page),
                    'theme' => $themeData['theme'],
                    'languages' => $themeData['languages'],
                ],
            ]);
        } catch (Throwable $exception) {
            return $this->errorResponse(
                'Frontend page API error',
                'ไม่สามารถดึงข้อมูลหน้าได้',
                $exception,
                ['screen_key' => $screenKey]
            );
        }
    }

    private function getActiveTheme(): ?FrontendTheme
    {
        $theme = FrontendTheme::query()
            ->where('is_active', true)
            ->where('is_default', true)
            ->first();

        if (!$theme) {
            $theme = FrontendTheme::query()
                ->where('is_active', true)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->first();
        }

        return $theme;
    }

    private function getThemeWithLanguages(): array
    {
        $theme = $this->getActiveTheme();

        $languages = FrontendLanguage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $defaultLanguage = $languages->firstWhere('is_default', true)
            ?? $languages->first();

        return [
            'theme' => $theme ? $this->formatTheme($theme) : null,
            'languages' => [
                'default' => $defaultLanguage
                    ? $this->formatLanguage($defaultLanguage)
                    : null,
                'items' => $languages
                    ->map(fn (FrontendLanguage $language) => $this->formatLanguage($language))
                    ->values(),
            ],
        ];
    }

    private function formatPage(FrontendPage $page): array
    {
        $settings = $page->settings_json ?? [];

        if (is_string($settings)) {
            $settings = json_decode($settings, true) ?: [];
        }

        return [
            'id' => $page->id,
            'screen_key' => $page->screen_key,
            'name' => $page->name,
            'title' => $page->title ?? null,
            'subtitle' => $page->subtitle ?? null,
            'remark' => $page->remark ?? null,
            'is_active' => isset($page->is_active) ? (bool) $page->is_active : true,
            'settings' => $settings,
            'media' => $page->media
                ->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'media_slot' => $media->media_slot ?? null,
                        'type' => $media->media_type ?? null,
                        'url' => $media->file_url ?? null,
                        'title' => $media->title ?? null,
                        'subtitle' => $media->subtitle ?? null,
                        'duration_seconds' => (int) ($media->duration_seconds ?? 5),
                        'object_fit' => $media->object_fit ?? 'cover',
                        'sort_order' => (int) ($media->sort_order ?? 0),
                        'is_active' => isset($media->is_active)
                            ? (bool) $media->is_active
                            : true,
                    ];
                })
                ->values(),
        ];
    }

    private function formatTheme(FrontendTheme $theme): array
    {
        return [
            'id' => $theme->id,
            'name' => $theme->name,
            'slug' => $theme->slug ?? null,
            'background_type' => $theme->background_type ?? 'color',
            'background_color' => $theme->background_color ?? '#DFF8FF',
            'background_image_url' => $theme->background_image_url ?? null,
            'background_video_url' => $theme->background_video_url ?? null,
            'text_color' => $theme->text_color ?? '#111827',
            'button_color' => $theme->button_color ?? '#0877C9',
            'button_text_color' => $theme->button_text_color ?? '#FFFFFF',
            'button_hover_border_color' => $theme->button_hover_border_color ?? '#0877C9',
            'logo_url' => $theme->logo_url ?? null,
            'header_type' => $theme->header_type ?? 'none',
            'header_height' => (int) ($theme->header_height ?? 100),
            'header_background_color' => $theme->header_background_color ?? null,
            'header_background_image_url' => $theme->header_background_image_url ?? null,
            'header_background_video_url' => $theme->header_background_video_url ?? null,
            'header_logo_main_url' => $theme->header_logo_main_url ?? null,
            'header_logo_right_1_url' => $theme->header_logo_right_1_url ?? null,
            'header_logo_right_2_url' => $theme->header_logo_right_2_url ?? null,
            'show_home_button' => (bool) ($theme->show_home_button ?? true),
            'home_button_text' => $theme->home_button_text ?? 'หน้าหลัก',
            'is_default' => (bool) ($theme->is_default ?? false),
            'is_active' => (bool) ($theme->is_active ?? true),
        ];
    }

    private function formatLanguage(FrontendLanguage $language): array
    {
        return [
            'id' => $language->id,
            'name' => $language->name,
            'native_name' => $language->native_name ?? $language->name,
            'code' => $language->code,
            'locale' => $language->locale,
            'flag' => $language->flag ?? null,
            'flag_url' => $language->flag_url ?? null,
            'sort_order' => (int) ($language->sort_order ?? 0),
            'is_default' => (bool) ($language->is_default ?? false),
            'is_active' => (bool) ($language->is_active ?? true),
        ];
    }

    private function errorResponse(
        string $logTitle,
        string $message,
        Throwable $exception,
        array $context = []
    ): JsonResponse {
        Log::error($logTitle, array_merge($context, [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]));

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug')
                ? $exception->getMessage()
                : null,
        ], 500);
    }
    private function getTranslations(): array
{
    $translations = FrontendTranslation::query()
        ->with([
            'language',
            'translationKey',
        ])
        ->whereHas('language', function ($query) {
            $query->where('is_active', true);
        })
        ->get();

    $result = [];

    foreach ($translations as $translation) {
        $languageCode =
            $translation->language?->code;

        $translationKey =
            $translation->translationKey?->key;

        if (!$languageCode || !$translationKey) {
            continue;
        }

        data_set(
            $result[$languageCode],
            $translationKey,
            $translation->value
        );
    }

    return $result;
}
}

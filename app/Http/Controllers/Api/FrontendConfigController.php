<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FrontendLanguage;
use App\Models\FrontendPage;
use App\Models\FrontendTheme;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class FrontendConfigController extends Controller
{
    /**
     * ส่งข้อมูล Theme และหน้าแรกของตู้
     */
    private function getActiveTheme(): ?FrontendTheme
{
    $theme = FrontendTheme::query()
        ->where('is_active', true)
        ->where('is_default', true)
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Fallback กรณียังไม่มี Theme Default
    |--------------------------------------------------------------------------
    */
    if (!$theme) {
        $theme = FrontendTheme::query()
            ->where('is_active', true)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();
    }

    return $theme;
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
            Log::error('Frontend theme API error', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถดึงข้อมูล Theme ได้',
                'error' => config('app.debug')
                    ? $exception->getMessage()
                    : null,
            ], 500);
        }
    }

public function firstPage(): JsonResponse {
    try {
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

        $responseData = [
            'page' => [
                'id' => $page->id,
                'screen_key' => $page->screen_key,
                'name' => $page->name,
                'title' => $page->title,
                'subtitle' => $page->subtitle,
                'remark' => $page->remark,

                'is_active' => isset($page->is_active)
                    ? (bool) $page->is_active
                    : true,

                'translation_keys' => [
                    'start_button' =>
                        'first_page.start_button',
                ],

                'settings' => [
                    'show_start_button' => (bool) (
                        $settings['show_start_button']
                        ?? true
                    ),

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

                            'type' =>
                                $media->media_type,

                            'url' =>
                                $media->file_url,

                            'title' =>
                                $media->title,

                            'subtitle' =>
                                $media->subtitle,

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
        ];

        /*
        |--------------------------------------------------------------------------
        | ส่ง Theme เฉพาะเมื่อระบุ include_theme=1
        |--------------------------------------------------------------------------
        */
        $themeData = $this->getThemeWithLanguages();

        $responseData['theme'] = $themeData['theme'];
        $responseData['languages'] = $themeData['languages'];

        return response()->json([
            'success' => true,
            'message' => 'ดึงข้อมูลหน้าแรกสำเร็จ',
            'data' => $responseData,
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

public function selectProduct(): JsonResponse {
    try {
        $page = FrontendPage::query()
            ->where('screen_key', 'select_product_page')
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลหน้าเลือกสินค้า',
                'data' => null,
            ], 404);
        }

        $settings = $page->settings_json ?? [];

        $products = Product::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $productGroups = [
            [
                'code' => 'detergent',
                'name' => 'น้ำยาซักผ้า',
                'icon' => $settings[
                    'category_primary_icon'
                ] ?? 'tabler-basket',
            ],
            [
                'code' => 'softener',
                'name' => 'น้ำยาปรับผ้านุ่ม',
                'icon' => $settings[
                    'category_secondary_icon'
                ] ?? 'tabler-droplet',
            ],
        ];

        $responseData = [
            'page' => [
                'id' => $page->id,
                'screen_key' => $page->screen_key,
                'name' => $page->name,
                'title' => $page->title,
                'subtitle' => $page->subtitle,

                'is_active' => isset($page->is_active)
                    ? (bool) $page->is_active
                    : true,

                'translation_keys' => [
                    'title' =>
                        'select_product_page.title',

                    'subtitle' =>
                        'select_product_page.subtitle',

                    'back_button' =>
                        'select_product_page.back_button',

                    'confirm_button' =>
                        'select_product_page.confirm_button',

                    'amount_unit' =>
                        'select_product_page.amount_unit',

                    'currency' =>
                        'select_product_page.currency',
                ],

                'settings' => [
                    'step_icon' =>
                        $settings['step_icon']
                        ?? 'tabler-bottle',

                    'category_primary_icon' =>
                        $settings['category_primary_icon']
                        ?? 'tabler-basket',

                    'category_secondary_icon' =>
                        $settings['category_secondary_icon']
                        ?? 'tabler-droplet',

                    'selected_product_icon' =>
                        $settings['selected_product_icon']
                        ?? 'tabler-check',

                    'amount_section_icon' =>
                        $settings['amount_section_icon']
                        ?? 'tabler-basket',

                    'total_price_icon' =>
                        $settings['total_price_icon']
                        ?? 'tabler-wallet',

                    'show_home_button' => (bool) (
                        $settings['show_home_button']
                        ?? true
                    ),

                    'home_button_icon' =>
                        $settings['home_button_icon']
                        ?? 'tabler-home',

                    'home_button_action' =>
                        $settings['home_button_action']
                        ?? 'first_page',

                    'show_confirm_button' => (bool) (
                        $settings['show_confirm_button']
                        ?? true
                    ),

                    'confirm_button_icon' =>
                        $settings['confirm_button_icon']
                        ?? 'tabler-chevron-right',

                    'confirm_button_action' =>
                        $settings['confirm_button_action']
                        ?? 'promotion_page',
                ],
            ],

            'product_types' => collect($productGroups)
                ->map(function ($group) use ($products) {
                    $groupProducts = $products
                        ->where(
                            'product_type',
                            $group['code']
                        )
                        ->values();

                    return [
                        'code' => $group['code'],
                        'name' => $group['name'],
                        'icon' => $group['icon'],

                        'products' => $groupProducts
                            ->map(function ($product) {
                                return [
                                    'id' => $product->id,

                                    'product_type' =>
                                        $product->product_type,

                                    'name' =>
                                        $product->name,

                                    'sku' =>
                                        $product->sku
                                        ?? null,

                                    'description' =>
                                        $product->short_detail
                                        ?? $product->description
                                        ?? null,

                                    'image_url' =>
                                        $product->image_url
                                        ?? (
                                            !empty($product->image)
                                                ? url(
                                                    'storage/'
                                                    . $product->image
                                                )
                                                : null
                                        ),

                                    'price' => (float) (
                                        $product->price
                                        ?? 0
                                    ),

                                    'special_price' =>
                                        !is_null(
                                            $product->special_price
                                            ?? null
                                        )
                                            ? (float) $product
                                                ->special_price
                                            : null,

                                    'amount_options' =>
                                        $this->formatProductAmounts(
                                            $product
                                        ),

                                    'sort_order' => (int) (
                                        $product->sort_order
                                        ?? 0
                                    ),

                                    'is_active' => isset($product->is_active)
                                        ? (bool) $product->is_active
                                        : true,
                                ];
                            })
                            ->values(),
                    ];
                })
                ->values(),
        ];

        $themeData = $this->getThemeWithLanguages();

        $responseData['theme'] = $themeData['theme'];
        $responseData['languages'] = $themeData['languages'];

        return response()->json([
            'success' => true,
            'message' =>
                'ดึงข้อมูลหน้าเลือกสินค้าสำเร็จ',
            'data' => $responseData,
        ]);
    } catch (Throwable $exception) {
        Log::error('Select product API error', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' =>
                'ไม่สามารถดึงข้อมูลหน้าเลือกสินค้าได้',

            'error' => config('app.debug')
                ? $exception->getMessage()
                : null,
        ], 500);
    }
}
private function formatProductAmounts(
    Product $product
): array {
    $amountOptions = [];

    /*
    |--------------------------------------------------------------------------
    | รองรับ JSON amount_options
    |--------------------------------------------------------------------------
    | ตัวอย่าง:
    | [
    |   {"amount":1250,"unit":"ml","price":115},
    |   {"amount":2500,"unit":"ml","price":220}
    | ]
    */
    $storedOptions = $product->amount_options ?? null;

    if (is_string($storedOptions)) {
        $storedOptions = json_decode(
            $storedOptions,
            true
        );
    }

    if (
        is_array($storedOptions)
        && count($storedOptions) > 0
    ) {
        foreach ($storedOptions as $index => $option) {
            $amountOptions[] = [
                'id' => $option['id']
                    ?? $index + 1,

                'amount' => (float) (
                    $option['amount']
                    ?? 0
                ),

                'unit' => $option['unit']
                    ?? 'ml',

                'price' => (float) (
                    $option['price']
                    ?? $product->price
                    ?? 0
                ),

                'special_price' => isset(
                    $option['special_price']
                )
                    ? (float) $option[
                        'special_price'
                    ]
                    : null,

                'is_default' => (bool) (
                    $option['is_default']
                    ?? $index === 0
                ),
            ];
        }

        return $amountOptions;
    }

    /*
    |--------------------------------------------------------------------------
    | Fallback จากข้อมูล Product ปกติ
    |--------------------------------------------------------------------------
    */
    return [
        [
            'id' => $product->id,
            'amount' => (float) (
                $product->volume
                ?? $product->amount
                ?? $product->size
                ?? 0
            ),

            'unit' => $product->unit
                ?? 'ml',

            'price' => (float) (
                $product->price
                ?? 0
            ),

            'special_price' => !is_null(
                $product->special_price
                ?? null
            )
                ? (float) $product->special_price
                : null,

            'is_default' => true,
        ],
    ];
}

    /**
     * ส่ง Theme พร้อมภาษาที่เปิดใช้งานจากตาราง frontend_languages
     */
    private function getThemeWithLanguages(): array
    {
        $theme = $this->getActiveTheme();

        $languages = FrontendLanguage::query()
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $defaultLanguage = $languages->firstWhere('is_default', true)
            ?? $languages->first();

        return [
            'theme' => $theme
                ? $this->formatTheme($theme)
                : null,

            'languages' => [
                'default' => $defaultLanguage
                    ? $this->formatLanguage($defaultLanguage)
                    : null,

                'items' => $languages
                    ->map(fn (FrontendLanguage $language) =>
                        $this->formatLanguage($language)
                    )
                    ->values(),
            ],
        ];
    }

    /**
     * จัดรูปแบบ Theme โดยไม่ส่งค่าภาษาเดิมจากตาราง Theme
     */
    private function formatTheme(FrontendTheme $theme): array
    {
        return [
            'id' => $theme->id,
            'name' => $theme->name,
            'background_type' => $theme->background_type ?? 'color',
            'background_color' => $theme->background_color ?? '#DFF8FF',
            'background_image_url' => $theme->background_image_url ?? null,
            'primary_color' => $theme->primary_color ?? '#0877C9',
            'secondary_color' => $theme->secondary_color ?? '#6F63F6',
            'text_color' => $theme->text_color ?? '#111827',
            'button_text_color' => $theme->button_text_color ?? '#FFFFFF',
            'header_logo_left_url' => $theme->header_logo_left_url ?? null,
            'header_logo_right_1_url' => $theme->header_logo_right_1_url ?? null,
            'header_logo_right_2_url' => $theme->header_logo_right_2_url ?? null,
            'show_home_button' => (bool) ($theme->show_home_button ?? true),
            'home_button_text' => $theme->home_button_text ?? 'หน้าหลัก',
            'is_active' => isset($theme->is_active)
                ? (bool) $theme->is_active
                : true,
        ];
    }

    /**
     * จัดรูปแบบภาษา
     */
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
/**
 * ส่งข้อมูลตั้งค่าหน้าสรุปรายการ
 *
 * รายการสินค้าและยอดเงินจริงให้หน้าบ้านใช้ข้อมูลจาก cart/session
 * ส่ง Theme ที่กำลังใช้งานและภาษามาพร้อมกันทุกครั้ง
 */
public function orderSummary(): JsonResponse {
    try {
        $page = FrontendPage::query()
            ->where('screen_key', 'order_summary_page')
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลหน้าสรุปรายการ',
                'data' => null,
            ], 404);
        }

        $settings = $page->settings_json ?? [];

        $responseData = [
            'page' => [
                'id' => $page->id,
                'screen_key' => $page->screen_key,
                'name' => $page->name,
                'title' => $page->title,
                'subtitle' => $page->subtitle,
                'remark' => $page->remark,

                'is_active' => isset($page->is_active)
                    ? (bool) $page->is_active
                    : true,

                'translation_keys' => [
                    'title' =>
                        'order_summary_page.title',

                    'subtitle' =>
                        'order_summary_page.subtitle',

                    'order_list' =>
                        'order_summary_page.order_list',

                    'quantity' =>
                        'order_summary_page.quantity',

                    'promotion_discount' =>
                        'order_summary_page.promotion_discount',

                    'point_discount' =>
                        'order_summary_page.point_discount',

                    'net_total' =>
                        'order_summary_page.net_total',

                    'currency' =>
                        'order_summary_page.currency',

                    'back_button' =>
                        'order_summary_page.back_button',

                    'confirm_button' =>
                        'order_summary_page.confirm_button',
                ],

                'settings' => [
                    'step_icon' =>
                        $settings['step_icon']
                        ?? 'tabler-list-details',

                    'order_summary_icon' =>
                        $settings['order_summary_icon']
                        ?? 'tabler-shopping-bag',

                    'discount_summary_icon' =>
                        $settings['discount_summary_icon']
                        ?? 'tabler-discount',

                    'net_total_icon' =>
                        $settings['net_total_icon']
                        ?? 'tabler-wallet',

                    'show_back_button' => (bool) (
                        $settings['show_back_button']
                        ?? true
                    ),

                    'back_button_icon' =>
                        $settings['back_button_icon']
                        ?? 'tabler-chevron-left',

                    'back_button_action' =>
                        $settings['back_button_action']
                        ?? 'select_product_page',

                    'show_confirm_button' => (bool) (
                        $settings['show_confirm_button']
                        ?? true
                    ),

                    'confirm_button_icon' =>
                        $settings['confirm_button_icon']
                        ?? 'tabler-chevron-right',

                    'confirm_button_action' =>
                        $settings['confirm_button_action']
                        ?? 'promotion_page',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | แจ้งให้หน้าบ้านรู้ว่า Order Data มาจาก Cart/Session
            |--------------------------------------------------------------------------
            */
            'order_data_source' => 'client_cart',
        ];

        if ($request->boolean('include_theme')) {
            $themeData = $this->getThemeWithLanguages();

            $responseData['theme'] =
                $themeData['theme'];

            $responseData['languages'] =
                $themeData['languages'];
        }

        return response()->json([
            'success' => true,
            'message' => 'ดึงข้อมูลหน้าสรุปรายการสำเร็จ',
            'data' => $responseData,
        ]);
    } catch (Throwable $exception) {
        Log::error('Order summary page API error', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' =>
                'ไม่สามารถดึงข้อมูลหน้าสรุปรายการได้',

            'error' => config('app.debug')
                ? $exception->getMessage()
                : null,
        ], 500);
    }
}
}

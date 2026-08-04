<?php

namespace App\Http\Controllers\frontend_theme;

use App\Http\Controllers\Controller;
use App\Models\FrontendLanguage;
use App\Models\FrontendTranslation;
use App\Models\FrontendTranslationKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendTranslationController extends Controller
{
    /**
     * รายการภาษา เพื่อเลือกเข้าไปแก้ข้อความ
     */
    public function index()
    {
        $languages = FrontendLanguage::query()
            ->withCount('translations')
            ->orderByDesc('is_default')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $activeKeyCount = FrontendTranslationKey::query()
            ->where('is_active', true)
            ->count();

        return view(
            'content.pages.frontend.translations.index',
            compact(
                'languages',
                'activeKeyCount'
            )
        );
    }

    /**
     * หน้าแก้ข้อความแปลของภาษาที่เลือก
     */
    public function edit(
        Request $request,
        FrontendLanguage $language
    ) {
        $keyword = trim(
            (string) $request->get('keyword', '')
        );

        $group = trim(
            (string) $request->get('group', '')
        );

        $translationKeys = FrontendTranslationKey::query()
            ->where('is_active', true)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery
                        ->where('key', 'like', "%{$keyword}%")
                        ->orWhere(
                            'description',
                            'like',
                            "%{$keyword}%"
                        )
                        ->orWhere(
                            'default_value',
                            'like',
                            "%{$keyword}%"
                        );
                });
            })
            ->when($group !== '', function ($query) use ($group) {
                $query->where('group', $group);
            })
            ->orderBy('group')
            ->orderBy('id')
            ->get();

        $translations = FrontendTranslation::query()
            ->where('language_id', $language->id)
            ->whereIn(
                'translation_key_id',
                $translationKeys->pluck('id')
            )
            ->get()
            ->keyBy('translation_key_id');

        $groups = FrontendTranslationKey::query()
            ->where('is_active', true)
            ->whereNotNull('group')
            ->where('group', '!=', '')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        return view(
            'content.pages.frontend.translations.edit',
            compact(
                'language',
                'translationKeys',
                'translations',
                'groups',
                'keyword',
                'group'
            )
        );
    }

    /**
     * บันทึกข้อความแปล
     */
    public function update(
        Request $request,
        FrontendLanguage $language
    ) {
        $validated = $request->validate(
            [
                'translations' => [
                    'nullable',
                    'array',
                ],

                'translations.*' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'translations.*.string' =>
                    'ข้อความแปลต้องเป็นข้อความ',

                'translations.*.max' =>
                    'ข้อความแปลต้องไม่เกิน 5,000 ตัวอักษร',
            ]
        );

        $translations = $validated['translations'] ?? [];

        $validKeyIds = FrontendTranslationKey::query()
            ->where('is_active', true)
            ->whereIn(
                'id',
                array_keys($translations)
            )
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        DB::transaction(function () use (
            $language,
            $translations,
            $validKeyIds
        ) {
            foreach ($validKeyIds as $translationKeyId) {
                $value = trim(
                    (string) (
                        $translations[$translationKeyId]
                        ?? ''
                    )
                );

                FrontendTranslation::updateOrCreate(
                    [
                        'language_id' => $language->id,

                        'translation_key_id' =>
                            $translationKeyId,
                    ],
                    [
                        /*
                        |--------------------------------------------------------------------------
                        | ถ้าว่างให้เก็บ null เพื่อให้ API fallback ไป default_value
                        |--------------------------------------------------------------------------
                        */
                        'value' => $value !== ''
                            ? $value
                            : null,
                    ]
                );
            }
        });

        return redirect()
            ->route(
                'frontend.translations.edit',
                [
                    'language' => $language->id,
                    'keyword' => $request->get('keyword'),
                    'group' => $request->get('group'),
                ]
            )
            ->with(
                'success',
                "บันทึกข้อความภาษา {$language->name} สำเร็จ"
            );
    }

    /**
     * สร้างแถวคำแปลที่ยังขาดสำหรับภาษานี้
     */
    public function sync(
        FrontendLanguage $language
    ) {
        $translationKeys = FrontendTranslationKey::query()
            ->where('is_active', true)
            ->get();

        DB::transaction(function () use (
            $language,
            $translationKeys
        ) {
            foreach ($translationKeys as $translationKey) {
                FrontendTranslation::firstOrCreate(
                    [
                        'language_id' => $language->id,

                        'translation_key_id' =>
                            $translationKey->id,
                    ],
                    [
                        /*
                        |--------------------------------------------------------------------------
                        | ภาษาเริ่มต้นใช้ default_value
                        | ภาษาอื่นยังไม่ใส่ค่า ให้ API fallback เอง
                        |--------------------------------------------------------------------------
                        */
                        'value' => $language->is_default
                            ? $translationKey->default_value
                            : null,
                    ]
                );
            }
        });

        return redirect()
            ->route(
                'frontend.translations.edit',
                $language
            )
            ->with(
                'success',
                "สร้างรายการข้อความของภาษา {$language->name} ครบแล้ว"
            );
    }
}

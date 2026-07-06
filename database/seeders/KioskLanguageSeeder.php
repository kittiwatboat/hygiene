<?php

namespace Database\Seeders;

use App\Models\KioskLanguage;
use Illuminate\Database\Seeder;

class KioskLanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'code' => 'th',
                'name' => 'Thai',
                'native_name' => 'ภาษาไทย',
                'locale' => 'th_TH',
                'flag_image' => 'th.png',
                'sort_order' => 1,
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'locale' => 'en_US',
                'flag_image' => 'en.png',
                'sort_order' => 2,
            ],
            [
                'code' => 'zh',
                'name' => 'Chinese',
                'native_name' => '中文',
                'locale' => 'zh_CN',
                'flag_image' => 'zh.png',
                'sort_order' => 3,
            ],
            [
                'code' => 'ja',
                'name' => 'Japanese',
                'native_name' => '日本語',
                'locale' => 'ja_JP',
                'flag_image' => 'ja.png',
                'sort_order' => 4,
            ],
            [
                'code' => 'ko',
                'name' => 'Korean',
                'native_name' => '한국어',
                'locale' => 'ko_KR',
                'flag_image' => 'ko.png',
                'sort_order' => 5,
            ],
            [
                'code' => 'lo',
                'name' => 'Lao',
                'native_name' => 'ລາວ',
                'locale' => 'lo_LA',
                'flag_image' => 'lo.png',
                'sort_order' => 6,
            ],
            [
                'code' => 'my',
                'name' => 'Myanmar',
                'native_name' => 'မြန်မာ',
                'locale' => 'my_MM',
                'flag_image' => 'my.png',
                'sort_order' => 7,
            ],
        ];

        foreach ($languages as $language) {
            KioskLanguage::updateOrCreate(
                [
                    'code' => $language['code'],
                ],
                [
                    'name' => $language['name'],
                    'native_name' => $language['native_name'],
                    'locale' => $language['locale'],
                    'flag_image' => $language['flag_image'],
                    'sort_order' => $language['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}

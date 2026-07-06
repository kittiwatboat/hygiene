<?php

namespace Database\Seeders;

use App\Models\KioskLanguage;
use App\Models\KioskLanguageSetting;
use Illuminate\Database\Seeder;

class KioskLanguageSettingSeeder extends Seeder
{
    public function run(): void
    {
        $languages = KioskLanguage::whereIn('code', ['th', 'en', 'zh'])
            ->orderByRaw("FIELD(code, 'th', 'en', 'zh')")
            ->get();

        foreach ($languages as $index => $language) {
            KioskLanguageSetting::updateOrCreate(
                [
                    'language_id' => $language->id,
                ],
                [
                    'sort_order' => $index + 1,
                    'is_default' => $language->code === 'th',
                    'is_active' => true,
                ]
            );
        }
    }
}

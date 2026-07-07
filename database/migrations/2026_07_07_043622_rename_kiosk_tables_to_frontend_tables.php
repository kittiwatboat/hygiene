<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kiosk_languages') && !Schema::hasTable('frontend_languages')) {
            Schema::rename('kiosk_languages', 'frontend_languages');
        }

        if (Schema::hasTable('kiosk_language_settings') && !Schema::hasTable('frontend_language_settings')) {
            Schema::rename('kiosk_language_settings', 'frontend_language_settings');
        }

        if (Schema::hasTable('kiosk_machine_language_settings') && !Schema::hasTable('frontend_machine_language_settings')) {
            Schema::rename('kiosk_machine_language_settings', 'frontend_machine_language_settings');
        }

        if (Schema::hasTable('kiosk_themes') && !Schema::hasTable('frontend_themes')) {
            Schema::rename('kiosk_themes', 'frontend_themes');
        }

        if (Schema::hasTable('kiosk_screens') && !Schema::hasTable('frontend_pages')) {
            Schema::rename('kiosk_screens', 'frontend_pages');
        }

        if (Schema::hasTable('kiosk_screen_media') && !Schema::hasTable('frontend_page_media')) {
            Schema::rename('kiosk_screen_media', 'frontend_page_media');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('frontend_page_media') && !Schema::hasTable('kiosk_screen_media')) {
            Schema::rename('frontend_page_media', 'kiosk_screen_media');
        }

        if (Schema::hasTable('frontend_pages') && !Schema::hasTable('kiosk_screens')) {
            Schema::rename('frontend_pages', 'kiosk_screens');
        }

        if (Schema::hasTable('frontend_themes') && !Schema::hasTable('kiosk_themes')) {
            Schema::rename('frontend_themes', 'kiosk_themes');
        }

        if (Schema::hasTable('frontend_machine_language_settings') && !Schema::hasTable('kiosk_machine_language_settings')) {
            Schema::rename('frontend_machine_language_settings', 'kiosk_machine_language_settings');
        }

        if (Schema::hasTable('frontend_language_settings') && !Schema::hasTable('kiosk_language_settings')) {
            Schema::rename('frontend_language_settings', 'kiosk_language_settings');
        }

        if (Schema::hasTable('frontend_languages') && !Schema::hasTable('kiosk_languages')) {
            Schema::rename('frontend_languages', 'kiosk_languages');
        }
    }
};

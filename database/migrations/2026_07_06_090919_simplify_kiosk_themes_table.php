<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kiosk_themes', function (Blueprint $table) {
            if (!Schema::hasColumn('kiosk_themes', 'text_color')) {
                $table->string('text_color', 50)->default('#111827')->after('slug');
            }

            if (!Schema::hasColumn('kiosk_themes', 'background_type')) {
                $table->string('background_type', 30)->default('color')->after('text_color');
            }

            if (!Schema::hasColumn('kiosk_themes', 'background_color')) {
                $table->string('background_color', 50)->default('#FFFFFF')->after('background_type');
            }

            if (!Schema::hasColumn('kiosk_themes', 'background_image')) {
                $table->string('background_image')->nullable()->after('background_color');
            }

            if (!Schema::hasColumn('kiosk_themes', 'background_video')) {
                $table->string('background_video')->nullable()->after('background_image');
            }

            if (!Schema::hasColumn('kiosk_themes', 'button_color')) {
                $table->string('button_color', 50)->default('#00AEEF')->after('background_video');
            }

            if (!Schema::hasColumn('kiosk_themes', 'button_text_color')) {
                $table->string('button_text_color', 50)->default('#FFFFFF')->after('button_color');
            }

            if (!Schema::hasColumn('kiosk_themes', 'button_hover_border_color')) {
                $table->string('button_hover_border_color', 50)->default('#00AEEF')->after('button_text_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kiosk_themes', function (Blueprint $table) {
            $columns = [
                'background_type',
                'background_image',
                'background_video',
                'button_color',
                'button_hover_border_color',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('kiosk_themes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

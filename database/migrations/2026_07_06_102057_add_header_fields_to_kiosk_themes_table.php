<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kiosk_themes', function (Blueprint $table) {
            if (!Schema::hasColumn('kiosk_themes', 'header_type')) {
                $table->string('header_type', 20)->default('none')->after('button_hover_border_color');
            }

            if (!Schema::hasColumn('kiosk_themes', 'header_background_color')) {
                $table->string('header_background_color', 50)->nullable()->after('header_type');
            }

            if (!Schema::hasColumn('kiosk_themes', 'header_background_image')) {
                $table->string('header_background_image')->nullable()->after('header_background_color');
            }

            if (!Schema::hasColumn('kiosk_themes', 'header_background_video')) {
                $table->string('header_background_video')->nullable()->after('header_background_image');
            }

            if (!Schema::hasColumn('kiosk_themes', 'header_logo_main')) {
                $table->string('header_logo_main')->nullable()->after('header_background_video');
            }

            if (!Schema::hasColumn('kiosk_themes', 'header_logo_right_1')) {
                $table->string('header_logo_right_1')->nullable()->after('header_logo_main');
            }

            if (!Schema::hasColumn('kiosk_themes', 'header_logo_right_2')) {
                $table->string('header_logo_right_2')->nullable()->after('header_logo_right_1');
            }

            if (!Schema::hasColumn('kiosk_themes', 'header_height')) {
                $table->unsignedInteger('header_height')->default(82)->after('header_logo_right_2');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kiosk_themes', function (Blueprint $table) {
            $table->dropColumn([
                'header_type',
                'header_background_color',
                'header_background_image',
                'header_background_video',
                'header_logo_main',
                'header_logo_right_1',
                'header_logo_right_2',
                'header_height',
            ]);
        });
    }
};

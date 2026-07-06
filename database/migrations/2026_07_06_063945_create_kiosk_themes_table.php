<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kiosk_themes')) {
            Schema::create('kiosk_themes', function (Blueprint $table) {
                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Basic Info
                |--------------------------------------------------------------------------
                */
                $table->string('name');
                $table->string('slug')->unique();

                /*
                |--------------------------------------------------------------------------
                | Main Colors
                |--------------------------------------------------------------------------
                | เป็นสีหลักของธีม ใส่สีอะไรก็ได้ เช่น #00AEEF, rgba(...), var(...)
                */
                $table->string('primary_color', 50)->default('#00AEEF');
                $table->string('secondary_color', 50)->default('#FFFFFF');
                $table->string('accent_color', 50)->default('#F7941D');

                /*
                |--------------------------------------------------------------------------
                | Page Colors
                |--------------------------------------------------------------------------
                */
                $table->string('background_color', 50)->default('#FFFFFF');
                $table->string('text_color', 50)->default('#111827');
                $table->string('muted_text_color', 50)->default('#6B7280');

                /*
                |--------------------------------------------------------------------------
                | Button Colors
                |--------------------------------------------------------------------------
                */
                $table->string('button_background_color', 50)->default('#00AEEF');
                $table->string('button_text_color', 50)->default('#FFFFFF');
                $table->string('button_border_color', 50)->nullable();

                $table->string('button_hover_background_color', 50)->nullable();
                $table->string('button_hover_text_color', 50)->nullable();

                /*
                |--------------------------------------------------------------------------
                | Card Colors
                |--------------------------------------------------------------------------
                */
                $table->string('card_background_color', 50)->default('#FFFFFF');
                $table->string('card_text_color', 50)->default('#111827');
                $table->string('card_border_color', 50)->nullable();

                /*
                |--------------------------------------------------------------------------
                | Status Colors
                |--------------------------------------------------------------------------
                */
                $table->string('success_color', 50)->default('#22C55E');
                $table->string('warning_color', 50)->default('#F59E0B');
                $table->string('danger_color', 50)->default('#EF4444');
                $table->string('info_color', 50)->default('#3B82F6');

                /*
                |--------------------------------------------------------------------------
                | Typography / Shape
                |--------------------------------------------------------------------------
                */
                $table->string('font_family')->nullable();

                $table->unsignedInteger('button_radius')->default(24);
                $table->unsignedInteger('card_radius')->default(28);
                $table->unsignedInteger('input_radius')->default(16);

                /*
                |--------------------------------------------------------------------------
                | Logo ของธีม
                |--------------------------------------------------------------------------
                | ไม่ใช่ภาพพื้นหลังของแต่ละหน้า
                | แนะนำเก็บไฟล์ที่ public_html/assets/img/kiosk/themes
                */
                $table->string('logo')->nullable();

                /*
                |--------------------------------------------------------------------------
                | Extra Settings
                |--------------------------------------------------------------------------
                | ใช้เก็บค่าพิเศษ เช่น gradient, shadow, overlay, spacing
                */
                $table->json('settings_json')->nullable();

                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);

                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['is_default', 'is_active']);
                $table->index('slug');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kiosk_themes');
    }
};

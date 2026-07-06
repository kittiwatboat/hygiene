<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kiosk_layouts')) {
            Schema::create('kiosk_layouts', function (Blueprint $table) {
                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Theme
                |--------------------------------------------------------------------------
                | Layout นี้ใช้คู่กับ Theme ไหน
                */
                $table->foreignId('theme_id')
                    ->nullable()
                    ->constrained('kiosk_themes')
                    ->nullOnDelete();

                $table->string('name')->default('Default Layout');

                /*
                |--------------------------------------------------------------------------
                | Top Section
                |--------------------------------------------------------------------------
                | ส่วนบนสุดที่ใช้ร่วมกันทุกหน้า
                */
                $table->boolean('show_top_section')->default(true);

                /*
                |--------------------------------------------------------------------------
                | image / video / none
                |--------------------------------------------------------------------------
                */
                $table->string('top_section_type', 30)->default('image');

                /*
                |--------------------------------------------------------------------------
                | Media หลักของส่วนบน
                |--------------------------------------------------------------------------
                | แนะนำเก็บไฟล์ที่:
                | public_html/assets/img/kiosk/layouts
                | public_html/assets/videos/kiosk/layouts
                */
                $table->string('top_section_image')->nullable();
                $table->string('top_section_video')->nullable();

                /*
                |--------------------------------------------------------------------------
                | ความสูงส่วนบน หน่วย px
                |--------------------------------------------------------------------------
                */
                $table->unsignedInteger('top_section_height')->default(220);

                /*
                |--------------------------------------------------------------------------
                | Overlay Logo / Icon
                |--------------------------------------------------------------------------
                | ใช้สำหรับ logo หรือ icon ที่ทับบนรูป/วิดีโอด้านบน
                */
                $table->boolean('show_overlay_logo')->default(true);
                $table->string('overlay_logo')->nullable();

                $table->boolean('show_overlay_icon')->default(false);
                $table->string('overlay_icon')->nullable();

                /*
                |--------------------------------------------------------------------------
                | ตำแหน่ง overlay
                |--------------------------------------------------------------------------
                | left / center / right / custom
                */
                $table->string('overlay_position', 30)->default('center');

                /*
                |--------------------------------------------------------------------------
                | custom position
                |--------------------------------------------------------------------------
                | ใช้ตอน overlay_position = custom
                */
                $table->integer('overlay_top')->nullable();
                $table->integer('overlay_left')->nullable();
                $table->integer('overlay_right')->nullable();
                $table->integer('overlay_bottom')->nullable();

                /*
                |--------------------------------------------------------------------------
                | ขนาด overlay
                |--------------------------------------------------------------------------
                */
                $table->unsignedInteger('overlay_width')->nullable();
                $table->unsignedInteger('overlay_height')->nullable();

                /*
                |--------------------------------------------------------------------------
                | Overlay / Mask
                |--------------------------------------------------------------------------
                | เช่น rgba(0,0,0,.25)
                */
                $table->boolean('show_media_overlay')->default(false);
                $table->string('media_overlay_color', 100)->nullable();

                /*
                |--------------------------------------------------------------------------
                | Extra Settings
                |--------------------------------------------------------------------------
                */
                $table->json('settings_json')->nullable();

                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);

                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['theme_id', 'is_default', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kiosk_layouts');
    }
};

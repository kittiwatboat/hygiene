<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kiosk_languages')) {
            Schema::create('kiosk_languages', function (Blueprint $table) {
                $table->id();

                /*
                |--------------------------------------------------------------------------
                | code
                |--------------------------------------------------------------------------
                | th, en, zh, ja, ko, lo, my
                */
                $table->string('code', 20)->unique();

                /*
                |--------------------------------------------------------------------------
                | name
                |--------------------------------------------------------------------------
                | English name เช่น Thai, English, Chinese
                */
                $table->string('name', 100);

                /*
                |--------------------------------------------------------------------------
                | native_name
                |--------------------------------------------------------------------------
                | ชื่อภาษาที่แสดงหน้าตู้ เช่น ภาษาไทย, English, 中文
                */
                $table->string('native_name', 100);

                /*
                |--------------------------------------------------------------------------
                | flag_image
                |--------------------------------------------------------------------------
                | เก็บชื่อไฟล์รูปธง เช่น th.png, en.png, zh.png
                | path จริงแนะนำไว้ที่ public_html/assets/img/languages
                */
                $table->string('flag_image')->nullable();

                /*
                |--------------------------------------------------------------------------
                | locale
                |--------------------------------------------------------------------------
                | สำหรับใช้กับระบบแปลภาษา เช่น th_TH, en_US, zh_CN
                */
                $table->string('locale', 50)->nullable();

                $table->unsignedInteger('sort_order')->default(0);

                $table->boolean('is_active')->default(true);

                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['is_active', 'sort_order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kiosk_languages');
    }
};

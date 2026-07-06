<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kiosk_screens')) {
            Schema::create('kiosk_screens', function (Blueprint $table) {
                $table->id();

                $table->foreignId('theme_id')
                    ->nullable()
                    ->constrained('kiosk_themes')
                    ->nullOnDelete();

                $table->foreignId('layout_id')
                    ->nullable()
                    ->constrained('kiosk_layouts')
                    ->nullOnDelete();

                /*
                |--------------------------------------------------------------------------
                | screen_key
                |--------------------------------------------------------------------------
                | idle, language, select_product, select_amount, promotion,
                | member, payment, dispensing, receipt, thank_you, error
                */
                $table->string('screen_key', 100);

                $table->string('name');
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();

                /*
                |--------------------------------------------------------------------------
                | Background
                |--------------------------------------------------------------------------
                | none / color / image / video
                */
                $table->string('background_type', 30)->default('color');
                $table->string('background_color', 50)->nullable();
                $table->string('background_image')->nullable();
                $table->string('background_video')->nullable();

                /*
                |--------------------------------------------------------------------------
                | Button หลักของหน้านั้น ๆ
                |--------------------------------------------------------------------------
                */
                $table->string('button_text')->nullable();
                $table->string('button_icon')->nullable();
                $table->string('button_action')->nullable();

                /*
                |--------------------------------------------------------------------------
                | settings_json
                |--------------------------------------------------------------------------
                | เก็บค่าเฉพาะของแต่ละหน้า เช่น หลายภาษา, timeout, layout, columns
                */
                $table->json('settings_json')->nullable();

                $table->unsignedInteger('sort_order')->default(0);

                $table->boolean('is_required')->default(false);
                $table->boolean('is_active')->default(true);

                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->unique(['theme_id', 'screen_key']);
                $table->index(['screen_key', 'is_active']);
                $table->index(['theme_id', 'sort_order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kiosk_screens');
    }
};

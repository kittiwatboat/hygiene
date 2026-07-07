<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('frontend_page_media')) {
            Schema::create('frontend_page_media', function (Blueprint $table) {
                $table->id();

                $table->foreignId('frontend_page_id')
                    ->constrained('frontend_pages')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | image / video
                |--------------------------------------------------------------------------
                */
                $table->string('media_type', 20);

                /*
                |--------------------------------------------------------------------------
                | เก็บชื่อไฟล์
                |--------------------------------------------------------------------------
                */
                $table->string('file_path');

                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();

                /*
                |--------------------------------------------------------------------------
                | ใช้กับรูปภาพ เช่น 5 วินาที
                | ถ้าเป็น video จะใช้ตามความยาววิดีโอ หรือจะกำหนดเองก็ได้
                |--------------------------------------------------------------------------
                */
                $table->unsignedInteger('duration_seconds')->default(5);

                /*
                |--------------------------------------------------------------------------
                | cover / contain
                |--------------------------------------------------------------------------
                */
                $table->string('object_fit', 20)->default('cover');

                /*
                |--------------------------------------------------------------------------
                | ลำดับ slide
                |--------------------------------------------------------------------------
                */
                $table->unsignedInteger('sort_order')->default(0);

                $table->boolean('is_active')->default(true);

                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['frontend_page_id', 'is_active', 'sort_order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_page_media');
    }
};

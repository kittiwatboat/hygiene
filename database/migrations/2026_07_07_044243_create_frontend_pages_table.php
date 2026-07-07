<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('frontend_pages')) {
            Schema::create('frontend_pages', function (Blueprint $table) {
                $table->id();

                $table->string('page_key')->unique(); // first_page
                $table->string('name'); // หน้าแรก

                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();

                $table->boolean('is_active')->default(true);
                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['page_key', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_pages');
    }
};

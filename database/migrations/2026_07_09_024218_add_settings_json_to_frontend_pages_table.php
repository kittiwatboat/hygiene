<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('frontend_pages', 'settings_json')) {
                $table->json('settings_json')->nullable()->after('subtitle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('frontend_pages', function (Blueprint $table) {
            if (Schema::hasColumn('frontend_pages', 'settings_json')) {
                $table->dropColumn('settings_json');
            }
        });
    }
};

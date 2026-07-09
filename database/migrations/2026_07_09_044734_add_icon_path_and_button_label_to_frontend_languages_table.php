<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_languages', function (Blueprint $table) {
            if (!Schema::hasColumn('frontend_languages', 'icon_path')) {
                $table->string('icon_path')->nullable()->after('native_name');
            }

            if (!Schema::hasColumn('frontend_languages', 'button_label')) {
                $table->string('button_label')->nullable()->after('icon_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('frontend_languages', function (Blueprint $table) {
            if (Schema::hasColumn('frontend_languages', 'button_label')) {
                $table->dropColumn('button_label');
            }

            if (Schema::hasColumn('frontend_languages', 'icon_path')) {
                $table->dropColumn('icon_path');
            }
        });
    }
};

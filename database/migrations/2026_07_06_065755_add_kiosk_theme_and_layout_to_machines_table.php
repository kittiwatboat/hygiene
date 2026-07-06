<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (!Schema::hasColumn('machines', 'theme_id')) {
                $table->foreignId('theme_id')
                    ->nullable()
                    ->after('location_id')
                    ->constrained('kiosk_themes')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('machines', 'layout_id')) {
                $table->foreignId('layout_id')
                    ->nullable()
                    ->after('theme_id')
                    ->constrained('kiosk_layouts')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (Schema::hasColumn('machines', 'layout_id')) {
                $table->dropForeign(['layout_id']);
                $table->dropColumn('layout_id');
            }

            if (Schema::hasColumn('machines', 'theme_id')) {
                $table->dropForeign(['theme_id']);
                $table->dropColumn('theme_id');
            }
        });
    }
};

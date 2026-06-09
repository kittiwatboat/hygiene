<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (!Schema::hasColumn('locations', 'province_id')) {
                $table->unsignedInteger('province_id')->nullable()->after('address');
            }

            if (!Schema::hasColumn('locations', 'district_id')) {
                $table->unsignedInteger('district_id')->nullable()->after('province_id');
            }

            if (!Schema::hasColumn('locations', 'subdistrict_id')) {
                $table->unsignedInteger('subdistrict_id')->nullable()->after('district_id');
            }

            if (!Schema::hasColumn('locations', 'province')) {
                $table->string('province')->nullable()->after('subdistrict_id');
            }

            if (!Schema::hasColumn('locations', 'district')) {
                $table->string('district')->nullable()->after('province');
            }

            if (!Schema::hasColumn('locations', 'sub_district')) {
                $table->string('sub_district')->nullable()->after('district');
            }

            if (!Schema::hasColumn('locations', 'postcode')) {
                $table->string('postcode')->nullable()->after('sub_district');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (Schema::hasColumn('locations', 'subdistrict_id')) {
                $table->dropColumn('subdistrict_id');
            }

            if (Schema::hasColumn('locations', 'district_id')) {
                $table->dropColumn('district_id');
            }

            if (Schema::hasColumn('locations', 'province_id')) {
                $table->dropColumn('province_id');
            }

            if (Schema::hasColumn('locations', 'postcode')) {
                $table->dropColumn('postcode');
            }

            if (Schema::hasColumn('locations', 'sub_district')) {
                $table->dropColumn('sub_district');
            }

            if (Schema::hasColumn('locations', 'district')) {
                $table->dropColumn('district');
            }

            if (Schema::hasColumn('locations', 'province')) {
                $table->dropColumn('province');
            }
        });
    }
};

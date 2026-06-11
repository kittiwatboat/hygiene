<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('machines')) {
            if (Schema::hasColumn('machines', 'machine_code')) {
                DB::statement("ALTER TABLE machines MODIFY machine_code VARCHAR(255) NULL");
            }

            if (Schema::hasColumn('machines', 'machine_name')) {
                DB::statement("ALTER TABLE machines MODIFY machine_name VARCHAR(255) NULL");
            }

            if (Schema::hasColumn('machines', 'location_name')) {
                DB::statement("ALTER TABLE machines MODIFY location_name VARCHAR(255) NULL");
            }

            if (Schema::hasColumn('machines', 'tank_capacity_liter')) {
                DB::statement("ALTER TABLE machines MODIFY tank_capacity_liter DECIMAL(10,2) NULL DEFAULT 0");
            }

            if (Schema::hasColumn('machines', 'current_stock_liter')) {
                DB::statement("ALTER TABLE machines MODIFY current_stock_liter DECIMAL(10,2) NULL DEFAULT 0");
            }

            if (Schema::hasColumn('machines', 'volume_per_press_ml')) {
                DB::statement("ALTER TABLE machines MODIFY volume_per_press_ml DECIMAL(10,2) NULL DEFAULT 0");
            }

            if (Schema::hasColumn('machines', 'total_press_count')) {
                DB::statement("ALTER TABLE machines MODIFY total_press_count INT NULL DEFAULT 0");
            }

            if (Schema::hasColumn('machines', 'address')) {
                DB::statement("ALTER TABLE machines MODIFY address TEXT NULL");
            }

            if (Schema::hasColumn('machines', 'note')) {
                DB::statement("ALTER TABLE machines MODIFY note TEXT NULL");
            }
        }
    }

    public function down(): void
    {
        //
    }
};

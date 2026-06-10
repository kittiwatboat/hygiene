<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vending_machines') && !Schema::hasTable('machines')) {
            Schema::rename('vending_machines', 'machines');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('machines') && !Schema::hasTable('vending_machines')) {
            Schema::rename('machines', 'vending_machines');
        }
    }
};

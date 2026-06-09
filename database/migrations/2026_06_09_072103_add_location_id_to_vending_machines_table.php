<?php

use App\Models\Location;
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
        Schema::table('vending_machines', function (Blueprint $table) {
            if (!Schema::hasColumn('vending_machines', 'location_id')) {
                $table->foreignIdFor(Location::class)
                    ->nullable()
                    ->after('id')
                    ->constrained('locations')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vending_machines', function (Blueprint $table) {
            if (Schema::hasColumn('vending_machines', 'location_id')) {
                $table->dropConstrainedForeignId('location_id');
            }
        });
    }
};

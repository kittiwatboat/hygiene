<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machine_tanks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('machine_id')
                ->constrained('machines')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            $table->unsignedTinyInteger('tank_no')->comment('ช่องน้ำยา 1-3');
            $table->string('tank_name')->nullable();

            $table->decimal('capacity_liters', 10, 2)->default(0);
            $table->decimal('remaining_liters', 10, 2)->default(0);
            $table->decimal('low_stock_liters', 10, 2)->default(0);
            $table->decimal('empty_stock_liters', 10, 2)->default(0);

            $table->decimal('volume_per_press_ml', 10, 2)->default(0);
            $table->decimal('price_per_press', 10, 2)->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['machine_id', 'tank_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machine_tanks');
    }
};

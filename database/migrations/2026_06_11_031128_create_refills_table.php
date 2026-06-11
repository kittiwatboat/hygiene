<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('refills')) {
            Schema::create('refills', function (Blueprint $table) {
                $table->id();

                $table->foreignId('machine_id')
                    ->constrained('machines')
                    ->cascadeOnDelete();

                $table->foreignId('machine_tank_id')
                    ->constrained('machine_tanks')
                    ->cascadeOnDelete();

                $table->foreignId('product_id')
                    ->nullable()
                    ->constrained('products')
                    ->nullOnDelete();

                $table->decimal('before_liters', 10, 2)->default(0);
                $table->decimal('refill_liters', 10, 2)->default(0);
                $table->decimal('after_liters', 10, 2)->default(0);

                $table->foreignId('refill_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamp('refill_at')->nullable();
                $table->text('remark')->nullable();

                $table->timestamps();

                $table->index(['machine_id', 'machine_tank_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('refills');
    }
};

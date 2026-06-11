<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
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

                $table->unsignedInteger('press_count')->default(1);

                $table->decimal('volume_per_press_ml', 10, 2)->default(0);
                $table->decimal('volume_liters', 10, 3)->default(0);

                $table->decimal('price_per_press', 10, 2)->default(0);
                $table->decimal('amount', 10, 2)->default(0);

                $table->string('payment_method')->default('cash');
                $table->string('payment_status')->default('paid');
                $table->string('transaction_ref')->nullable();

                $table->timestamp('sold_at')->nullable();

                $table->foreignId('created_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->json('payload')->nullable();
                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['machine_id', 'machine_tank_id']);
                $table->index(['sold_at', 'payment_status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

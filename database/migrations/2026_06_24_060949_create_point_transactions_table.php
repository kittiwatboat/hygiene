<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('point_transactions')) {
            Schema::create('point_transactions', function (Blueprint $table) {
                $table->id();

                $table->foreignId('customer_id')
                    ->constrained('customers')
                    ->cascadeOnDelete();

                $table->foreignId('sale_id')
                    ->nullable()
                    ->constrained('sales')
                    ->nullOnDelete();

                $table->foreignId('promotion_id')
                    ->nullable()
                    ->constrained('promotions')
                    ->nullOnDelete();

                /*
                |--------------------------------------------------------------------------
                | earn    = ได้รับแต้ม
                | redeem  = ใช้แต้ม
                | adjust  = แอดมินปรับแต้ม
                | expire  = แต้มหมดอายุ
                | refund  = คืนแต้ม
                |--------------------------------------------------------------------------
                */
                $table->string('type', 30);

                /*
                |--------------------------------------------------------------------------
                | แต้มบวก เช่น 100
                | แต้มลบ เช่น -100
                |--------------------------------------------------------------------------
                */
                $table->bigInteger('points');

                $table->unsignedBigInteger('balance_before')->default(0);
                $table->unsignedBigInteger('balance_after')->default(0);

                $table->string('reference_no')->nullable();
                $table->string('description')->nullable();

                $table->timestamp('expired_at')->nullable();

                $table->foreignId('created_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamps();

                $table->index(['customer_id', 'type']);
                $table->index(['customer_id', 'created_at']);
                $table->index('expired_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};

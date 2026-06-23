<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('promotions')) {
            Schema::create('promotions', function (Blueprint $table) {
                $table->id();

                $table->string('name');
                $table->string('code')->nullable()->unique();
                $table->string('image')->nullable();

                /*
                | earn_points = ซื้อสินค้าแล้วได้รับแต้ม
                | redeem_discount = ใช้แต้มแลกส่วนลด
                | direct_discount = ลดราคาโดยไม่ใช้แต้ม
                */
                $table->string('promotion_type');

                /*
                | fixed = ลดเป็นจำนวนเงิน
                | percent = ลดเป็นเปอร์เซ็นต์
                */
                $table->string('discount_type')->nullable();
                $table->decimal('discount_value', 10, 2)->default(0);
                $table->decimal('max_discount', 10, 2)->nullable();

                $table->unsignedInteger('points_required')->default(0);
                $table->unsignedInteger('points_reward')->default(0);

                $table->decimal('minimum_amount', 10, 2)->default(0);

                /*
                | all = ใช้กับสินค้าทั้งหมด
                | product = ใช้กับสินค้าเฉพาะรายการ
                */
                $table->string('scope')->default('all');

                $table->foreignId('product_id')
                    ->nullable()
                    ->constrained('products')
                    ->nullOnDelete();

                $table->unsignedInteger('usage_limit')->nullable();
                $table->unsignedInteger('used_count')->default(0);

                $table->timestamp('start_at')->nullable();
                $table->timestamp('end_at')->nullable();

                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);

                $table->text('description')->nullable();
                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['promotion_type', 'is_active']);
                $table->index(['start_at', 'end_at']);
                $table->index(['scope', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};

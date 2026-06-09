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
        Schema::create('vending_machines', function (Blueprint $table) {
            $table->id();

            $table->string('machine_code')->unique()->comment('รหัสตู้');
            $table->string('machine_name')->comment('ชื่อตู้');

            $table->string('location_name')->nullable()->comment('ชื่อสถานที่ติดตั้ง');
            $table->text('address')->nullable()->comment('ที่อยู่');
            $table->decimal('latitude', 10, 7)->nullable()->comment('ละติจูด');
            $table->decimal('longitude', 10, 7)->nullable()->comment('ลองจิจูด');

            $table->decimal('tank_capacity_liter', 10, 2)->default(0)->comment('ความจุถังทั้งหมด ลิตร');
            $table->decimal('current_stock_liter', 10, 2)->default(0)->comment('ปริมาณคงเหลือ ลิตร');
            $table->decimal('volume_per_press_ml', 10, 2)->default(0)->comment('ปริมาณต่อการกด 1 ครั้ง มิลลิลิตร');

            $table->unsignedInteger('total_press_count')->default(0)->comment('จำนวนการกดทั้งหมด');

            $table->enum('status', [
                'active',
                'inactive',
                'maintenance',
                'out_of_stock',
            ])->default('active')->comment('สถานะตู้');

            $table->text('note')->nullable()->comment('หมายเหตุ');

            $table->timestamps();
            $table->softDeletes();

            $table->index('machine_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vending_machines');
    }
};

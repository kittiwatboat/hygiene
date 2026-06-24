<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();

                $table->string('member_code')->unique();
                $table->string('name');
                $table->string('phone', 30)->nullable()->unique();
                $table->string('email')->nullable()->unique();

                $table->unsignedBigInteger('points_balance')->default(0);

                $table->string('status')->default('active');
                $table->boolean('is_active')->default(true);

                $table->timestamp('last_used_at')->nullable();
                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['status', 'is_active']);
                $table->index('points_balance');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

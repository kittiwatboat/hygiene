<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'customer_id')) {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('customers')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('sales', 'promotion_id')) {
                $table->foreignId('promotion_id')
                    ->nullable()
                    ->after('customer_id')
                    ->constrained('promotions')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('sales', 'points_earned')) {
                $table->unsignedBigInteger('points_earned')
                    ->default(0)
                    ->after('promotion_id');
            }

            if (!Schema::hasColumn('sales', 'points_used')) {
                $table->unsignedBigInteger('points_used')
                    ->default(0)
                    ->after('points_earned');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['promotion_id']);

            $table->dropColumn([
                'customer_id',
                'promotion_id',
                'points_earned',
                'points_used',
            ]);
        });
    }
};

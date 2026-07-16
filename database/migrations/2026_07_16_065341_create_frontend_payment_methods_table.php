<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // promptpay, credit_card, true_money, shopeepay
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('action_key')->nullable(); // promptpay, card, wallet
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_payment_methods');
    }
};

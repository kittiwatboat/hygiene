<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kiosk_selections', function (Blueprint $table) {
            $table->id();
            $table->uuid('selection_token')->unique();
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('machine_group_id');
            $table->string('phone', 20)->nullable();
            $table->boolean('otp_verified')->default(false);
            $table->boolean('member_found')->nullable();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->json('items');
            $table->json('summary');
            $table->string('status', 30)->default('selected');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('phone');
            $table->index('machine_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kiosk_selections');
    }
};

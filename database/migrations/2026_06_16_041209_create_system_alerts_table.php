<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('system_alerts')) {
            Schema::create('system_alerts', function (Blueprint $table) {
                $table->id();

                $table->string('alert_key')->unique();

                $table->string('type');
                $table->string('level')->default('warning');

                $table->string('source_type')->nullable();
                $table->unsignedBigInteger('source_id')->nullable();

                $table->string('title');
                $table->text('message')->nullable();
                $table->string('url')->nullable();

                $table->string('status')->default('open');
                $table->timestamp('read_at')->nullable();
                $table->timestamp('resolved_at')->nullable();

                $table->timestamps();

                $table->index(['status', 'read_at']);
                $table->index(['source_type', 'source_id']);
                $table->index(['type', 'level']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_alerts');
    }
};

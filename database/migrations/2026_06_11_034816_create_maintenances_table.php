<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('maintenances')) {
            Schema::create('maintenances', function (Blueprint $table) {
                $table->id();

                $table->foreignId('machine_id')
                    ->constrained('machines')
                    ->cascadeOnDelete();

                $table->string('code')->nullable()->unique();

                $table->string('type')->default('other');
                $table->string('status')->default('reported');
                $table->string('priority')->default('normal');

                $table->text('problem')->nullable();
                $table->text('solution')->nullable();

                $table->foreignId('reported_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->foreignId('assigned_to')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamp('reported_at')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('finished_at')->nullable();

                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['machine_id', 'status']);
                $table->index(['type', 'priority']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};

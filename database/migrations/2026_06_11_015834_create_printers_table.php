<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('printers')) {
            Schema::create('printers', function (Blueprint $table) {
                $table->id();

                $table->foreignId('machine_id')
                    ->nullable()
                    ->constrained('machines')
                    ->nullOnDelete();

                $table->string('code')->nullable()->unique();
                $table->string('name');
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('serial_number')->nullable();

                $table->string('connection_type')->default('usb');
                $table->string('ip_address')->nullable();
                $table->unsignedInteger('port')->nullable();

                $table->string('paper_size')->nullable();
                $table->string('status')->default('active');

                $table->boolean('paper_available')->default(true);
                $table->boolean('is_active')->default(true);

                $table->timestamp('last_seen_at')->nullable();
                $table->text('remark')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (!Schema::hasColumn('machines', 'code')) {
                $table->string('code')->nullable()->after('id');
            }

            if (!Schema::hasColumn('machines', 'name')) {
                $table->string('name')->nullable()->after('code');
            }

            if (!Schema::hasColumn('machines', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('location_id');
            }

            if (!Schema::hasColumn('machines', 'model')) {
                $table->string('model')->nullable()->after('serial_number');
            }

            if (!Schema::hasColumn('machines', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }

            if (!Schema::hasColumn('machines', 'remark')) {
                $table->text('remark')->nullable()->after('is_active');
            }

            if (!Schema::hasColumn('machines', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable()->after('remark');
            }
        });

        DB::table('machines')->update([
            'code' => DB::raw('machine_code'),
            'name' => DB::raw('machine_name'),
            'remark' => DB::raw('note'),
        ]);
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (Schema::hasColumn('machines', 'code')) {
                $table->dropColumn('code');
            }

            if (Schema::hasColumn('machines', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('machines', 'serial_number')) {
                $table->dropColumn('serial_number');
            }

            if (Schema::hasColumn('machines', 'model')) {
                $table->dropColumn('model');
            }

            if (Schema::hasColumn('machines', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('machines', 'remark')) {
                $table->dropColumn('remark');
            }

            if (Schema::hasColumn('machines', 'last_seen_at')) {
                $table->dropColumn('last_seen_at');
            }
        });
    }
};

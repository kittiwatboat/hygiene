<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_machine_group_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_group_id')->constrained('machine_groups')->cascadeOnDelete();
            $table->foreignId('frontend_page_id')->constrained('frontend_pages')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(
                ['machine_group_id', 'frontend_page_id'],
                'fm_group_page_unique'
            );
        });

        $groupIds = DB::table('machine_groups')->pluck('id');
        $pageIds = DB::table('frontend_pages')->pluck('id');
        $rows = [];
        $now = now();

        foreach ($groupIds as $groupId) {
            foreach ($pageIds as $pageId) {
                $rows[] = [
                    'machine_group_id' => $groupId,
                    'frontend_page_id' => $pageId,
                    'is_active' => 1,
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($rows) {
            DB::table('frontend_machine_group_pages')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_machine_group_pages');
    }
};

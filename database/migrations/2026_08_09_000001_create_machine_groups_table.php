<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void { Schema::create('machine_groups',function(Blueprint $table){ $table->id(); $table->string('name'); $table->string('code',100)->unique(); $table->foreignId('frontend_theme_id')->nullable()->constrained('frontend_themes')->nullOnDelete(); $table->boolean('is_active')->default(true); $table->text('remark')->nullable(); $table->timestamps(); }); Schema::table('machines',function(Blueprint $table){ $table->foreignId('machine_group_id')->nullable()->after('id')->constrained('machine_groups')->nullOnDelete(); }); }
 public function down():void { Schema::table('machines',function(Blueprint $table){ $table->dropConstrainedForeignId('machine_group_id'); }); Schema::dropIfExists('machine_groups'); }
};

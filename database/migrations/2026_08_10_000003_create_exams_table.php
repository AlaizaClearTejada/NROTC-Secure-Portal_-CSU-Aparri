<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('subject')->nullable();
            $table->string('ms_grade_level')->nullable(); // MS1, MS2
            $table->integer('duration_minutes')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->boolean('has_part_two')->default(false);
            $table->string('part_one_title')->nullable();
            $table->string('part_two_title')->nullable();
            $table->boolean('prevent_back_navigation')->default(true);
            $table->boolean('auto_submit_on_tab_switch')->default(false);
            $table->boolean('is_published')->default(false);
            $table->string('status')->default('draft'); // draft, scheduled, open, closed
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('exams'); }
};

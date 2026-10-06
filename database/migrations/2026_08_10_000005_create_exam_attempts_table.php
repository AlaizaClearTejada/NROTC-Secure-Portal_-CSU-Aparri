<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('score_objective', 8, 2)->nullable();
            $table->decimal('score_essay', 8, 2)->nullable();
            $table->decimal('total_score', 8, 2)->nullable();
            $table->integer('tab_switch_count')->default(0);
            $table->boolean('force_submitted')->default(false);
            $table->string('status')->default('in_progress'); // in_progress, submitted, graded
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};

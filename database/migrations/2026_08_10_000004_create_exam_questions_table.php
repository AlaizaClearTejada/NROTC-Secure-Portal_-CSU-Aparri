<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->integer('part')->default(1);
            $table->text('question_text');
            $table->string('type'); // multiple_choice, identification, enumeration, essay
            $table->json('options')->nullable(); // For multiple choice
            $table->json('correct_answers')->nullable(); // Can store multiple correct answers for enumeration or ident
            $table->integer('points')->default(1);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};

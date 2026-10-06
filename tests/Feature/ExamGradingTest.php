<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamGradingTest extends TestCase
{
    use RefreshDatabase;

    public function test_blank_choices_are_dropped_and_the_correct_answer_follows_its_choice(): void
    {
        $officer = User::factory()->create(['role' => User::ROLE_OFFICER]);
        $exam = $this->openExam($officer);

        $this->actingAs($officer)->post(route('officer.exams.questions.store', $exam), [
            'part' => 1, 'question_text' => 'Pick C', 'type' => 'multiple_choice', 'points' => 2, 'order_index' => 0,
            'options' => ['A', '', 'C'], 'correct_answer_mc' => 'option_2',
        ])->assertSessionHasNoErrors();

        $question = $exam->questions()->first();
        $this->assertSame(['A', 'C'], $question->options);
        $this->assertSame(['option_1'], $question->correct_answers);
    }

    public function test_marking_a_blank_choice_as_correct_is_rejected(): void
    {
        $officer = User::factory()->create(['role' => User::ROLE_OFFICER]);
        $exam = $this->openExam($officer);

        $this->actingAs($officer)->post(route('officer.exams.questions.store', $exam), [
            'part' => 1, 'question_text' => 'Q', 'type' => 'multiple_choice', 'points' => 2, 'order_index' => 0,
            'options' => ['A', 'B', ''], 'correct_answer_mc' => 'option_2',
        ])->assertSessionHasErrors('options');

        $this->assertSame(0, $exam->questions()->count());
    }

    public function test_cadet_answers_are_graded(): void
    {
        $officer = User::factory()->create(['role' => User::ROLE_OFFICER]);
        $cadet = User::factory()->create();
        $exam = $this->openExam($officer);
        $mc = $exam->questions()->create(['part' => 1, 'question_text' => 'MC', 'type' => 'multiple_choice', 'points' => 2, 'order_index' => 0, 'options' => ['A', 'C'], 'correct_answers' => ['option_1']]);
        $ident = $exam->questions()->create(['part' => 1, 'question_text' => 'ID', 'type' => 'identification', 'points' => 2, 'order_index' => 1, 'correct_answers' => ['Rizal']]);
        $enum = $exam->questions()->create(['part' => 1, 'question_text' => 'EN', 'type' => 'enumeration', 'points' => 3, 'order_index' => 2, 'correct_answers' => ['Red', 'White', 'Blue']]);

        $this->actingAs($cadet)->post(route('cadet.exams.start', $exam));
        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), [
            'is_final' => 1,
            'answers' => [$mc->id => 'option_1', $ident->id => ' rizal ', $enum->id => "red, white\nBLUE"],
        ])->assertOk();

        $attempt = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $cadet->id)->first();
        $this->assertEquals(7, $attempt->score_objective);
        $this->assertSame('completed', $attempt->status);
    }

    public function test_enumeration_gives_partial_credit(): void
    {
        $officer = User::factory()->create(['role' => User::ROLE_OFFICER]);
        $cadet = User::factory()->create();
        $exam = $this->openExam($officer);
        $enum = $exam->questions()->create(['part' => 1, 'question_text' => 'EN', 'type' => 'enumeration', 'points' => 3, 'order_index' => 0, 'correct_answers' => ['Red', 'White', 'Blue']]);

        $this->actingAs($cadet)->post(route('cadet.exams.start', $exam));
        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), [
            'is_final' => 1,
            'answers' => [$enum->id => 'red, red, green'],
        ])->assertOk();

        $this->assertEquals(1, ExamAttempt::where('user_id', $cadet->id)->value('score_objective'));
    }

    private function openExam(User $creator): Exam
    {
        return Exam::create(['title' => 'Exam', 'status' => 'open', 'is_published' => true, 'created_by' => $creator->id]);
    }
}

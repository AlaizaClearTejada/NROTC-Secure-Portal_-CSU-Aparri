<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamTimeLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_answers_within_the_time_limit_are_saved(): void
    {
        [$cadet, $exam, $question] = $this->startedExam(['duration_minutes' => 30]);
        $this->travel(29)->minutes();

        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), ['is_final' => 0, 'answers' => [$question->id => 'Rizal']])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertEquals(2, $this->attempt($cadet, $exam)->score_objective);
    }

    public function test_final_submission_within_the_grace_period_is_accepted(): void
    {
        [$cadet, $exam, $question] = $this->startedExam(['duration_minutes' => 30]);
        $this->travel(30 * 60 + ExamAttempt::SUBMIT_GRACE_SECONDS - 5)->seconds();

        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), ['is_final' => 1, 'is_forced' => 1, 'answers' => [$question->id => 'Rizal']])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertEquals(2, $this->attempt($cadet, $exam)->score_objective);
    }

    public function test_answers_after_the_time_limit_are_rejected_and_the_attempt_is_closed(): void
    {
        [$cadet, $exam, $question] = $this->startedExam(['duration_minutes' => 30]);
        $this->travel(31)->minutes();

        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), ['is_final' => 1, 'answers' => [$question->id => 'Rizal']])
            ->assertOk()
            ->assertJson(['success' => false, 'redirect' => route('cadet.exams.show', $exam)]);

        $attempt = $this->attempt($cadet, $exam);
        $this->assertEquals(0, $attempt->score_objective);
        $this->assertSame('completed', $attempt->status);
        $this->assertTrue($attempt->force_submitted);
        $this->assertSame(0, $attempt->answers()->count());
    }

    public function test_answers_after_the_exam_end_time_are_rejected(): void
    {
        [$cadet, $exam, $question] = $this->startedExam(['end_time' => now()->addMinutes(10)]);
        $this->travel(11)->minutes();

        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), ['is_final' => 0, 'answers' => [$question->id => 'Rizal']])
            ->assertJson(['success' => false]);

        $this->assertSame('completed', $this->attempt($cadet, $exam)->status);
    }

    public function test_previously_saved_answers_are_kept_when_time_runs_out(): void
    {
        [$cadet, $exam, $question] = $this->startedExam(['duration_minutes' => 30]);
        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), ['is_final' => 0, 'answers' => [$question->id => 'Rizal']]);
        $this->travel(31)->minutes();

        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), ['is_final' => 1, 'answers' => [$question->id => 'Wrong']]);

        $this->assertEquals(2, $this->attempt($cadet, $exam)->score_objective);
    }

    public function test_exam_without_limits_accepts_late_submissions(): void
    {
        [$cadet, $exam, $question] = $this->startedExam([]);
        $this->travel(5)->hours();

        $this->actingAs($cadet)->postJson(route('cadet.exams.submit', $exam), ['is_final' => 1, 'answers' => [$question->id => 'Rizal']])
            ->assertJson(['success' => true]);
    }

    public function test_exam_cannot_be_started_after_its_end_time(): void
    {
        $cadet = User::factory()->create();
        $exam = Exam::create(['title' => 'Exam', 'status' => 'open', 'is_published' => true, 'end_time' => now()->subMinute(), 'created_by' => $cadet->id]);

        $this->actingAs($cadet)->post(route('cadet.exams.start', $exam))->assertSessionHas('error');

        $this->assertSame(0, ExamAttempt::count());
    }

    /**
     * @param  array<string, mixed>  $examAttributes
     * @return array{0: User, 1: Exam, 2: ExamQuestion}
     */
    private function startedExam(array $examAttributes): array
    {
        $cadet = User::factory()->create();
        $exam = Exam::create(array_merge(['title' => 'Exam', 'status' => 'open', 'is_published' => true, 'created_by' => $cadet->id], $examAttributes));
        $question = $exam->questions()->create(['part' => 1, 'question_text' => 'Who?', 'type' => 'identification', 'points' => 2, 'order_index' => 0, 'correct_answers' => ['Rizal']]);

        $this->actingAs($cadet)->post(route('cadet.exams.start', $exam))->assertRedirect(route('cadet.exams.take', $exam));

        return [$cadet, $exam, $question];
    }

    private function attempt(User $cadet, Exam $exam): ExamAttempt
    {
        return ExamAttempt::where('exam_id', $exam->id)->where('user_id', $cadet->id)->firstOrFail();
    }
}

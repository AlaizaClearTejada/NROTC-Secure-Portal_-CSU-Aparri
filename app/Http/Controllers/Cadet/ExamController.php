<?php

namespace App\Http\Controllers\Cadet;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::where('is_published', true)->whereIn('status', ['scheduled', 'open', 'closed']);

        $exams = $query->latest()->paginate(15);

        return view('cadet.exams.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        if (! $exam->is_published) {
            abort(404);
        }

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->first();

        return view('cadet.exams.show', compact('exam', 'attempt'));
    }

    public function start(Exam $exam)
    {
        if ($exam->status !== 'open') {
            return back()->with('error', 'This exam is not currently open.');
        }

        if ($exam->end_time?->isPast()) {
            return back()->with('error', 'This exam has already ended.');
        }

        $attempt = ExamAttempt::firstOrCreate([
            'exam_id' => $exam->id,
            'user_id' => Auth::id(),
        ], [
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('cadet.exams.show', $exam)->with('error', 'You have already completed this exam.');
        }

        return redirect()->route('cadet.exams.take', $exam);
    }

    public function take(Exam $exam)
    {
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('cadet.exams.show', $exam)->with('error', 'You have already completed this exam.');
        }

        if ($attempt->isPastDeadline($exam)) {
            return $this->autoSubmit($attempt);
        }

        $exam->load(['questions' => function ($q) {
            $q->orderBy('part')->orderBy('order_index');
        }]);

        $attempt->load('answers');

        return view('cadet.exams.take', compact('exam', 'attempt'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($attempt->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Exam already submitted.', 'redirect' => route('cadet.exams.show', $exam)]);
        }

        if ($attempt->isPastDeadline($exam, ExamAttempt::SUBMIT_GRACE_SECONDS)) {
            $response = $this->autoSubmit($attempt);

            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Time expired.', 'redirect' => route('cadet.exams.show', $exam)])
                : $response;
        }

        $isFinal = $request->input('is_final', false);
        $isForced = $request->input('is_forced', false);
        $answersData = $request->input('answers', []);

        // Save answers and auto-grade objective questions
        $objectiveScore = 0;

        $exam->load('questions');

        foreach ($exam->questions as $question) {
            $answerVal = $answersData[$question->id] ?? null;
            if (! $answerVal) {
                continue;
            }

            $isCorrect = false;
            $pointsAwarded = 0;
            $answerArray = is_array($answerVal) ? $answerVal : [$answerVal];

            if ($question->type === 'multiple_choice') {
                if (in_array($answerArray[0], $question->correct_answers)) {
                    $isCorrect = true;
                    $pointsAwarded = $question->points;
                }
            } elseif ($question->type === 'identification') {
                $correct = strtolower(trim($question->correct_answers[0] ?? ''));
                $given = strtolower(trim($answerArray[0] ?? ''));
                if ($correct === $given) {
                    $isCorrect = true;
                    $pointsAwarded = $question->points;
                }
            } elseif ($question->type === 'enumeration') {
                // simple grading for enumeration: 1 point for each correct match (if that's the logic)
                // For now, if all required answers are present (case insensitive)
                $corrects = array_unique(ExamQuestion::enumerationItems($question->correct_answers ?? []));
                $givens = ExamQuestion::enumerationItems($answerArray);

                $matched = array_intersect($corrects, $givens);

                // Assign partial points based on matches, or require all.
                // Let's do partial: (matches / total) * points
                if (count($corrects) > 0) {
                    $fraction = count($matched) / count($corrects);
                    $pointsAwarded = round($question->points * $fraction, 2);
                    $isCorrect = $pointsAwarded == $question->points;
                }
            }

            ExamAnswer::updateOrCreate([
                'exam_attempt_id' => $attempt->id,
                'exam_question_id' => $question->id,
            ], [
                'answer_text' => $answerArray,
                'is_correct' => $isCorrect,
                'points_awarded' => $pointsAwarded,
            ]);

            $objectiveScore += $pointsAwarded;
        }

        $attempt->update([
            'score_objective' => $objectiveScore,
            'total_score' => $objectiveScore + $attempt->score_essay,
        ]);

        if ($isFinal) {
            $attempt->update([
                'completed_at' => now(),
                'status' => 'completed',
                'force_submitted' => $isForced,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'redirect' => route('cadet.exams.show', $exam)]);
            }

            return redirect()->route('cadet.exams.show', $exam)->with('success', 'Exam submitted successfully.');
        }

        return response()->json(['success' => true, 'message' => 'Progress saved.']);
    }

    public function tabSwitch(Request $request, Exam $exam)
    {
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->first();

        if ($attempt) {
            $attempt->increment('tab_switch_count');

            return response()->json(['success' => true, 'count' => $attempt->tab_switch_count]);
        }

        return response()->json(['success' => false], 404);
    }

    private function autoSubmit(ExamAttempt $attempt)
    {
        $attempt->update([
            'completed_at' => now(),
            'status' => 'completed',
            'force_submitted' => true,
        ]);

        return redirect()->route('cadet.exams.show', $attempt->exam_id)->with('error', 'Time expired. Your exam was automatically submitted.');
    }
}

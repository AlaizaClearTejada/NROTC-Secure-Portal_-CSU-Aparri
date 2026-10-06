<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with('creator')->withCount('questions', 'attempts');

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%'.$request->subject.'%');
        }

        $exams = $query->latest()->paginate(15);

        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.exams.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:255',
            'ms_grade_level' => 'nullable|in:ms1,ms2',
            'duration_minutes' => 'nullable|integer|min:1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'has_part_two' => 'boolean',
            'part_one_title' => 'nullable|string|max:255',
            'part_two_title' => 'nullable|string|max:255',
            'prevent_back_navigation' => 'boolean',
            'auto_submit_on_tab_switch' => 'boolean',
            'is_published' => 'boolean',
            'status' => 'required|in:draft,scheduled,open,closed',
        ]);

        $exam = Exam::create(array_merge($request->all(), [
            'created_by' => Auth::id(),
            'has_part_two' => $request->boolean('has_part_two', false),
            'prevent_back_navigation' => $request->boolean('prevent_back_navigation', true),
            'auto_submit_on_tab_switch' => $request->boolean('auto_submit_on_tab_switch', false),
            'is_published' => $request->boolean('is_published', false),
        ]));

        return redirect()->route('admin.exams.show', $exam)->with('success', 'Exam created. You can now add questions.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['questions' => function ($q) {
            $q->orderBy('part')->orderBy('order_index');
        }]);

        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.form', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:255',
            'ms_grade_level' => 'nullable|in:ms1,ms2',
            'duration_minutes' => 'nullable|integer|min:1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'has_part_two' => 'boolean',
            'part_one_title' => 'nullable|string|max:255',
            'part_two_title' => 'nullable|string|max:255',
            'prevent_back_navigation' => 'boolean',
            'auto_submit_on_tab_switch' => 'boolean',
            'is_published' => 'boolean',
            'status' => 'required|in:draft,scheduled,open,closed',
        ]);

        $exam->update(array_merge($request->all(), [
            'has_part_two' => $request->boolean('has_part_two', false),
            'prevent_back_navigation' => $request->boolean('prevent_back_navigation', true),
            'auto_submit_on_tab_switch' => $request->boolean('auto_submit_on_tab_switch', false),
            'is_published' => $request->boolean('is_published', false),
        ]));

        return redirect()->route('admin.exams.show', $exam)->with('success', 'Exam details updated.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted.');
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $request->validate([
            'part' => 'required|integer|in:1,2',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,identification,enumeration,essay',
            'points' => 'required|integer|min:1',
            'order_index' => 'required|integer|min:0',
        ]);

        $data = $request->only(['part', 'question_text', 'type', 'points', 'order_index']);

        if ($request->type === 'multiple_choice') {
            $request->validate([
                'options' => 'required|array|min:2',
                'correct_answer_mc' => 'required|string',
            ]);
            $choices = ExamQuestion::compactChoices($request->options, $request->correct_answer_mc);

            if (! $choices) {
                return back()->withInput()->withErrors(['options' => 'Fill in at least two choices and mark a filled-in choice as the correct answer.']);
            }

            $data['options'] = $choices['options'];
            $data['correct_answers'] = [$choices['correct_answer']];
        } elseif ($request->type === 'identification') {
            $request->validate([
                'correct_answer_ident' => 'required|string',
            ]);
            $data['correct_answers'] = [$request->correct_answer_ident];
        } elseif ($request->type === 'enumeration') {
            $request->validate([
                'correct_answers_enum' => 'required|string',
            ]);
            $data['correct_answers'] = array_map('trim', explode(',', $request->correct_answers_enum));
        }

        $exam->questions()->create($data);

        return back()->with('success', 'Question added.');
    }

    public function destroyQuestion(Exam $exam, ExamQuestion $question)
    {
        $question->delete();

        return back()->with('success', 'Question removed.');
    }

    public function results(Exam $exam)
    {
        $attempts = $exam->attempts()->with('user')->orderBy('score_objective', 'desc')->paginate(20);

        return view('admin.exams.results', compact('exam', 'attempts'));
    }

    public function showAttempt(Exam $exam, ExamAttempt $attempt)
    {
        $attempt->load(['user', 'answers.question']);

        return view('admin.exams.attempt', compact('exam', 'attempt'));
    }

    public function gradeAttempt(Request $request, Exam $exam, ExamAttempt $attempt)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'numeric|min:0',
        ]);

        $essayScore = 0;
        foreach ($request->scores as $answerId => $points) {
            $answer = $attempt->answers()->find($answerId);
            if ($answer) {
                $answer->update([
                    'points_awarded' => $points,
                    'is_correct' => $points > 0,
                ]);
                $essayScore += $points;
            }
        }

        $attempt->update([
            'score_essay' => $essayScore,
            'total_score' => $attempt->score_objective + $essayScore,
            'status' => 'graded',
        ]);

        return back()->with('success', 'Essay/Manual grading updated and final score recorded.');
    }
}

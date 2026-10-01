@extends('layouts.app')

@section('title', 'Grade Attempt: ' . $attempt->user->name)
@section('page-title', 'Grade Attempt')

@section('sidebar-nav')
    <a href="{{ route('officer.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Unit Oversight
    </a>
    <a href="{{ route('officer.exams.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        Examinations
    </a>
@endsection

@section('content')
<div class="max-w-5xl space-y-6 pb-20">
    <div class="flex items-center gap-3">
        <a href="{{ route('officer.exams.results', $exam) }}" class="text-xs font-semibold flex items-center gap-1 text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Results
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card rounded-xl overflow-hidden p-6 md:p-8 flex flex-col md:flex-row justify-between gap-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800">{{ $attempt->user->name }}</h1>
            <p class="text-sm font-bold text-slate-500 mt-1">Student ID: {{ $attempt->user->student_id }}</p>
            <div class="mt-4 space-y-1 text-sm text-slate-600">
                <p><span class="font-bold">Exam:</span> {{ $exam->title }}</p>
                <p><span class="font-bold">Submitted:</span> {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y h:i A') : 'Not completed' }}</p>
                @if($attempt->force_submitted)
                    <p class="text-red-600 font-bold">This attempt was forcefully submitted (Time expired or Tab switching violation).</p>
                @endif
                @if($attempt->tab_switch_count > 0)
                    <p class="text-orange-600 font-bold">Tab switches detected: {{ $attempt->tab_switch_count }}</p>
                @endif
            </div>
        </div>
        <div class="shrink-0 flex gap-4 text-center">
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 min-w-[100px]">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Objective</p>
                <p class="text-2xl font-black text-slate-800">{{ $attempt->score_objective + 0 }}</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 min-w-[100px]">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Essay</p>
                <p class="text-2xl font-black text-blue-800">{{ $attempt->score_essay + 0 }}</p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 min-w-[100px]">
                <p class="text-xs font-bold text-green-700 uppercase tracking-wider mb-1">Total</p>
                <p class="text-2xl font-black text-green-800">{{ $attempt->total_score + 0 }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('officer.exams.attempt.grade', [$exam, $attempt]) }}">
        @csrf
        <div class="space-y-6 mt-8">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-black text-slate-800">Answers & Grading</h2>
                @if($attempt->status !== 'graded')
                    <button type="submit" class="px-5 py-2 bg-blue-700 text-white font-bold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-sm">Save Grades</button>
                @else
                    <button type="submit" class="px-5 py-2 bg-slate-200 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-300 transition-colors shadow-sm">Update Grades</button>
                @endif
            </div>

            @foreach($attempt->answers as $index => $answer)
                @php 
                    $q = $answer->question; 
                    $requiresManualGrading = in_array($q->type, ['essay']);
                @endphp
                <div class="card bg-white border {{ $requiresManualGrading && $attempt->status !== 'graded' ? 'border-blue-300 ring-1 ring-blue-100' : 'border-slate-200' }} rounded-lg p-5">
                    <div class="flex justify-between items-start gap-4 mb-3">
                        <div class="flex-1">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-600 text-xs font-black mb-2">{{ $loop->iteration }}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wider bg-slate-100 text-slate-600 ml-2">
                                {{ str_replace('_', ' ', $q->type) }}
                            </span>
                            <p class="text-slate-800 font-medium">{{ $q->question_text }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <span class="text-xs font-bold text-slate-400 block mb-1">Max: {{ $q->points }} pt(s)</span>
                            @if($requiresManualGrading)
                                <div class="flex items-center gap-2">
                                    <input type="number" name="scores[{{ $answer->id }}]" value="{{ $answer->points_awarded + 0 }}" min="0" max="{{ $q->points }}" step="0.5" class="w-20 px-2 py-1 border border-blue-300 rounded font-bold text-blue-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 text-right bg-blue-50">
                                </div>
                            @else
                                <span class="inline-flex px-3 py-1 rounded font-black text-sm {{ $answer->is_correct ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $answer->points_awarded + 0 }} / {{ $q->points }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="pl-8 pt-3 border-t border-slate-100">
                        @if($q->type === 'multiple_choice')
                            <div class="space-y-1">
                                @foreach($q->options as $optIndex => $optText)
                                    @php 
                                        $isCorrect = in_array('option_' . $optIndex, $q->correct_answers ?? []); 
                                        $isSelected = in_array('option_' . $optIndex, $answer->answer_text ?? []);
                                    @endphp
                                    <div class="flex items-center gap-2 text-sm">
                                        @if($isSelected)
                                            <svg class="w-4 h-4 {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        @else
                                            <div class="w-4 h-4 rounded-full border border-slate-300"></div>
                                        @endif
                                        <span class="{{ $isCorrect ? 'font-bold text-green-700' : ($isSelected ? 'text-red-600' : 'text-slate-600') }}">{{ $optText }}</span>
                                        @if($isCorrect && !$isSelected) <span class="text-[10px] uppercase font-bold text-green-600 ml-2">(Correct Answer)</span> @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mb-2">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Cadet's Answer:</p>
                                <div class="bg-slate-50 p-3 rounded border border-slate-200 text-sm text-slate-800 whitespace-pre-wrap">{{ is_array($answer->answer_text) ? implode(', ', $answer->answer_text) : ($answer->answer_text[0] ?? '') }}</div>
                            </div>
                            @if(!$requiresManualGrading)
                                <p class="text-xs font-bold text-green-700 mt-2">Accepted Answers: {{ implode(', ', $q->correct_answers ?? []) }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>
@endsection

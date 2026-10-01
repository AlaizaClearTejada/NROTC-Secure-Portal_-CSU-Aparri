@extends('layouts.app')

@section('title', $exam->title)
@section('page-title', 'Examination Details')

@section('sidebar-nav')
    <a href="{{ route('cadet.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>
    <a href="{{ route('cadet.exams.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        Examinations
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('cadet.exams.index') }}" class="text-xs font-semibold flex items-center gap-1 text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Exams
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-50 text-red-800 border border-red-200 rounded-lg p-4 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card rounded-xl overflow-hidden shadow-lg border-t-4 border-t-blue-600">
        <div class="p-8 text-center bg-slate-50 border-b border-slate-100">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 font-bold text-[10px] uppercase tracking-wider rounded-full mb-3">
                {{ $exam->subject ?? 'General Exam' }}
            </span>
            <h1 class="text-3xl font-black text-slate-800 mb-2">{{ $exam->title }}</h1>
            <p class="text-sm text-slate-500 max-w-lg mx-auto">{{ $exam->description ?? 'Please read all instructions before starting the exam.' }}</p>
        </div>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Exam Details</h3>
                    <ul class="space-y-3 text-sm font-medium text-slate-700">
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-400">Duration</span>
                                {{ $exam->duration_minutes ? $exam->duration_minutes . ' Minutes' : 'No Time Limit' }}
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-400">Total Items</span>
                                {{ $exam->questions()->count() }} Questions
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Security Rules</h3>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @if($exam->prevent_back_navigation)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Do not use the browser's back button.</span>
                            </li>
                        @endif
                        @if($exam->auto_submit_on_tab_switch)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span><strong class="text-red-700">Strict Tab Tracking:</strong> Leaving the exam tab will be recorded. Multiple violations will auto-submit your exam.</span>
                            </li>
                        @endif
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Answers are auto-saved periodically.</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-slate-100">

            <div class="text-center pt-2">
                @if($attempt)
                    @if($attempt->status === 'completed' || $attempt->status === 'graded')
                        <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Your Result</h3>
                            <p class="text-4xl font-black text-slate-800">{{ $attempt->total_score + 0 }} <span class="text-base font-semibold text-slate-400">Points</span></p>
                            @if($attempt->status === 'completed')
                                <p class="text-xs text-orange-600 mt-2 font-bold">Pending final review (Essay items may still need grading).</p>
                            @else
                                <p class="text-xs text-green-600 mt-2 font-bold">Grading finalized.</p>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg text-sm font-bold border border-yellow-200 mb-4">
                            You have an ongoing attempt.
                        </div>
                        <a href="{{ route('cadet.exams.take', $exam) }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-yellow-500 text-white rounded-xl text-lg font-black shadow-lg hover:bg-yellow-600 hover:-translate-y-0.5 transition-all w-full md:w-auto">
                            Resume Exam
                        </a>
                    @endif
                @else
                    @if($exam->status === 'open')
                        <form method="POST" action="{{ route('cadet.exams.start', $exam) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-700 text-white rounded-xl text-lg font-black shadow-lg shadow-blue-700/30 hover:bg-blue-800 hover:-translate-y-0.5 transition-all w-full md:w-auto">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                Start Examination
                            </button>
                        </form>
                    @else
                        <div class="bg-slate-50 text-slate-600 p-4 rounded-lg text-sm font-bold border border-slate-200">
                            This exam is currently closed or not yet open.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

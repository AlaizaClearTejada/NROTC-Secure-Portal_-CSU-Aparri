@extends('layouts.app')

@section('title', isset($exam) ? 'Edit Examination' : 'Create Examination')
@section('page-title', isset($exam) ? 'Edit Examination' : 'Create Examination')

@section('sidebar-nav')
    <a href="{{ route('officer.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Unit Oversight
    </a>
    <a href="{{ route('officer.materials.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        Lecture Materials
    </a>
    <a href="{{ route('officer.exams.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        Examinations
    </a>
@endsection

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ isset($exam) ? route('officer.exams.show', $exam) : route('officer.exams.index') }}" class="text-xs font-semibold flex items-center gap-1 text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ isset($exam) ? route('officer.exams.update', $exam) : route('officer.exams.store') }}" class="space-y-6">
        @csrf
        @if(isset($exam)) @method('PUT') @endif

        <div class="card rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-lg font-black text-slate-800">Basic Details</h2>
                <p class="text-sm text-slate-500">General information about this examination.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="col-span-full">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Exam Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $exam->title ?? '') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="col-span-full">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Description / Instructions</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">{{ old('description', $exam->description ?? '') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject', $exam->subject ?? '') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                        @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">MS Grade Level</label>
                        <select name="ms_grade_level" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                            <option value="">All levels</option>
                            <option value="ms1" {{ old('ms_grade_level', $exam->ms_grade_level ?? '') == 'ms1' ? 'selected' : '' }}>MS1</option>
                            <option value="ms2" {{ old('ms_grade_level', $exam->ms_grade_level ?? '') == 'ms2' ? 'selected' : '' }}>MS2</option>
                        </select>
                        @error('ms_grade_level') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-base font-black text-slate-800">Time & Scheduling</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes ?? '') }}" placeholder="Leave blank for no limit" min="1" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                        <p class="text-[11px] text-slate-500 mt-1">Exam will auto-submit when time expires.</p>
                        @error('duration_minutes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Start Time</label>
                        <input type="datetime-local" name="start_time" value="{{ old('start_time', isset($exam) && $exam->start_time ? $exam->start_time->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">End Time</label>
                        <input type="datetime-local" name="end_time" value="{{ old('end_time', isset($exam) && $exam->end_time ? $exam->end_time->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                    </div>
                </div>
            </div>

            <div class="card rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-base font-black text-slate-800">Security & Organization</h2>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="has_part_two" value="0">
                            <input type="checkbox" name="has_part_two" value="1" {{ old('has_part_two', $exam->has_part_two ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-600 rounded">
                            <span class="text-sm font-bold text-slate-700">Divide into 2 Parts</span>
                        </label>
                    </div>

                    <hr class="border-slate-100">

                    <div>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="hidden" name="prevent_back_navigation" value="0">
                            <input type="checkbox" name="prevent_back_navigation" value="1" {{ old('prevent_back_navigation', $exam->prevent_back_navigation ?? true) ? 'checked' : '' }} class="w-4 h-4 mt-0.5 text-blue-600 focus:ring-blue-600 rounded">
                            <div>
                                <span class="text-sm font-bold text-slate-700 block">Prevent Back Navigation</span>
                                <span class="text-[11px] text-slate-500 leading-tight">Cadets cannot use the browser back button to re-attempt questions.</span>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="hidden" name="auto_submit_on_tab_switch" value="0">
                            <input type="checkbox" name="auto_submit_on_tab_switch" value="1" {{ old('auto_submit_on_tab_switch', $exam->auto_submit_on_tab_switch ?? false) ? 'checked' : '' }} class="w-4 h-4 mt-0.5 text-red-600 focus:ring-red-600 rounded">
                            <div>
                                <span class="text-sm font-bold text-red-700 block">Strict Tab-Switching Detection</span>
                                <span class="text-[11px] text-slate-500 leading-tight">Warns cadet if they leave the tab. Auto-submits exam on 3rd violation.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card rounded-xl p-6 bg-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Exam Status <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-4">
                    <select name="status" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                        <option value="draft" {{ old('status', $exam->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft (Under construction)</option>
                        <option value="scheduled" {{ old('status', $exam->status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled (Awaiting start time)</option>
                        <option value="open" {{ old('status', $exam->status ?? '') == 'open' ? 'selected' : '' }}>Open (Accepting attempts)</option>
                        <option value="closed" {{ old('status', $exam->status ?? '') == 'closed' ? 'selected' : '' }}>Closed (Done)</option>
                    </select>
                    
                    <label class="flex items-center gap-2 cursor-pointer border-l border-slate-300 pl-4">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $exam->is_published ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-600 rounded">
                        <span class="text-sm font-bold text-slate-700">Visible to Cadets</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ isset($exam) ? route('officer.exams.show', $exam) : route('officer.exams.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-100 transition-colors">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-blue-700 text-white font-bold text-sm rounded-lg hover:bg-blue-800 transition-colors">
                    {{ isset($exam) ? 'Save Exam Details' : 'Create Exam' }}
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

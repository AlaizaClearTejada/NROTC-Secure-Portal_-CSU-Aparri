@extends('layouts.app')

@section('title', 'Cadet Details — ' . $user->name)
@section('page-title', 'Cadet Details')

@section('sidebar-nav')
    <a href="{{ route('officer.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Unit Oversight
    </a>
    <a href="{{ route('officer.cadets.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Cadets
    </a>
    <a href="{{ route('officer.attendance.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Attendance
    </a>
    <a href="{{ route('officer.grades') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Grades
    </a>
    <a href="{{ route('officer.materials.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        Lecture Materials
    </a>
    <a href="{{ route('officer.exams.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        Examinations
    </a>
    <a href="{{ route('officer.exams.create') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create Exam
    </a>
@endsection

@section('content')
<div class="max-w-3xl space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('officer.cadets.index') }}" class="text-xs font-semibold flex items-center gap-1 transition-colors text-blue-600 hover:text-blue-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Cadets
        </a>
    </div>

    <div class="card rounded-xl overflow-hidden">
        <div class="h-1.5 w-full bg-blue-600"></div>
        <div class="px-6 py-5 flex items-center gap-5">
            @if($user->photo_path)
                <img src="{{ Storage::url($user->photo_path) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover shrink-0 border-2 border-blue-200">
            @else
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-black shrink-0 bg-blue-100 text-blue-800">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-black text-slate-900">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    @if ($user->student_id)
                        <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $user->student_id }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Enrolled Cadet
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-xl p-6 space-y-5">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600">Personal Information</h3>
        </div>

        <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-sm">
            @php
                $fields = [
                    'Date of Birth'  => $user->date_of_birth?->format('F d, Y'),
                    'Age'            => $user->date_of_birth ? $user->date_of_birth->age . ' years old' : null,
                    'Gender'         => $user->gender,
                    'Blood Type'     => $user->blood_type,
                    'College'        => $user->college,
                    'Course / Year'  => $user->course_year,
                    'Medical Cond.'  => $user->existing_medical_conditions,
                    'Contact Number' => $user->contact_number,
                ];
            @endphp

            @foreach ($fields as $label => $value)
                <div>
                    <p class="text-xs text-slate-400 font-medium">{{ $label }}</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $value ?? '—' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

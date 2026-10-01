@extends('layouts.app')

@section('title', 'Enrollment Validation')
@section('page-title', 'Enrollment Validation')

@section('sidebar-nav')
    <a href="{{ route('officer.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Unit Oversight
    </a>
    <a href="{{ route('officer.enrollments.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Enrollments
    </a>
    <a href="{{ route('officer.attendance.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Attendance
    </a>
    <a href="{{ route('officer.grades') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
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

{{-- Flash messages --}}
@if (session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
         style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
        {{ session('success') }}
    </div>
@endif

{{-- Stat chips --}}
<div class="flex flex-wrap gap-3 mb-5">
    @php
        $tabs = [
            'pending_review' => ['label' => 'Pending',   'count' => $counts->pending   ?? 0, 'color' => '#92400e', 'bg' => '#fffbeb', 'border' => '#fde68a'],
            'approved'       => ['label' => 'Validated', 'count' => $counts->approved  ?? 0, 'color' => '#14532d', 'bg' => '#f0fdf4', 'border' => '#bbf7d0'],
            'rejected'       => ['label' => 'Rejected',  'count' => $counts->rejected  ?? 0, 'color' => '#7f1d1d', 'bg' => '#fff5f5', 'border' => '#fecaca'],
            'all'            => ['label' => 'All',       'count' => $counts->total     ?? 0, 'color' => '#1e3a5f', 'bg' => '#f1f5f9', 'border' => '#cbd5e1'],
        ];
    @endphp

    @foreach ($tabs as $key => $tab)
        <a href="{{ route('officer.enrollments.index', ['status' => $key]) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold transition-all"
           style="
               background: {{ $filter === $key ? $tab['bg'] : '#fff' }};
               color: {{ $tab['color'] }};
               border: 1.5px solid {{ $filter === $key ? $tab['border'] : '#e2e8f0' }};
               box-shadow: {{ $filter === $key ? '0 1px 4px rgba(0,0,0,0.07)' : 'none' }};
           ">
            {{ $tab['label'] }}
            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-black"
                  style="background: {{ $tab['border'] }}; color: {{ $tab['color'] }};">
                {{ $tab['count'] }}
            </span>
        </a>
    @endforeach
</div>

{{-- Main table --}}
<div class="card rounded-xl overflow-hidden">

    <div class="flex items-center justify-between px-5 py-3.5 border-b" style="border-color: rgba(4,9,15,0.08);">
        <div>
            <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                @if ($filter === 'all') All Enrollees
                @elseif ($filter === 'pending_review') Pending Validation
                @elseif ($filter === 'approved') Validated Enrollees
                @else Rejected Enrollees
                @endif
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">{{ $enrollees->count() }} record(s)</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background: #f8fafc;">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Cadet</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Student ID</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Course / Year</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Contact</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($enrollees as $enrollee)
                    <tr class="hover:bg-slate-50 transition-colors">

                        {{-- Name + avatar --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                @if($enrollee->photo_path)
                                    <img src="{{ Storage::url($enrollee->photo_path) }}" alt="{{ $enrollee->name }}" class="w-8 h-8 rounded-full object-cover shrink-0" style="border: 2px solid var(--gold);">
                                @else
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black shrink-0"
                                         style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                                        {{ strtoupper(substr($enrollee->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $enrollee->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $enrollee->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Student ID --}}
                        <td class="px-5 py-3 text-xs font-mono text-slate-500">{{ $enrollee->student_id ?? '—' }}</td>

                        {{-- Course/Year --}}
                        <td class="px-5 py-3 text-xs text-slate-600">{{ $enrollee->course_year ?? '—' }}</td>

                        {{-- Contact --}}
                        <td class="px-5 py-3 text-xs text-slate-600">{{ $enrollee->contact_number ?? '—' }}</td>

                        {{-- Status badge --}}
                        <td class="px-5 py-3 text-center">
                            @if ($enrollee->enrollment_status === 'pending_review')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold"
                                      style="background: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 inline-block"></span>
                                    Pending
                                </span>
                            @elseif ($enrollee->enrollment_status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold"
                                      style="background: #f0fdf4; color: #14532d; border: 1px solid #bbf7d0;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                    Validated
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold"
                                      style="background: #fff5f5; color: #7f1d1d; border: 1px solid #fecaca;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>
                                    Rejected
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('officer.enrollments.show', $enrollee) }}"
                                   class="text-xs px-2.5 py-1 rounded font-semibold transition-colors"
                                   style="background: rgba(200,169,81,0.1); color: #c8a951; border: 1px solid rgba(200,169,81,0.2);">
                                    Review
                                </a>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-8 h-8 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm">No
                                    @if ($filter !== 'all') {{ $filter }} @endif
                                    enrollment records found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

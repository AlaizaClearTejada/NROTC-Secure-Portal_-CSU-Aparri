@extends('layouts.app')

@section('title', 'Review Enrollment — ' . $user->name)
@section('page-title', 'Review Enrollment')

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
@endsection

@section('content')
<div class="max-w-3xl space-y-5">

    {{-- Back + header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('officer.enrollments.index') }}"
           class="text-xs font-semibold flex items-center gap-1 transition-colors"
           style="color: var(--gold2);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Enrollees
        </a>
    </div>

    {{-- Hero card --}}
    <div class="card rounded-xl overflow-hidden">
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, var(--gold2), var(--gold3), var(--gold));"></div>
        <div class="px-6 py-5 flex items-center gap-5">
            @if($user->photo_path)
                <img src="{{ Storage::url($user->photo_path) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover shrink-0" style="border: 2px solid var(--gold);">
            @else
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-black shrink-0"
                     style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-black" style="color: var(--navy);">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    @if ($user->student_id)
                        <span class="text-xs font-mono px-2 py-0.5 rounded"
                              style="background: rgba(4,9,15,0.06); color: var(--navy);">
                            {{ $user->student_id }}
                        </span>
                    @endif

                    @if ($user->enrollment_status === 'pending')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                              style="background: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> Pending Validation
                        </span>
                    @elseif ($user->enrollment_status === 'validated')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                              style="background: #f0fdf4; color: #14532d; border: 1px solid #bbf7d0;">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Validated
                        </span>
                    @elseif ($user->enrollment_status === 'rejected')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                              style="background: #fff5f5; color: #7f1d1d; border: 1px solid #fecaca;">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Profile details --}}
    <div class="card rounded-xl p-6 space-y-5">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
            <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600">Personal Information</h3>
        </div>

        <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-sm">
            @php
                $fields = [
                    'Date of Birth'  => $user->date_of_birth?->format('F d, Y'),
                    'Place of Birth' => $user->place_of_birth,
                    'Age'            => $user->date_of_birth ? $user->date_of_birth->age . ' years old' : null,
                    'Gender'         => $user->gender,
                    'Blood Type'     => $user->blood_type,
                    'Religion'       => $user->religion,
                    'College'        => $user->college,
                    'Department'     => $user->department,
                    'Course / Year'  => $user->course_year,
                    'Medical Cond.'  => $user->existing_medical_conditions,
                    'Height'         => $user->height ? $user->height . ' cm' : null,
                    'Weight'         => $user->weight ? $user->weight . ' kg' : null,
                    'Contact Number' => $user->contact_number,
                    'Address'        => $user->address,
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
    {{-- Documents section --}}
    <div class="card rounded-xl p-6 space-y-4">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
            <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
            </svg>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600">Attached Documents</h3>
        </div>

        <div class="grid grid-cols-2 gap-4">
            @foreach([
                'diploma_path' => 'Photocopy of Diploma',
                'birth_certificate_path' => 'Birth Certificate',
                'medical_path' => 'Medical Certificate',
                'parent_id_path' => 'Parent/Guardian ID',
                'parent_consent_path' => 'Parent Consent Form'
            ] as $field => $label)
                <div class="p-3 border rounded-lg {{ $user->$field ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-slate-50' }}">
                    <p class="text-xs font-semibold text-slate-600 mb-2">{{ $label }}</p>
                    @if($user->$field)
                        <a href="{{ Storage::url($user->$field) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View Document
                        </a>
                    @else
                        <span class="text-sm text-slate-400 italic">Not provided</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    {{-- Emergency contact --}}
    <div class="card rounded-xl p-6 space-y-4">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
            <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600">Emergency Contact</h3>
        </div>

        <div class="grid grid-cols-3 gap-x-8 gap-y-3 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-medium">Name</p>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $user->emergency_name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-medium">Relationship</p>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $user->emergency_relationship ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-medium">Contact</p>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $user->emergency_contact ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Existing remarks (if any) --}}
    @if ($user->enrollment_remarks)
        <div class="card rounded-xl p-5"
             style="background: #fff9db; border: 1px solid #fde68a;">
            <p class="text-xs font-bold text-yellow-700 uppercase tracking-wider mb-1">Previous Remarks</p>
            <p class="text-sm text-yellow-900">{{ $user->enrollment_remarks }}</p>
        </div>
    @endif

    @endif

</div>
@endsection

@extends('layouts.app')

@section('title', 'Applicant Dashboard')
@section('page-title', 'Applicant Dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="card rounded-xl overflow-hidden mb-6">
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
                <p class="text-sm text-slate-500">Applicant</p>
            </div>
        </div>
    </div>

    <div class="card rounded-xl p-6">
        <h3 class="text-lg font-black text-slate-900 mb-4">Application Status</h3>

        @if ($user->isPendingEnrollment())
            <div class="rounded-xl p-5 flex items-start gap-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <svg class="w-6 h-6 mt-0.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-md font-bold text-slate-800">Submitted - Pending Review</h4>
                    <p class="text-sm text-slate-600 mt-1">Your application is currently being reviewed by the NROTC Office. Please check back later for updates.</p>
                </div>
            </div>
        @elseif ($user->isUnderMedicalReview())
            <div class="rounded-xl p-5 flex items-start gap-4" style="background: #fffbeb; border: 1px solid #fde68a;">
                <svg class="w-6 h-6 mt-0.5 text-yellow-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-md font-bold text-yellow-800">Under Medical Review</h4>
                    <p class="text-sm text-yellow-700 mt-1">Your application has passed the initial review and is now under medical evaluation. Ensure your medical documents are clear.</p>
                </div>
            </div>
        @elseif ($user->isRevisionRequested())
            <div class="rounded-xl p-5 flex items-start gap-4" style="background: #fff1f2; border: 1px solid #fecdd3;">
                <svg class="w-6 h-6 mt-0.5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h4 class="text-md font-bold text-red-800">Revision Requested</h4>
                    <p class="text-sm text-red-700 mt-1">The ROTC office has reviewed your application and requires some changes or additional documents.</p>
                    
                    <div class="mt-4 p-4 bg-white rounded-lg border border-red-200">
                        <h5 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-2">Revision Notes:</h5>
                        <p class="text-sm text-slate-800">{{ $user->revision_notes }}</p>
                    </div>
                    
                    <div class="mt-5">
                        <a href="{{ route('enroll.form') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-bold text-sm bg-red-600 text-white hover:bg-red-700 transition-colors">
                            Update Application Form
                        </a>
                    </div>
                </div>
            </div>
        @elseif ($user->isEnrollmentRejected())
            <div class="rounded-xl p-5 flex items-start gap-4" style="background: #fef2f2; border: 1px solid #fecaca;">
                <svg class="w-6 h-6 mt-0.5 text-red-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-md font-bold text-red-900">Application Rejected</h4>
                    <p class="text-sm text-red-800 mt-1">Unfortunately, your application was rejected.</p>
                    @if($user->enrollment_remarks)
                    <div class="mt-3 text-sm text-red-700">
                        <strong>Remarks:</strong> {{ $user->enrollment_remarks }}
                    </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

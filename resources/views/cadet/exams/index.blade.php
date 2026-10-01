@extends('layouts.app')

@section('title', 'Examinations')
@section('page-title', 'Examinations')

@section('sidebar-nav')
    <a href="{{ route('cadet.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>
    <a href="{{ route('cadet.profile') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        My Profile
    </a>
    <a href="{{ route('cadet.materials.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        Lecture Materials
    </a>
    <a href="{{ route('cadet.exams.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        Examinations
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Examinations</h1>
            <p class="text-sm text-slate-500 mt-1">View available exams and your results.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($exams as $exam)
            @php 
                $attempt = $exam->attempts()->where('user_id', Auth::id())->first();
            @endphp
            <div class="card rounded-xl overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border-t-4 {{ $exam->status === 'open' ? 'border-t-green-500' : ($exam->status === 'closed' ? 'border-t-slate-400' : 'border-t-blue-500') }}">
                <div class="p-5 flex-1">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="px-2.5 py-1 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-wider rounded">
                            {{ $exam->subject ?? 'General' }}
                        </div>
                        @if($exam->status === 'open')
                            <span class="inline-flex items-center gap-1 text-green-700 font-bold text-xs"><span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Open</span>
                        @elseif($exam->status === 'scheduled')
                            <span class="inline-flex items-center text-blue-600 font-bold text-xs">Scheduled</span>
                        @else
                            <span class="inline-flex items-center text-slate-400 font-bold text-xs">Closed</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-black text-slate-800 leading-tight mb-2">{{ $exam->title }}</h3>
                    
                    <div class="text-xs text-slate-500 space-y-1 mb-4 font-medium">
                        <p class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ $exam->duration_minutes ? $exam->duration_minutes . ' mins' : 'No time limit' }}</p>
                        @if($exam->start_time)
                            <p class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Opens: {{ $exam->start_time->format('M d, Y h:i A') }}</p>
                        @endif
                        @if($exam->end_time)
                            <p class="flex items-center gap-2"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Closes: {{ $exam->end_time->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                </div>
                <div class="px-5 py-4 bg-slate-50 border-t border-slate-100">
                    @if($attempt)
                        @if($attempt->status === 'completed' || $attempt->status === 'graded')
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Your Score</p>
                                    <p class="text-xl font-black text-slate-800">{{ $attempt->total_score + 0 }}</p>
                                </div>
                                <a href="{{ route('cadet.exams.show', $exam) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-100 transition-colors">Details</a>
                            </div>
                        @else
                            <a href="{{ route('cadet.exams.show', $exam) }}" class="block w-full text-center px-4 py-2 bg-yellow-500 text-white font-bold text-sm rounded-lg hover:bg-yellow-600 transition-colors shadow-sm">Resume Exam</a>
                        @endif
                    @else
                        @if($exam->status === 'open')
                            <a href="{{ route('cadet.exams.show', $exam) }}" class="block w-full text-center px-4 py-2 bg-blue-700 text-white font-bold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-sm">View Details</a>
                        @else
                            <button disabled class="block w-full text-center px-4 py-2 bg-slate-100 text-slate-400 font-bold text-sm rounded-lg cursor-not-allowed">Not Available</button>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white border border-slate-100 rounded-xl shadow-sm">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">No exams available.</h3>
                <p class="text-sm text-slate-500 mt-1">There are no examinations published at this time.</p>
            </div>
        @endforelse
    </div>

    @if($exams->hasPages())
        <div class="mt-6">
            {{ $exams->links() }}
        </div>
    @endif
</div>
@endsection

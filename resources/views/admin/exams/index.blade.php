@extends('layouts.app')

@section('title', 'Examinations')
@section('page-title', 'Examinations')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>
    <a href="{{ route('admin.exams.index') }}" class="sidebar-link active">
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
            <p class="text-sm text-slate-500 mt-1">Manage online exams, questions, and grading.</p>
        </div>
        <a href="{{ route('admin.exams.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-blue-800 focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Exam
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Title / Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Questions</th>
                        <th class="px-6 py-4">Attempts</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($exams as $exam)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $exam->title }}</p>
                                <p class="text-xs text-slate-500">{{ $exam->subject ?? 'General' }} &bull; {{ strtoupper($exam->ms_grade_level ?? 'ALL') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($exam->status === 'draft')
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-slate-100 text-slate-600">Draft</span>
                                @elseif($exam->status === 'scheduled')
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-blue-50 text-blue-700 border border-blue-200">Scheduled</span>
                                @elseif($exam->status === 'open')
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-green-50 text-green-700 border border-green-200">Open</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-red-50 text-red-700 border border-red-200">Closed</span>
                                @endif
                                
                                @if(!$exam->is_published)
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded bg-yellow-100 text-yellow-800 ml-1">Hidden</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $exam->questions_count }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $exam->attempts_count }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                                {{ $exam->duration_minutes ? $exam->duration_minutes . ' mins' : 'No limit' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.exams.show', $exam) }}" class="inline-block px-3 py-1.5 text-blue-700 bg-blue-50 font-bold text-xs rounded hover:bg-blue-100 transition-colors">Manage</a>
                                <a href="{{ route('admin.exams.results', $exam) }}" class="inline-block px-3 py-1.5 text-indigo-700 bg-indigo-50 font-bold text-xs rounded hover:bg-indigo-100 transition-colors">Results</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">No exams created yet.</h3>
                                <p class="text-sm text-slate-500 mt-1">Create an exam to assess the cadets.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($exams->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

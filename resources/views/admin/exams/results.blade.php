@extends('layouts.app')

@section('title', 'Exam Results: ' . $exam->title)
@section('page-title', 'Exam Results')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
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
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.exams.show', $exam) }}" class="text-xs font-semibold flex items-center gap-1 text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Exam Details
        </a>
    </div>

    <div>
        <h1 class="text-2xl font-black text-slate-800">Results: {{ $exam->title }}</h1>
        <p class="text-sm text-slate-500 mt-1">View and grade cadet attempts.</p>
    </div>

    <div class="card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Cadet</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Objective Score</th>
                        <th class="px-6 py-4">Essay Score</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attempts as $attempt)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $attempt->user->name }}</p>
                                <p class="text-[11px] text-slate-500">{{ $attempt->user->student_id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($attempt->status === 'in_progress')
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-yellow-50 text-yellow-700 border border-yellow-200">In Progress</span>
                                @elseif($attempt->status === 'completed')
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-blue-50 text-blue-700 border border-blue-200">Completed (Needs Grading)</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-green-50 text-green-700 border border-green-200">Graded</span>
                                @endif

                                @if($attempt->force_submitted)
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-red-50 text-red-700 border border-red-200 ml-1" title="Forced via Time or Tab Switch">Forced</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $attempt->score_objective + 0 }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $attempt->score_essay + 0 }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800 text-base">
                                {{ $attempt->total_score + 0 }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.exams.attempt', [$exam, $attempt]) }}" class="inline-block px-3 py-1.5 text-blue-700 bg-blue-50 font-bold text-xs rounded hover:bg-blue-100 transition-colors">
                                    {{ $attempt->status === 'completed' ? 'Grade / View' : 'View' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <h3 class="text-sm font-bold text-slate-800">No attempts yet.</h3>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attempts->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

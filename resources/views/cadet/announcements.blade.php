@extends('layouts.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('sidebar-nav')
    <a href="{{ route('cadet.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        My Dashboard
    </a>
    <a href="{{ route('cadet.profile') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        My Profile
    </a>
    <a href="{{ route('cadet.announcements') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
        </svg>
        Announcements
    </a>
    <a href="{{ route('cadet.attendance') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                     M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        My Attendance
    </a>
    <a href="{{ route('cadet.materials.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        Lecture Materials
    </a>
    <a href="{{ route('cadet.exams.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        Examinations
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">

    {{-- ── Announcements list (2/3) ────────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-3">

        @forelse ($announcements as $ann)
            <div class="card rounded-xl overflow-hidden">
                @if ($ann->is_pinned)
                    <div class="h-0.5" style="background: linear-gradient(90deg, var(--gold2), var(--gold3));"></div>
                @endif
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="text-sm font-bold text-slate-900 leading-snug">{{ $ann->title }}</h3>
                        @if ($ann->is_pinned)
                            <span class="shrink-0 flex items-center gap-1 text-xs font-semibold px-1.5 py-0.5 rounded"
                                  style="background: rgba(200,169,81,.1); color: var(--gold);">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 3a1 1 0 011 1v1h1a1 1 0 110 2h-.172l.823 7.407A2 2 0 0116.67 16H13v5a1 1 0 11-2 0v-5H7.33a2 2 0 01-1.981-1.593L5.172 7H5a1 1 0 110-2h1V4a1 1 0 011-1h9z"/>
                                </svg>
                                Pinned
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $ann->content }}</p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black shrink-0"
                                 style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                                {{ strtoupper(substr($ann->author->name, 0, 1)) }}
                            </div>
                            <span class="text-xs text-slate-400">{{ $ann->author->name }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <span>{{ $ann->published_at->format('d M Y, H:i') }}</span>
                            @if ($ann->expires_at)
                                <span class="px-1.5 py-0.5 rounded"
                                      style="background: rgba(248,113,113,.08); color: #f87171;">
                                    Expires {{ $ann->expires_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card rounded-xl p-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mb-3"
                     style="background: rgba(200,169,81,.08); border: 1px solid rgba(200,169,81,.15);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--gold);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">No announcements yet</p>
                <p class="text-xs text-slate-400 mt-1">Check back later for updates from your unit.</p>
            </div>
        @endforelse

    </div>

    {{-- ── Info sidebar (1/3) ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col gap-4">

        {{-- Stats --}}
        <div class="card rounded-xl p-4">
            <p class="text-xs font-bold uppercase tracking-widest mb-3 pb-2"
               style="color: var(--gold); border-bottom: 1px solid rgba(200,169,81,.15);">Overview</p>
            <dl class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <dt class="text-slate-400">Total</dt>
                    <dd class="font-semibold text-slate-700">{{ $announcements->count() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400">Pinned</dt>
                    <dd class="font-semibold text-slate-700">{{ $announcements->where('is_pinned', true)->count() }}</dd>
                </div>
                @if ($announcements->count())
                    <div class="flex justify-between">
                        <dt class="text-slate-400">Latest</dt>
                        <dd class="font-semibold text-slate-700">{{ $announcements->first()->published_at->diffForHumans() }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Notice --}}
        <div class="card rounded-xl p-4">
            <p class="text-xs font-bold uppercase tracking-widest mb-2 pb-2"
               style="color: var(--gold); border-bottom: 1px solid rgba(200,169,81,.15);">Notice</p>
            <p class="text-xs text-slate-500 leading-relaxed">
                Announcements are posted by unit officers and administrators.
                Contact your unit staff for concerns or questions.
            </p>
        </div>

    </div>

</div>
@endsection

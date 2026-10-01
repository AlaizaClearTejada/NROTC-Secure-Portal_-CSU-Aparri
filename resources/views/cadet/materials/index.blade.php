@extends('layouts.app')

@section('title', 'Lecture Materials')
@section('page-title', 'Lecture Materials')

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
    <a href="{{ route('cadet.materials.index') }}" class="sidebar-link active">
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
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Lecture Materials</h1>
            <p class="text-sm text-slate-500 mt-1">Access study materials and resources for your classes.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card p-4 rounded-xl">
        <form method="GET" action="{{ route('cadet.materials.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="subject" value="{{ request('subject') }}" placeholder="Search subjects or topics..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white font-bold text-sm rounded-lg hover:bg-blue-800 transition-colors">Search</button>
            @if(request('subject'))
                <a href="{{ route('cadet.materials.index') }}" class="px-4 py-2 text-slate-500 font-semibold text-sm hover:text-slate-700 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    {{-- Grid of Materials --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($materials as $mat)
            <div class="card rounded-xl overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full">
                <div class="p-5 flex-1">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="px-2.5 py-1 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-wider rounded">
                            {{ $mat->subject ?? 'General' }}
                        </div>
                        <div class="text-slate-400">
                            @if($mat->file_type === 'pdf')
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                            @elseif(in_array($mat->file_type, ['docx','xlsx','pptx']))
                                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                            @elseif($mat->file_type === 'image')
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @elseif($mat->file_type === 'video')
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            @endif
                        </div>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 leading-tight mb-1">{{ $mat->title }}</h3>
                    @if($mat->topic)
                        <p class="text-xs font-semibold text-slate-500 mb-3">{{ $mat->topic }}</p>
                    @endif
                    @if($mat->description)
                        <p class="text-sm text-slate-600 line-clamp-2">{{ $mat->description }}</p>
                    @endif
                </div>
                <div class="px-5 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400">By {{ $mat->creator?->name ?? 'Instructor' }}</span>
                    <a href="{{ Storage::url($mat->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-700 font-bold text-xs rounded-lg hover:bg-blue-200 transition-colors">
                        Open File
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white border border-slate-100 rounded-xl shadow-sm">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">No materials available yet.</h3>
                <p class="text-sm text-slate-500 mt-1">Check back later when instructors upload resources.</p>
            </div>
        @endforelse
    </div>

    @if($materials->hasPages())
        <div class="mt-6">
            {{ $materials->links() }}
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Lecture Materials')
@section('page-title', 'Lecture Materials')

@section('sidebar-nav')
    <a href="{{ route('officer.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Unit Oversight
    </a>
    <a href="{{ route('officer.materials.index') }}" class="sidebar-link active">
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
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Lecture Materials</h1>
            <p class="text-sm text-slate-500 mt-1">Manage documents and resources for cadets.</p>
        </div>
        <a href="{{ route('officer.materials.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-700 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-blue-800 focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Upload Material
        </a>
    </div>

    {{-- Session alerts --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="card p-4 rounded-xl">
        <form method="GET" action="{{ route('officer.materials.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="subject" value="{{ request('subject') }}" placeholder="Filter by subject..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-200 transition-colors">Filter</button>
            @if(request('subject'))
                <a href="{{ route('officer.materials.index') }}" class="px-4 py-2 text-slate-500 font-semibold text-sm hover:text-slate-700 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Title / Topic</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Availability</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($materials as $mat)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $mat->title }}</p>
                                @if($mat->topic) <p class="text-xs text-slate-500">{{ $mat->topic }}</p> @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded font-semibold text-xs">{{ $mat->subject ?? 'General' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-xs uppercase text-slate-600">{{ $mat->availability }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($mat->is_published)
                                    <span class="inline-flex items-center gap-1 text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded text-xs font-bold">Published</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-yellow-700 bg-yellow-50 border border-yellow-200 px-2 py-0.5 rounded text-xs font-bold">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 uppercase text-xs font-bold text-slate-500">
                                {{ $mat->file_type }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ Storage::url($mat->file_path) }}" target="_blank" class="inline-block px-3 py-1.5 text-blue-700 bg-blue-50 font-bold text-xs rounded hover:bg-blue-100 transition-colors">View</a>
                                <a href="{{ route('officer.materials.edit', $mat) }}" class="inline-block px-3 py-1.5 text-slate-700 bg-slate-100 font-bold text-xs rounded hover:bg-slate-200 transition-colors">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">No materials found</h3>
                                <p class="text-sm text-slate-500 mt-1">Upload lecture materials to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($materials->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Unit Oversight')
@section('page-title', 'Unit Oversight')

@section('sidebar-nav')
    <a href="{{ route('officer.dashboard') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Unit Oversight
    </a>
    <a href="{{ route('officer.cadets.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Cadets
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

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $statCards = [
                ['label' => 'Total Cadets',   'value' => $stats['total_cadets'],    'color' => '#c8a951', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => 'Active',         'value' => $stats['active_cadets'],   'color' => '#4ade80', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Inactive',       'value' => $stats['inactive_cadets'], 'color' => '#94a3b8', 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
                ['label' => 'Account Locked', 'value' => $stats['locked_cadets'],   'color' => '#f87171', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
            ];
        @endphp
        @foreach ($statCards as $card)
            <div class="stat-card rounded-xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-xs text-slate-500 uppercase tracking-widest leading-tight">{{ $card['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background: rgba(255,255,255,.6); border: 1px solid #e2e8f0;">
                        <svg class="w-4 h-4" fill="none" stroke="{{ $card['color'] }}" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Cadet roster --}}
    <div class="card rounded-xl overflow-hidden mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Cadet Roster</h2>
                <p class="text-xs text-slate-400 mt-0.5">Read-only — {{ $stats['total_cadets'] }} enrolled cadet(s)</p>
            </div>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="cadetSearch" type="text" placeholder="Search cadets..."
                       class="pl-9 pr-4 py-2 text-xs rounded-lg text-slate-900 w-56"
                       style="background: #f8fafc; border: 1px solid #e2e8f0;">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="cadetTable">
                <thead>
                    <tr class="border-b border-slate-100" style="background: #f8fafc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider w-10">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Student ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Last Login</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($cadets as $index => $cadet)
                        <tr class="cadet-row hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-400 text-xs">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-slate-900 font-medium">{{ $cadet->name }}</td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $cadet->student_id ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600 text-xs">{{ $cadet->email }}</td>
                            <td class="px-6 py-4">
                                @if ($cadet->isLocked())
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                          style="background: rgba(248,113,113,.1); color: #ef4444; border: 1px solid rgba(248,113,113,.25);">
                                        Locked
                                    </span>
                                @elseif ($cadet->is_active)
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                          style="background: rgba(74,222,128,.1); color: #16a34a; border: 1px solid rgba(74,222,128,.25);">
                                        Active
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                          style="background: rgba(148,163,184,.1); color: #64748b; border: 1px solid rgba(148,163,184,.25);">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs">
                                {{ $cadet->last_login_at ? $cadet->last_login_at->diffForHumans() : 'Never' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400 text-sm">
                                No cadets enrolled yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="noResults" class="hidden px-6 py-10 text-center text-slate-400 text-sm border-t border-slate-100">
            No cadets match your search.
        </div>
    </div>

    {{-- Access note --}}
    <div class="card rounded-xl p-5">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="#c8a951" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-700">Officer Access Level</h3>
        </div>
        <p class="text-xs text-slate-500 leading-relaxed">
            As an officer, you may <strong class="text-slate-700">view all cadet records</strong> in read-only mode.
            Account management (create, unlock, deactivate) is reserved for administrators.
            All access is subject to audit logging.
        </p>
    </div>

@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('cadetSearch');
    const rows        = document.querySelectorAll('.cadet-row');
    const noResults   = document.getElementById('noResults');

    searchInput.addEventListener('input', () => {
        const q = searchInput.value.toLowerCase().trim();
        let visible = 0;

        rows.forEach(row => {
            const match = !q || row.textContent.toLowerCase().includes(q);
            row.style.display = match ? '' : 'none';
            if (match) { visible++; }
        });

        noResults.classList.toggle('hidden', visible > 0 || !q);
    });
</script>
@endpush

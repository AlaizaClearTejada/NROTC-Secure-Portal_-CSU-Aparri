@extends('layouts.app')

@section('title', 'My Attendance')
@section('page-title', 'My Attendance')

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
    <a href="{{ route('cadet.announcements') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
        </svg>
        Announcements
    </a>
    <a href="{{ route('cadet.attendance') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
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
<div class="space-y-4">

    {{-- ── Stat cards ───────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php $net = $stats['net']; @endphp
        <div class="stat-card rounded-xl p-4 text-center">
            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Days Recorded</p>
            <p class="text-2xl font-black text-slate-900">{{ $stats['days_recorded'] }} <span class="text-sm font-normal text-slate-400">/ 15</span></p>
        </div>
        <div class="stat-card rounded-xl p-4 text-center">
            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Merits</p>
            <p class="text-2xl font-black" style="color: #047857;">{{ $stats['total_merits'] }}</p>
        </div>
        <div class="stat-card rounded-xl p-4 text-center">
            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Demerits</p>
            <p class="text-2xl font-black" style="color: #b91c1c;">{{ $stats['total_demerits'] }}</p>
        </div>
        <div class="stat-card rounded-xl p-4 text-center">
            <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Net Merit</p>
            <p class="text-2xl font-black" style="color: {{ $net >= 0 ? '#047857' : '#b91c1c' }};">
                {{ $net >= 0 ? '+' : '' }}{{ $net }}
            </p>
        </div>
    </div>

    {{-- ── Attendance table ─────────────────────────────────────────────────────── --}}
    <div class="card rounded-xl overflow-hidden">

        <div class="px-5 py-3" style="border-bottom: 2px solid rgba(200,169,81,.2); background: rgba(200,169,81,.03);">
            <h2 class="text-xs font-black uppercase tracking-widest" style="color: var(--gold);">
                Attendance Record — Training Days 1–15
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Read-only. Contact your unit officer for corrections.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse: collapse; min-width: 650px;">
                <thead>
                    <tr style="background: rgba(200,169,81,.05); border-bottom: 2px solid rgba(200,169,81,.15);">
                        <th style="padding:.55rem .7rem; text-align:center; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color: var(--gold); width:48px;">Day</th>
                        <th style="padding:.55rem .7rem; text-align:left; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color: var(--gold);">Date</th>
                        <th style="padding:.55rem .7rem; text-align:center; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#047857; width:80px;">Merits</th>
                        <th style="padding:.55rem .7rem; text-align:center; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#b91c1c; width:80px;">Demerits</th>
                        <th style="padding:.55rem .7rem; text-align:left; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#374151;">Remarks</th>
                        <th style="padding:.55rem .7rem; text-align:left; font-size:.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#374151; white-space:nowrap;">Signature</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($days as $index => $rec)
                        @php $d = $index + 1; $filled = $rec && $rec->training_date; @endphp
                        <tr style="border-bottom: 1px solid #f3f4f6; {{ !$filled ? 'opacity: 0.45;' : '' }}">
                            <td style="padding:.45rem .7rem; text-align:center;">
                                <span style="display:inline-flex; align-items:center; justify-content:center; width:1.6rem; height:1.6rem; border-radius:.4rem; background:{{ $filled ? 'rgba(200,169,81,.1)' : '#f1f5f9' }}; border:1px solid {{ $filled ? 'rgba(200,169,81,.2)' : '#e2e8f0' }}; font-size:.7rem; font-weight:800; color:{{ $filled ? 'var(--gold)' : '#94a3b8' }};">{{ $d }}</span>
                            </td>
                            <td style="padding:.45rem .7rem; font-size:.8rem; font-weight:600; color:#374151;">
                                {{ $rec?->training_date?->format('d M Y') ?? '—' }}
                            </td>
                            <td style="padding:.45rem .7rem; text-align:center; font-size:.85rem; font-weight:800; color:#047857;">
                                {{ $rec?->merits ?? '—' }}
                            </td>
                            <td style="padding:.45rem .7rem; text-align:center; font-size:.85rem; font-weight:800; color:#b91c1c;">
                                {{ $rec?->demerits ?? '—' }}
                            </td>
                            <td style="padding:.45rem .7rem; font-size:.8rem; color:#64748b;">
                                {{ $rec?->remarks ?? '—' }}
                            </td>
                            <td style="padding:.45rem .7rem; font-size:.8rem; font-style:italic; color:#94a3b8;">
                                {{ $rec?->e_signature ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                {{-- Totals --}}
                @php $totM = $days->sum(fn($r) => $r?->merits ?? 0); $totD = $days->sum(fn($r) => $r?->demerits ?? 0); @endphp
                <tfoot>
                    <tr style="background: rgba(200,169,81,.04); border-top: 2px solid rgba(200,169,81,.15);">
                        <td colspan="2" style="padding:.55rem .7rem; text-align:right; font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#64748b;">Total</td>
                        <td style="padding:.55rem .7rem; text-align:center; font-size:.9rem; font-weight:900; color:#047857;">{{ $totM }}</td>
                        <td style="padding:.55rem .7rem; text-align:center; font-size:.9rem; font-weight:900; color:#b91c1c;">{{ $totD }}</td>
                        <td colspan="2" style="padding:.55rem .7rem;">
                            <span style="font-size:.75rem; font-weight:800; color: {{ $totM - $totD >= 0 ? '#047857' : '#b91c1c' }};">
                                Net: {{ $totM - $totD >= 0 ? '+' : '' }}{{ $totM - $totD }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>
@endsection

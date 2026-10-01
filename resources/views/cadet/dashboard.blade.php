@extends('layouts.app')

@section('title', 'Cadet Dashboard')
@section('page-title', 'Cadet Dashboard')

@section('sidebar-nav')
    <a href="{{ route('cadet.dashboard') }}" class="sidebar-link active">
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
<div class="space-y-4">

    {{-- ── On-hold enrollment modal (blocks dashboard access) ────────────────── --}}
    @unless($cadet->student_id)
    <div id="enrollModal" style="position:fixed;inset:0;z-index:9000;display:flex;align-items:center;justify-content:center;background:rgba(4,9,15,.75);backdrop-filter:blur(6px);">
        <div style="background:#fff;border-radius:1rem;max-width:420px;width:92%;box-shadow:0 24px 64px rgba(0,0,0,.25);overflow:hidden;animation:modalIn .4s ease;">

            {{-- Gold accent bar --}}
            <div style="height:4px;background:linear-gradient(90deg,var(--gold2),var(--gold3),var(--gold));"></div>

            <div style="padding:2rem 1.75rem 1.5rem;text-align:center;">

                {{-- Warning icon --}}
                <div style="width:56px;height:56px;border-radius:50%;margin:0 auto 1.25rem;display:flex;align-items:center;justify-content:center;background:rgba(245,158,11,.1);border:2px solid rgba(245,158,11,.2);">
                    <svg style="width:28px;height:28px;color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3l9.09 16.91H2.91L12 3z"/>
                    </svg>
                </div>

                {{-- Title --}}
                <h2 style="font-size:1.15rem;font-weight:800;color:#1e293b;margin:0 0 .35rem;">Account On Hold</h2>
                <p style="font-size:.8rem;color:#64748b;line-height:1.6;margin:0 0 1.5rem;">
                    Your account has been created successfully but is currently <strong style="color:#d97706;">on hold</strong>.
                    You must complete the enrollment process before you can access the portal.
                </p>

                {{-- Status badge --}}
                <div style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1rem;border-radius:9999px;font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(245,158,11,.08);color:#b45309;border:1px solid rgba(245,158,11,.25);margin-bottom:1.5rem;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#f59e0b;display:inline-block;animation:pulse-dot 1.5s ease-in-out infinite;"></span>
                    Pending Enrollment
                </div>

                {{-- Buttons --}}
                <div style="display:flex;flex-direction:column;gap:.6rem;">
                    <a href="{{ route('enroll.form') }}"
                       style="display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.75rem 1.25rem;border-radius:.5rem;font-size:.85rem;font-weight:700;color:#fff;background:#d97706;text-decoration:none;transition:background .15s;box-shadow:0 2px 8px rgba(217,119,6,.25);"
                       onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Proceed to Enrollment
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                style="display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.65rem 1.25rem;border-radius:.5rem;font-size:.8rem;font-weight:600;color:#78716c;background:rgba(120,113,108,.08);border:1px solid rgba(120,113,108,.15);cursor:pointer;transition:background .15s;"
                                onmouseover="this.style.background='rgba(120,113,108,.15)'" onmouseout="this.style.background='rgba(120,113,108,.08)'">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Log Out & Continue Later
                        </button>
                    </form>
                </div>

                <p style="font-size:.65rem;color:#94a3b8;margin:1.25rem 0 0;line-height:1.5;">
                    Need help? Contact the ROTC office at CSU Aparri.
                </p>
            </div>
        </div>
    </div>
    <style>
        @keyframes modalIn { from { opacity:0; transform:scale(.92) translateY(12px); } to { opacity:1; transform:none; } }
        @keyframes pulse-dot { 0%,100% { opacity:.5; } 50% { opacity:1; } }
    </style>
    @endunless

    {{-- ── Top row ────────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Profile card --}}
        <div class="stat-card rounded-xl p-4 flex flex-col justify-between">

            {{-- Identity --}}
            <div class="flex items-center gap-3 mb-4">
                @if($cadet->photo_path)
                    <img src="{{ Storage::url($cadet->photo_path) }}" alt="{{ $cadet->name }}" class="w-12 h-12 rounded-full object-cover shrink-0" style="border: 2px solid var(--gold);">
                @else
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 font-black text-lg"
                         style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                        {{ strtoupper(substr($cadet->name, 0, 1)) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <h2 class="text-sm font-black text-slate-900 truncate">{{ $cadet->name }}</h2>
                    <p class="text-xs font-mono text-slate-400 truncate">{{ $cadet->student_id ?? 'No ID assigned' }}</p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="text-xs px-1.5 py-0.5 rounded-full font-semibold uppercase"
                              style="background: rgba(200,169,81,.12); color: var(--gold); border: 1px solid rgba(200,169,81,.3);">
                            Cadet
                        </span>
                        <span class="text-xs px-1.5 py-0.5 rounded-full font-semibold"
                              style="background: rgba(74,222,128,.12); color: #4ade80; border: 1px solid rgba(74,222,128,.3);">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick stats --}}
            <dl class="space-y-1.5 text-xs border-t border-slate-100 pt-3">
                <div class="flex justify-between">
                    <dt class="text-slate-400">Email</dt>
                    <dd class="text-slate-700 truncate max-w-[60%] text-right">{{ $cadet->email }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400">Contact</dt>
                    <dd class="text-slate-700">{{ $cadet->contact_number ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400">Last Login</dt>
                    <dd class="text-slate-700">
                        {{ $cadet->last_login_at ? $cadet->last_login_at->format('d M Y, H:i') : 'First login' }}
                    </dd>
                </div>
            </dl>

        </div>

        {{-- Cadet details --}}
        <div class="card rounded-xl p-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Cadet Details</h3>

            <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                <div>
                    <dt class="text-slate-400">Date of Birth</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">
                        {{ $cadet->date_of_birth ? $cadet->date_of_birth->format('d M Y') : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-slate-400">Age</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">
                        {{ $cadet->date_of_birth ? $cadet->date_of_birth->diffInYears(now()) . ' yrs' : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-slate-400">Gender</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">{{ $cadet->gender ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Blood Type</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">{{ $cadet->blood_type ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Religion</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">{{ $cadet->religion ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Course / Year</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">{{ $cadet->course_year ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Height</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">{{ $cadet->height ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400">Weight</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">
                        {{ $cadet->weight ? $cadet->weight . ' kg' : '—' }}
                    </dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-slate-400">Address</dt>
                    <dd class="text-slate-800 font-medium mt-0.5">{{ $cadet->address ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Access level + Emergency --}}
        <div class="flex flex-col gap-4">

            {{-- Access level --}}
            <div class="card rounded-xl p-4 flex-1">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Access Level</h3>
                @php
                    $perms = [
                        ['label' => 'View my profile',          'allowed' => true],
                        ['label' => 'View my attendance',       'allowed' => true],
                        ['label' => 'View my grades',           'allowed' => true],
                        ['label' => 'Submit digital documents', 'allowed' => true],
                        ['label' => 'View other cadet records', 'allowed' => false],
                        ['label' => 'Modify any records',       'allowed' => false],
                        ['label' => 'Access system settings',   'allowed' => false],
                    ];
                @endphp
                <ul class="space-y-1.5">
                    @foreach ($perms as $perm)
                        <li class="flex items-center gap-2 text-xs">
                            @if ($perm['allowed'])
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="#4ade80" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-slate-700">{{ $perm['label'] }}</span>
                            @else
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="#f87171" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="text-slate-400 line-through">{{ $perm['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Emergency contact --}}
            <div class="card rounded-xl p-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Emergency Contact</h3>
                @if ($cadet->emergency_name)
                    <dl class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Name</dt>
                            <dd class="text-slate-800 font-medium">{{ $cadet->emergency_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Relation</dt>
                            <dd class="text-slate-800 font-medium">{{ $cadet->emergency_relationship ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-400">Contact</dt>
                            <dd class="text-slate-800 font-medium">{{ $cadet->emergency_contact ?? '—' }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="text-xs text-slate-400 italic">
                        Not set —
                        <a href="{{ route('cadet.profile') }}" class="underline" style="color: var(--gold);">add one</a>
                    </p>
                @endif
            </div>

        </div>
    </div>

    {{-- ── Security notice ────────────────────────────────────────────────────── --}}
    <div class="card rounded-xl px-4 py-3 flex items-start gap-3">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             style="color: var(--gold);">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <p class="text-xs text-slate-500">
            Session expires after <strong class="text-slate-700">30 minutes</strong> of inactivity.
            Always <strong class="text-slate-700">Secure Logout</strong> on shared devices.
            Never share your password with anyone, including unit staff.
        </p>
    </div>

</div>

{{-- ── Announcements popup modal ───────────────────────────────────────────── --}}
@if ($announcements->isNotEmpty())
<div id="ann-modal"
     style="position:fixed;inset:0;z-index:8500;display:flex;align-items:center;justify-content:center;
            padding:1.25rem;background:rgba(4,9,15,.6);backdrop-filter:blur(5px);">
    <div style="background:#fff;border-radius:1.25rem;max-width:460px;width:100%;
                max-height:80vh;display:flex;flex-direction:column;
                box-shadow:0 24px 64px rgba(0,0,0,.22);overflow:hidden;
                animation:annModalIn .35s cubic-bezier(.34,1.4,.64,1);">

        {{-- Gold header bar --}}
        <div style="height:3px;background:linear-gradient(90deg,var(--gold2),var(--gold3),var(--gold));flex-shrink:0;"></div>

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:1.1rem 1.4rem .8rem;flex-shrink:0;
                    border-bottom:1px solid #f1f5f9;">
            <div style="display:flex;align-items:center;gap:.6rem;">
                <div style="width:2rem;height:2rem;border-radius:.5rem;display:flex;align-items:center;justify-content:center;
                            background:rgba(200,169,81,.1);border:1px solid rgba(200,169,81,.2);">
                    <svg style="width:1rem;height:1rem;" fill="none" stroke="var(--gold)" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:.8rem;font-weight:800;color:var(--navy);margin:0;letter-spacing:.01em;">Unit Announcements</p>
                    <p style="font-size:.65rem;color:#94a3b8;margin:0;">{{ $announcements->count() }} active {{ Str::plural('announcement', $announcements->count()) }}</p>
                </div>
            </div>
            <button onclick="dismissAnnouncements()"
                    style="width:1.75rem;height:1.75rem;border-radius:9999px;display:flex;align-items:center;justify-content:center;
                           border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer;transition:background .15s;"
                    onmouseover="this.style.background='#f3f4f6';" onmouseout="this.style.background='#f9fafb';"
                    title="Dismiss">
                <svg style="width:.85rem;height:.85rem;" fill="none" stroke="#6b7280" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Scrollable announcements list --}}
        <div style="overflow-y:auto;flex:1;padding:.75rem 1.4rem;">
            @foreach ($announcements as $ann)
            <div style="padding:.9rem 0;{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9;' : '' }}">
                @if ($ann->is_pinned)
                <div style="display:inline-flex;align-items:center;gap:.3rem;
                             font-size:.6rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;
                             color:var(--gold);margin-bottom:.4rem;">
                    <svg style="width:.65rem;height:.65rem;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 3a1 1 0 011 1v1h1a1 1 0 110 2h-.172l.823 7.407A2 2 0 0116.67 16H13v5a1 1 0 11-2 0v-5H7.33a2 2 0 01-1.981-1.593L5.172 7H5a1 1 0 110-2h1V4a1 1 0 011-1h9z"/>
                    </svg>
                    Pinned
                </div>
                @endif
                <p style="font-size:.85rem;font-weight:700;color:#1e293b;margin:0 0 .35rem;line-height:1.4;">
                    {{ $ann->title }}
                </p>
                <p style="font-size:.78rem;color:#475569;line-height:1.6;margin:0 0 .5rem;white-space:pre-line;">
                    {{ Str::limit($ann->content, 180) }}
                </p>
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:.65rem;color:#94a3b8;">
                    <span>{{ $ann->author->name }}</span>
                    <span>{{ $ann->published_at->format('d M Y') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Footer buttons --}}
        <div style="padding:.9rem 1.4rem;border-top:1px solid #f1f5f9;flex-shrink:0;
                    display:flex;gap:.6rem;">
            <a href="{{ route('cadet.announcements') }}"
               style="flex:1;display:flex;align-items:center;justify-content:center;gap:.4rem;
                      padding:.65rem 1rem;border-radius:.625rem;font-size:.78rem;font-weight:700;
                      letter-spacing:.06em;text-transform:uppercase;text-decoration:none;
                      background:var(--gold);color:var(--navy);
                      box-shadow:0 2px 6px rgba(200,169,81,.3);transition:background .15s;"
               onmouseover="this.style.background='var(--gold2)';" onmouseout="this.style.background='var(--gold)';">
                View All
            </a>
            <button onclick="dismissAnnouncements()"
                    style="flex:1;padding:.65rem 1rem;border-radius:.625rem;font-size:.78rem;font-weight:600;
                           letter-spacing:.06em;text-transform:uppercase;cursor:pointer;
                           color:#475569;border:1px solid #e2e8f0;background:#f8fafc;transition:background .15s;"
                    onmouseover="this.style.background='#f1f5f9';" onmouseout="this.style.background='#f8fafc';">
                Dismiss
            </button>
        </div>
    </div>
</div>
<style>
@keyframes annModalIn {
    from { opacity:0; transform:scale(.93) translateY(14px); }
    to   { opacity:1; transform:none; }
}
</style>
<script>
(function () {
    var modal = document.getElementById('ann-modal');
    var seenKey = 'ann_seen_{{ $announcements->max("id") }}';
    if (sessionStorage.getItem(seenKey)) {
        modal.style.display = 'none';
    }
})();
function dismissAnnouncements() {
    var modal = document.getElementById('ann-modal');
    var seenKey = 'ann_seen_{{ $announcements->max("id") }}';
    modal.style.display = 'none';
    sessionStorage.setItem(seenKey, '1');
}
</script>
@endif

@endsection

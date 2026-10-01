@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Administrator Dashboard')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>
    <a href="{{ route('admin.users.create') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Create Account
    </a>
    <a href="{{ route('admin.announcements.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
        </svg>
        Announcements
    </a>
    <a href="{{ route('admin.attendance.index') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        Attendance
    </a>
@endsection

@section('content')

    {{-- ΓöÇΓöÇ Stats row ΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇ --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

        @php
            $statCards = [
                ['label' => 'Total Accounts',    'value' => $stats['total_users'],     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => '#60a5fa'],
                ['label' => 'Officers',           'value' => $stats['total_officers'],  'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',                                                                                                                                                                                                                                                                                                                    'color' => '#c8a951'],
                ['label' => 'Cadets',             'value' => $stats['total_cadets'],    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',                                                                                                                                                                                                                                                                        'color' => '#34d399'],
                ['label' => 'Active Users',       'value' => $stats['active_users'],    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                                                                                                                                                                                                                                                                         'color' => '#4ade80'],
                ['label' => 'Locked Accounts',    'value' => $stats['locked_accounts'], 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',                                                                                                                                                                                                                                                                                'color' => '#f87171'],
            ];
        @endphp

        @foreach ($statCards as $card)
            <div class="stat-card rounded-xl p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ $card['label'] }}</p>
                        <p class="text-3xl font-bold text-slate-900">{{ $card['value'] }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background: rgba(4,9,15,0.04);">
                        <svg class="w-5 h-5" fill="none" stroke="{{ $card['color'] }}" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ΓöÇΓöÇ Recent accounts table ΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇ --}}
    <div class="card rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: rgba(4,9,15,0.08);">
                <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Recent Accounts</h2>
            <a href="{{ route('admin.users.create') }}"
               class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-colors"
               style="background: rgba(200,169,81,0.15); color: #c8a951; border: 1px solid rgba(200,169,81,0.3);">
                + New Account
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">ID Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recent_users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                @if($user->photo_path)
                                    <img src="{{ Storage::url($user->photo_path) }}"
                                         alt="{{ $user->name }}"
                                         class="w-9 h-9 rounded-full object-cover"
                                         style="border: 2px solid var(--gold);">
                                @else
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black"
                                         style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-900 font-medium">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $user->student_id ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColors = ['admin' => '#60a5fa', 'officer' => '#c8a951', 'cadet' => '#34d399'];
                                    $color = $roleColors[$user->role] ?? '#94a3b8';
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold uppercase"
                                      style="color: {{ $color }}; background: {{ $color }}20; border: 1px solid {{ $color }}40;">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->is_active && !$user->isLocked())
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                          style="background: rgba(74,222,128,0.12); color: #4ade80; border: 1px solid rgba(74,222,128,0.3);">
                                        Active
                                    </span>
                                @elseif ($user->isLocked())
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                          style="background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.3);">
                                        Locked
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                          style="background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.3);">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button type="button"
                                            onclick="openPhotoModal(
                                                {{ $user->id }},
                                                '{{ e(addslashes($user->name)) }}',
                                                '{{ $user->photo_path ? Storage::url($user->photo_path) : '' }}',
                                                '{{ route('admin.users.photo.store', $user) }}',
                                                '{{ route('admin.users.photo.destroy', $user) }}'
                                            )"
                                            class="text-xs px-2 py-1 rounded font-medium transition-colors"
                                            style="background: rgba(200,169,81,0.12); color: #c8a951; border: 1px solid rgba(200,169,81,0.3);">&#128247; Photo
                                    </button>
                                    @if ($user->isLocked())
                                        <form method="POST" action="{{ route('admin.users.unlock', $user) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-xs px-2 py-1 rounded font-medium transition-colors"
                                                    style="background: rgba(200,169,81,0.15); color: #c8a951; border: 1px solid rgba(200,169,81,0.3);">
                                                Unlock
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs px-2 py-1 rounded font-medium transition-colors"
                                                style="background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.2);">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">No user accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ΓöÇΓöÇ Security info panel ΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇΓöÇ --}}
    <div class="mt-6 card rounded-xl p-5">
        <h3 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: #c8a951;">
            Security Policy Summary
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-600">
            <div>
                <p class="font-semibold text-slate-700 mb-1">Account Lockout</p>
                <p>Accounts are locked for <strong class="text-slate-900">15 minutes</strong> after
                   <strong class="text-slate-900">5 consecutive</strong> failed login attempts.</p>
            </div>
            <div>
                <p class="font-semibold text-slate-700 mb-1">Password Storage</p>
                <p>All passwords are hashed using <strong class="text-slate-900">bcrypt</strong>
                   (NIST SP 800-63B compliant). Plain-text passwords are never stored.</p>
            </div>
            <div>
                <p class="font-semibold text-slate-700 mb-1">Session Security</p>
                <p>Sessions auto-expire after <strong class="text-slate-900">30 minutes</strong> of inactivity.
                   Session IDs are regenerated on every login (prevents session fixation).</p>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
{{-- ── Photo Upload Modal ──────────────────────────────────────────────────── --}}
<div id="photo-modal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center p-4"
     style="background: rgba(4,9,15,0.6); backdrop-filter: blur(4px);"
     onclick="if(event.target===this) closePhotoModal()">
    <div class="w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl"
         style="background: #fff; animation: modalIn .2s ease;">
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4"
             style="background: linear-gradient(135deg, var(--navy), #1a2744); border-bottom: 1px solid rgba(200,169,81,.2);">
            <div>
                <h3 class="text-sm font-black text-white">Manage Photo</h3>
                <p id="photo-modal-name" class="text-xs mt-0.5" style="color: var(--gold);"></p>
            </div>
            <button onclick="closePhotoModal()"
                    class="w-7 h-7 rounded-full flex items-center justify-center transition-colors"
                    style="background: rgba(255,255,255,0.1); color: #94a3b8;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-4">
            {{-- Current photo preview --}}
            <div class="flex flex-col items-center gap-3">
                <div id="photo-modal-preview" class="w-24 h-24 rounded-full overflow-hidden shrink-0"
                     style="border: 3px solid var(--gold); box-shadow: 0 4px 20px rgba(200,169,81,.3);">
                </div>
                <p class="text-xs text-slate-400">Current 2×2 photo</p>
            </div>

            {{-- Upload new photo form --}}
            <form id="photo-modal-upload-form" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <label class="block">
                    <span class="text-xs font-semibold text-slate-700 mb-1 block">Upload New 2×2 Photo</span>
                    <input type="file"
                           id="photo-modal-file"
                           name="photo"
                           accept="image/jpeg,image/png,image/jpg"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:cursor-pointer"
                           style="file:background: rgba(200,169,81,0.15); file:color: #c8a951;">
                </label>
                <button type="submit"
                        class="w-full py-2 rounded-xl text-sm font-bold transition-all"
                        style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                    Upload Photo
                </button>
            </form>

            {{-- Remove photo --}}
            <form id="photo-modal-remove-form" method="POST" class="pt-2 border-t border-slate-100">
                @csrf
                @method('DELETE')
                <button type="submit"
                        id="photo-modal-remove-btn"
                        class="w-full py-1.5 rounded-xl text-xs font-semibold transition-colors"
                        style="background: rgba(248,113,113,0.1); color: #f87171; border: 1px solid rgba(248,113,113,0.2);">
                    Remove Photo
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modalIn { from { opacity:0; transform:scale(.92) translateY(12px); } to { opacity:1; transform:none; } }
</style>

<script>
    function openPhotoModal(id, name, photoUrl, uploadUrl, removeUrl) {
        document.getElementById('photo-modal-name').textContent = name;

        const preview = document.getElementById('photo-modal-preview');
        if (photoUrl) {
            preview.innerHTML = '<img src="' + photoUrl + '" class="w-full h-full object-cover" alt="' + name + '">';
        } else {
            preview.innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:900;background:linear-gradient(135deg,var(--gold3),var(--gold));color:var(--navy);">'
                + name.charAt(0).toUpperCase() + '</div>';
        }

        document.getElementById('photo-modal-upload-form').action = uploadUrl;
        document.getElementById('photo-modal-remove-form').action = removeUrl;

        const removeBtn = document.getElementById('photo-modal-remove-btn');
        removeBtn.disabled = !photoUrl;
        removeBtn.style.opacity = photoUrl ? '1' : '0.4';
        removeBtn.style.cursor = photoUrl ? 'pointer' : 'not-allowed';

        document.getElementById('photo-modal').classList.remove('hidden');
        document.getElementById('photo-modal').style.display = 'flex';
    }

    function closePhotoModal() {
        document.getElementById('photo-modal').style.display = 'none';
        document.getElementById('photo-modal').classList.add('hidden');
    }
</script>
@endpush

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'CCJE ROTC')) — 133rd NROTC Portal</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background: var(--navy); color: #1e293b;">
        @include('partials.page-loader')

        <div class="flex min-h-screen">

            {{-- ── Sidebar ──────────────────────────────────────────────────── --}}
            <aside class="app-sidebar flex flex-col w-60 shrink-0">
                <div class="flex flex-col flex-1 py-6 px-4">

                    {{-- Brand --}}
                    <div class="flex items-center gap-3 px-2 mb-8">
                        <img src="{{ asset('133rd NROTC_logo.jpg') }}" alt="133rd NROTC Logo" class="w-10 h-10 rounded-full shrink-0 object-cover"
                             style="box-shadow: 0 0 12px rgba(200,169,81,.4);">
                        <div>
                            <p class="font-black text-sm tracking-widest uppercase" style="color: var(--gold);">133rd NROTC</p>
                            <p class="text-xs text-slate-500 leading-none tracking-wide">CSU — Aparri</p>
                        </div>
                    </div>

                    {{-- Role-specific nav --}}
                    <nav class="flex flex-col gap-1 flex-1">
                        @if(Auth::check())
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="sidebar-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    <span>Create Account</span>
                                </a>
                                <a href="{{ route('admin.enrollments.index') }}" class="sidebar-link {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Enrollments</span>
                                </a>
                                <a href="{{ route('admin.announcements.index') }}" class="sidebar-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    <span>Announcements</span>
                                </a>
                                <a href="{{ route('admin.attendance.index') }}" class="sidebar-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    <span>Attendance</span>
                                </a>
                                <a href="{{ route('admin.exams.index') }}" class="sidebar-link {{ request()->routeIs('admin.exams.index') || request()->routeIs('admin.exams.show') || request()->routeIs('admin.exams.results') || request()->routeIs('admin.exams.attempt') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    <span>Examinations</span>
                                </a>
                                <a href="{{ route('admin.exams.create') }}" class="sidebar-link {{ request()->routeIs('admin.exams.create') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Create Exam</span>
                                </a>
                            @elseif(Auth::user()->isOfficer())
                                <a href="{{ route('officer.dashboard') }}" class="sidebar-link {{ request()->routeIs('officer.dashboard') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>Unit Oversight</span>
                                </a>
                                <a href="{{ route('officer.cadets.index') }}" class="sidebar-link {{ request()->routeIs('officer.cadets.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>Cadets</span>
                                </a>
                                <a href="{{ route('officer.attendance.index') }}" class="sidebar-link {{ request()->routeIs('officer.attendance.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Attendance</span>
                                </a>
                                <a href="{{ route('officer.grades') }}" class="sidebar-link {{ request()->routeIs('officer.grades') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Grades</span>
                                </a>
                                <a href="{{ route('officer.materials.index') }}" class="sidebar-link {{ request()->routeIs('officer.materials.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>Lecture Materials</span>
                                </a>
                            @elseif(Auth::user()->isCadet())
                                <a href="{{ route('cadet.dashboard') }}" class="sidebar-link {{ request()->routeIs('cadet.dashboard') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>My Dashboard</span>
                                </a>
                                <a href="{{ route('cadet.profile') }}" class="sidebar-link {{ request()->routeIs('cadet.profile') || request()->routeIs('profile.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>My Profile</span>
                                </a>
                                <a href="{{ route('cadet.announcements') }}" class="sidebar-link {{ request()->routeIs('cadet.announcements') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    <span>Announcements</span>
                                </a>
                                <a href="{{ route('cadet.attendance') }}" class="sidebar-link {{ request()->routeIs('cadet.attendance') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    <span>My Attendance</span>
                                </a>
                                <a href="{{ route('cadet.materials.index') }}" class="sidebar-link {{ request()->routeIs('cadet.materials.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span>Lecture Materials</span>
                                </a>
                                <a href="{{ route('cadet.exams.index') }}" class="sidebar-link {{ request()->routeIs('cadet.exams.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    <span>Examinations</span>
                                </a>
                            @endif
                        @endif
                    </nav>

                    {{-- User identity chip --}}
                    <div class="px-3 py-2.5 rounded-xl mb-3"
                         style="background: rgba(200,169,81,.05); border: 1px solid rgba(200,169,81,.1);">
                        <div class="flex items-center gap-2.5">
                            @if(Auth::user()->photo_path)
                                <img src="{{ Storage::url(Auth::user()->photo_path) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover shrink-0" style="border: 2px solid var(--gold);">
                            @else
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs shrink-0"
                                     style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="uppercase tracking-widest font-bold" style="color: var(--gold); font-size: .6rem;">
                                    {{ Auth::user()->role }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Logout --}}
                    <div class="pt-3" style="border-top: 1px solid rgba(255,255,255,.06);">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link w-full text-left">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>

                </div>
            </aside>

            {{-- ── Main wrapper ─────────────────────────────────────────────── --}}
            <div class="flex flex-col flex-1 min-w-0" style="background: #f8fafc;">

                {{-- Top bar --}}
                <header class="app-topbar flex items-center justify-between px-8 py-4 shrink-0">
                    <h1 class="text-xs font-bold tracking-widest uppercase" style="color: #1e293b;">
                        @yield('page-title')
                    </h1>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-500">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->photo_path)
                            <img src="{{ Storage::url(Auth::user()->photo_path) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover shrink-0" style="border: 2px solid var(--gold);">
                        @else
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs shrink-0"
                                 style="background: linear-gradient(135deg, var(--gold3), var(--gold)); color: var(--navy);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </header>



                {{-- Page content --}}
                <main class="flex-1 p-8 overflow-auto">
                    @yield('content')
                </main>

            </div>
        </div>
        {{-- Announcement popup modal (cadets only) --}}
        @if (Auth::check() && Auth::user()->isCadet())
            @include('cadet.partials.announcement-modal')
        @endif

        @stack('scripts')
    </body>
</html>

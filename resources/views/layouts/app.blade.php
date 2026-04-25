<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Heartstrings — Attendance') </title>

    {{-- Google Fonts: DM Sans + Playfair Display --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    {{-- Tailwind via CDN (replace with compiled build in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rose:    { DEFAULT: '#E86975', dark: '#BE0822', light: '#EFAAB0' },
                        blush:   { DEFAULT: '#FD9898', light: '#EFAAB0' },
                        cream:   { DEFAULT: '#EED7C8' },
                        ivory:   { DEFAULT: '#FFF9F5' },
                        ruby:    { DEFAULT: '#BE0822' },
                    },
                    fontFamily: {
                        sans:    ['"DM Sans"', 'ui-sans-serif', 'system-ui'],
                        display: ['"Playfair Display"', 'Georgia', 'serif'],
                    },
                    backdropBlur: { xs: '2px' },
                }
            }
        }
    </script>

    <style>
        /* ── Base ─────────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background: linear-gradient(135deg, #E86975 0%, #EED7C8 40%, #FFF9F5 70%, #FD9898 100%);
        }

        /* ── Glassmorphism helpers ────────────────────────────────── */
        .glass {
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 8px 32px rgba(190, 8, 34, 0.10), 0 2px 8px rgba(0,0,0,0.06);
        }
        .glass-strong {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.60);
            box-shadow: 0 12px 48px rgba(190, 8, 34, 0.14), 0 4px 12px rgba(0,0,0,0.08);
        }
        .glass-dark {
            background: rgba(190, 8, 34, 0.18);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.20);
        }

        /* ── Navbar ───────────────────────────────────────────────── */
        .navbar {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.38);
            backdrop-filter: blur(32px) saturate(200%);
            -webkit-backdrop-filter: blur(32px) saturate(200%);
            border: 1px solid rgba(255,255,255,0.60);
            box-shadow: 0 4px 32px rgba(190,8,34,0.12), 0 1px 4px rgba(0,0,0,0.06);
            border-radius: 48px;
            padding: 10px 28px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 32px;
            font-size: 0.82rem;
            font-weight: 500;
            color: #6b2232;
            text-decoration: none;
            transition: all 0.2s ease;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }
    .nav-link:hover {
            background: rgba(190,8,34,0.10);
            color: #BE0822;
        }
        .nav-link.active {
            background: #BE0822;
            color: #fff;
            box-shadow: 0 2px 12px rgba(190,8,34,0.35);
        }
        .nav-link img {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .nav-divider { width: 1px; height: 22px; background: rgba(190,8,34,0.15); margin: 0 4px; }

        /* ── Buttons ──────────────────────────────────────────────── */
        .btn-primary {
            background: linear-gradient(135deg, #BE0822 0%, #E86975 100%);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 13px 28px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(190,8,34,0.30);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(190,8,34,0.40);
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-primary:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none;
        }

        .btn-outline {
            background: rgba(255,255,255,0.40);
            border: 1.5px solid rgba(190,8,34,0.30);
            color: #BE0822;
            border-radius: 14px;
            padding: 11px 24px;
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-outline:hover { background: rgba(190,8,34,0.08); border-color: #BE0822; }

        /* ── Form inputs ──────────────────────────────────────────── */
        .input-glass {
            width: 100%;
            background: rgba(255,255,255,0.50);
            border: 1.5px solid rgba(255,255,255,0.70);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.9rem;
            font-family: inherit;
            color: #3d1a22;
            outline: none;
            transition: all 0.2s ease;
        }
        .input-glass::placeholder { color: rgba(107,34,50,0.45); }
        .input-glass:focus {
            border-color: rgba(190,8,34,0.45);
            background: rgba(255,255,255,0.70);
            box-shadow: 0 0 0 3px rgba(190,8,34,0.10);
        }

        /* ── Card panel ───────────────────────────────────────────── */
        .panel {
            border-radius: 24px;
            padding: 28px;
            position: relative;
            overflow: hidden;
        }

        /* ── Status badges ────────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 32px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        .badge-success { background: rgba(22,163,74,0.15); color: #15803d; border: 1px solid rgba(22,163,74,0.25); }
        .badge-warning { background: rgba(234,179,8,0.15); color: #92400e; border: 1px solid rgba(234,179,8,0.25); }
        .badge-danger  { background: rgba(190,8,34,0.12); color: #BE0822; border: 1px solid rgba(190,8,34,0.20); }

        /* ── Animations ───────────────────────────────────────────── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeSlideUp 0.4s ease both; }
        .delay-1 { animation-delay: 0.08s; }
        .delay-2 { animation-delay: 0.16s; }
        .delay-3 { animation-delay: 0.24s; }

        /* ── Alert flash ──────────────────────────────────────────── */
        .flash {
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 16px;
        }
        .flash-success { background: rgba(22,163,74,0.12); color: #15803d; border: 1px solid rgba(22,163,74,0.25); }
        .flash-error   { background: rgba(190,8,34,0.10); color: #BE0822; border: 1px solid rgba(190,8,34,0.25); }

        /* ── Page wrapper ─────────────────────────────────────────── */
        .page-wrapper { padding-top: 90px; min-height: 100vh; }

        /* ── Scrollbar ────────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(190,8,34,0.25); border-radius: 3px; }

        /* ── Branding watermark ───────────────────────────────────── */
        .brand-watermark {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            color: rgba(190,8,34,0.12);
            font-size: 4rem;
            font-weight: 400;
            position: absolute;
            bottom: 16px;
            right: 20px;
            pointer-events: none;
            user-select: none;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        /* ── Tables ───────────────────────────────────────────────── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            background: rgba(190,8,34,0.08);
            color: #6b2232;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 10px 14px;
            text-align: left;
        }
        .data-table thead th:first-child { border-radius: 10px 0 0 10px; }
        .data-table thead th:last-child  { border-radius: 0 10px 10px 0; }
        .data-table tbody td {
            padding: 11px 14px;
            font-size: 0.85rem;
            color: #3d1a22;
            border-bottom: 1px solid rgba(190,8,34,0.06);
        }
        .data-table tbody tr:hover td { background: rgba(255,255,255,0.35); }
        .data-table tbody tr:last-child td { border-bottom: none; }
    </style>

    @stack('head')
</head>
<body>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{--  TOP-CENTER GLASSMORPHISM NAVBAR                                --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
@auth
<nav class="navbar">
    {{-- Brand logo --}}
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('attendance.index') }}"
       class="flex items-center gap-2 mr-2" style="text-decoration:none;">
        <div style="width:30px;height:30px;background:linear-gradient(135deg,#BE0822,#E86975);border-radius:50%;display:flex;align-items:center;justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.27 2 8.5 2 5.41 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.41 22 8.5c0 3.77-3.4 6.86-8.55 11.53L12 21.35z"/></svg>
        </div>
        <span style="font-family:'Playfair Display',serif;font-weight:600;font-size:0.95rem;color:#BE0822;letter-spacing:-0.01em;">heartstrings</span>
    </a>

    <div class="nav-divider"></div>

    {{-- Dashboard --}}
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('attendance.index') }}"
       class="nav-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('attendance.index') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Dashboard
    </a>

    {{-- My Attendance (user) --}}
    @if(auth()->user()->isUser())
    <a href="{{ route('attendance.history') }}"
       class="nav-link {{ request()->routeIs('attendance.history') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        My Attendance
    </a>

    {{-- My Leaves (user) --}}
    <a href="{{ route('leaves.index') }}"
       class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        My Leaves
    </a>
    @endif

    {{-- Admin Portal --}}
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.recap') }}"
       class="nav-link {{ request()->routeIs('admin.recap*') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Admin Portal
    </a>
    @endif

    {{-- Staff Overview (admin) --}}
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.users.index') }}"
       class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Staff
    </a>
    @endif

    {{-- Leave Approvals (admin) --}}
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.leaves') }}"
       class="nav-link {{ request()->routeIs('admin.leaves') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Leaves
    </a>
    @endif

    <div class="nav-divider"></div>

    {{-- Profile --}}
    <a href="{{ route('profile.show') }}"
       class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
    @if(auth()->user()->photo_path)
            <img src="{{ asset('storage/' . auth()->user()->photo_path) }}" alt="">
        @else
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        @endif
        <span class="hidden sm:inline">{{ Str::words(auth()->user()->name, 1, '') }}</span>
    </a>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;font-family:inherit;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </button>
    </form>
</nav>
@endauth

{{-- ════════════════════════════════════════════════════════════════ --}}
{{--  PAGE CONTENT                                                   --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<div class="page-wrapper">
    @yield('content')
</div>

@stack('scripts')
</body>
</html>

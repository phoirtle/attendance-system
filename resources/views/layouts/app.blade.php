<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="office-latitude" content="{{ env('OFFICE_LATITUDE', -2.985) }}">
    <meta name="office-longitude" content="{{ env('OFFICE_LONGITUDE', 104.732) }}">
    <meta name="office-radius" content="{{ env('OFFICE_RADIUS_METERS', 100) }}">
    <title>@yield('title', 'Heartstrings — Attendance') </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

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

        /* ── Glassmorphism ────────────────────────────────────────── */
        .glass {
            background: rgba(255,255,255,0.28);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.45);
            box-shadow: 0 8px 32px rgba(190,8,34,0.10), 0 2px 8px rgba(0,0,0,0.06);
        }
        .glass-strong {
            background: rgba(255,255,255,0.45);
            backdrop-filter: blur(28px) saturate(180%);
            -webkit-backdrop-filter: blur(28px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.60);
            box-shadow: 0 12px 48px rgba(190,8,34,0.14), 0 4px 12px rgba(0,0,0,0.08);
        }
        .glass-dark {
            background: rgba(190,8,34,0.18);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.20);
        }

        /* ── Navbar ───────────────────────────────────────────────── */
        .navbar {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(255,255,255,0.38);
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
            position: relative;
        }
        .nav-link:hover { background: rgba(190,8,34,0.10); color: #BE0822; }
        .nav-link.active {
            background: #BE0822;
            color: #fff;
            box-shadow: 0 2px 12px rgba(190,8,34,0.35);
        }
        .nav-link img {
            width: 22px; height: 22px;
            border-radius: 50%; object-fit: cover; flex-shrink: 0;
        }
        .nav-divider { width: 1px; height: 22px; background: rgba(190,8,34,0.15); margin: 0 4px; }

        /* ── Nav badge ────────────────────────────────────────────── */
        .nav-badge {
            position: absolute;
            top: -6px; right: -6px;
            min-width: 18px; height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            font-size: 0.63rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            border: 2px solid rgba(255,255,255,0.85);
            pointer-events: none;
            animation: badgePop 0.3s ease both;
        }
        .nav-badge-red   { background: #BE0822; color: #fff; box-shadow: 0 2px 6px rgba(190,8,34,0.45); }
        .nav-badge-green { background: #1a7a4a; color: #fff; box-shadow: 0 2px 6px rgba(26,122,74,0.45); }

        @keyframes badgePop {
            from { opacity: 0; transform: scale(0.5); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* ── Buttons ──────────────────────────────────────────────── */
        .btn-primary {
            background: linear-gradient(135deg, #BE0822 0%, #E86975 100%);
            color: white; border: none; border-radius: 14px;
            padding: 13px 28px; font-size: 0.9rem; font-weight: 600;
            letter-spacing: 0.02em; cursor: pointer; transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(190,8,34,0.30);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(190,8,34,0.40); }
        .btn-primary:active { transform: translateY(0); }
        .btn-primary:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

        .btn-outline {
            background: rgba(255,255,255,0.40);
            border: 1.5px solid rgba(190,8,34,0.30);
            color: #BE0822; border-radius: 14px;
            padding: 11px 24px; font-size: 0.88rem; font-weight: 500;
            cursor: pointer; transition: all 0.2s ease;
        }
        .btn-outline:hover { background: rgba(190,8,34,0.08); border-color: #BE0822; }

        /* ── Form inputs ──────────────────────────────────────────── */
        .input-glass {
            width: 100%; background: rgba(255,255,255,0.50);
            border: 1.5px solid rgba(255,255,255,0.70); border-radius: 12px;
            padding: 12px 16px; font-size: 0.9rem; font-family: inherit;
            color: #3d1a22; outline: none; transition: all 0.2s ease;
        }
        .input-glass::placeholder { color: rgba(107,34,50,0.45); }
        .input-glass:focus {
            border-color: rgba(190,8,34,0.45); background: rgba(255,255,255,0.70);
            box-shadow: 0 0 0 3px rgba(190,8,34,0.10);
        }

        /* ── Card panel ───────────────────────────────────────────── */
        .panel { border-radius: 24px; padding: 28px; position: relative; overflow: hidden; }

        /* ── Status badges ────────────────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: 32px;
            font-size: 0.75rem; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-success { background: rgba(22,163,74,0.15); color: #15803d; border: 1px solid rgba(22,163,74,0.25); }
        .badge-warning { background: rgba(234,179,8,0.15); color: #92400e; border: 1px solid rgba(234,179,8,0.25); }
        .badge-info    { background: rgba(29,78,216,0.12); color: #1d4ed8; border: 1px solid rgba(29,78,216,0.22); }
        .badge-danger  { background: rgba(190,8,34,0.12); color: #BE0822; border: 1px solid rgba(190,8,34,0.20); }

        /* ── Animations ───────────────────────────────────────────── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in  { animation: fadeSlideUp 0.4s ease both; }
        .delay-1  { animation-delay: 0.08s; }
        .delay-2  { animation-delay: 0.16s; }
        .delay-3  { animation-delay: 0.24s; }

        /* ── Alert flash ──────────────────────────────────────────── */
        .flash { border-radius: 12px; padding: 12px 18px; font-size: 0.875rem; font-weight: 500; margin-bottom: 16px; }
        .flash-success { background: rgba(22,163,74,0.12); color: #15803d; border: 1px solid rgba(22,163,74,0.25); }
        .flash-error   { background: rgba(190,8,34,0.10); color: #BE0822; border: 1px solid rgba(190,8,34,0.25); }

        /* ── Page wrapper ─────────────────────────────────────────── */
        .page-wrapper { padding-top: 90px; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Scrollbar ────────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(190,8,34,0.25); border-radius: 3px; }

        /* ── Branding watermark ───────────────────────────────────── */
        .brand-watermark {
            font-family: 'Playfair Display', serif; font-style: italic;
            color: rgba(190,8,34,0.12); font-size: 4rem; font-weight: 400;
            position: absolute; bottom: 16px; right: 20px;
            pointer-events: none; user-select: none;
            letter-spacing: -0.02em; line-height: 1;
        }

        /* ── Tables ───────────────────────────────────────────────── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            background: rgba(190,8,34,0.08); color: #6b2232;
            font-size: 0.75rem; font-weight: 600; letter-spacing: 0.06em;
            text-transform: uppercase; padding: 10px 14px; text-align: left;
        }
        .data-table thead th:first-child { border-radius: 10px 0 0 10px; }
        .data-table thead th:last-child  { border-radius: 0 10px 10px 0; }
        .data-table tbody td {
            padding: 11px 14px; font-size: 0.85rem; color: #3d1a22;
            border-bottom: 1px solid rgba(190,8,34,0.06);
        }
        .data-table tbody tr:hover td { background: rgba(255,255,255,0.35); }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* ── Footer ───────────────────────────────────────────────── */
        .site-footer { margin-top: auto; padding: 16px 24px 24px; text-align: center; }
        .footer-copy { font-size: 0.73rem; color: rgba(107,34,50,0.40); margin: 0; }
    </style>

    @stack('head')
</head>
<body>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{--  NAVBAR                                                         --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
@auth
    @if(auth()->user()->isAdmin())
        <x-nav.admin />
    @else
        <x-nav.user />
    @endif
@endauth

{{-- ════════════════════════════════════════════════════════════════ --}}
{{--  PAGE CONTENT                                                   --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<div class="page-wrapper">
    <div style="flex:1;">
        @yield('content')
    </div>

    @auth
    @if(auth()->user()->isUser())
    <footer class="site-footer">
        <p class="footer-copy">&copy; {{ date('Y') }} Heartstrings &mdash; Employee Attendance System</p>
    </footer>
    @endif
    @endauth
</div>

@stack('scripts')
</body>
</html>

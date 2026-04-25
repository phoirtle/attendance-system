@extends('layouts.app')
@section('title', 'Admin Dashboard — Heartstrings')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px 20px 48px;">

    {{-- Header --}}
    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">{{ now()->format('l, d F Y') }}</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Admin <em>Dashboard</em></h1>
    </div>

    {{-- KPI row --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:20px;">
        @foreach([
            ['Total Staff',   $totalUsers,   '#3d1a22', 'M',  '#FD9898'],
            ['Present Today', $presentToday, '#15803d', '✓',  '#EED7C8'],
            ['Late Today',    $lateToday,    '#92400e', '⏰', '#EFAAB0'],
            ['Leave Today',   $leaveToday,   '#1d4ed8', 'L',  '#EED7C8'],
            ['Absent Today',  $absentToday,  '#BE0822', '✗',  '#E86975'],
        ] as [$label, $val, $txtColor, $icon, $bg])
        <div class="glass-strong panel fade-in delay-1" style="background:{{ $bg }}33;text-align:center;padding:22px 16px;">
            <div style="font-size:2rem;font-weight:800;color:{{ $txtColor }};line-height:1;">{{ $val }}</div>
            <div style="font-size:0.78rem;color:rgba(107,34,50,0.60);font-weight:500;margin-top:4px;letter-spacing:0.02em;">{{ $label }}</div>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:20px;">

        {{-- ── Donut chart: Present vs Absent ───────────────────────── --}}
        <div class="glass-strong panel fade-in delay-1" style="background:rgba(255,255,255,0.50);padding-top:36px;">
            <div style="font-size:0.85rem;font-weight:600;color:#3d1a22;margin-bottom:20px;">Present Employees — Today</div>

            <div style="display:flex;align-items:center;justify-content:center;gap:24px;margin-top:16px;">
                {{-- SVG donut --}}
                @php
                    $pct   = $totalUsers > 0 ? round($presentToday / $totalUsers * 100) : 0;
                    $circ  = 2 * 3.14159 * 42;
                    $dash  = $circ * $pct / 100;
                    $gap   = $circ - $dash;
                @endphp
                <div style="position:relative;width:130px;height:130px;flex-shrink:0;">
                    <svg width="130" height="130" viewBox="0 0 100 100" style="transform:rotate(-90deg);">
                        <circle cx="50" cy="50" r="42" fill="none" stroke="rgba(61,26,34,0.15)" stroke-width="10"/>
                        <circle cx="50" cy="50" r="42" fill="none" stroke="#BE0822" stroke-width="10"
                                stroke-dasharray="{{ $dash }} {{ $gap }}"
                                stroke-linecap="round"
                                style="transition:stroke-dasharray 1s ease;"/>
                    </svg>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <div style="font-size:1.6rem;font-weight:800;color:#3d1a22;line-height:1;">{{ $pct }}%</div>
                        <div style="font-size:0.72rem;color:#3d1a22;font-weight:600;">Present</div>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div>
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:2px;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#14532d;"></div>
                            <span style="font-size:0.78rem;color:#3d1a22;font-weight:500;">Present</span>
                        </div>
                        <div style="font-size:1.1rem;font-weight:700;color:#3d1a22;padding-left:17px;">{{ $presentToday }}</div>
                    </div>
                    <div>
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:2px;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#BE0822;"></div>
                            <span style="font-size:0.78rem;color:#3d1a22;font-weight:500;">Absent</span>
                        </div>
                        <div style="font-size:1.1rem;font-weight:700;color:#3d1a22;padding-left:17px;">{{ $absentToday }}</div>
                    </div>
                    <div>
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:2px;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#78350f;"></div>
                            <span style="font-size:0.78rem;color:#3d1a22;font-weight:500;">Late</span>
                        </div>
                        <div style="font-size:1.1rem;font-weight:700;color:#3d1a22;padding-left:17px;">{{ $lateToday }}</div>
                    </div>
                    <div>
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:2px;">
                            <div style="width:10px;height:10px;border-radius:50%;background:#1d4ed8;"></div>
                            <span style="font-size:0.78rem;color:#3d1a22;font-weight:500;">On Leave</span>
                        </div>
                        <div style="font-size:1.1rem;font-weight:700;color:#3d1a22;padding-left:17px;">{{ $leaveToday }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Bar chart: Late clock-ins this week ─────────────────── --}}
        <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,255,255,0.50);padding-top:36px;">
            <div style="font-size:0.85rem;font-weight:600;color:#3d1a22;margin-bottom:20px;">Late Clock-ins — This Week</div>

            @php
                $counts = array_column($lateByDay, 'count');
                $maxLate = empty($counts) ? 1 : max($counts);
            @endphp
            <div style="display:flex;align-items:flex-end;gap:8px;height:160px;padding-bottom:28px;position:relative;margin-top:40px;">
                @foreach($lateByDay as $day)
                @php $h = $maxLate > 0 ? max(4, round($day['count'] / $maxLate * 90)) : 4; @endphp
                <a href="{{ route('admin.recap', ['date' => $day['date']]) }}"
                   style="flex:1;display:flex;flex-direction:column;align-items:center;gap:0;height:100%;justify-content:flex-end;position:relative;text-decoration:none;"
                   title="View attendance for {{ $day['date'] }}">
                    <div style="width:100%;height:{{ $h }}px;background:linear-gradient(180deg,#BE0822,#E86975);border-radius:6px 6px 3px 3px;transition:height 0.8s ease,opacity 0.2s;position:relative;cursor:pointer;"
                         onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        @if($day['count'] > 0)
                        <div style="position:absolute;top:-20px;left:50%;transform:translateX(-50%);font-size:0.75rem;font-weight:700;color:#3d1a22;">{{ $day['count'] }}</div>
                        @endif
                    </div>
                    <div style="position:absolute;bottom:-22px;font-size:0.75rem;font-weight:600;color:#3d1a22;">{{ $day['day'] }}</div>
                </a>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Quick links --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:20px;">
        <a href="{{ route('admin.recap') }}" class="glass panel fade-in delay-3"
           style="text-decoration:none;display:flex;align-items:center;gap:14px;padding:18px 20px;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#BE0822,#E86975);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div>
                <div style="font-size:0.9rem;font-weight:600;color:#3d1a22;">Monthly Recap</div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);">View & export reports</div>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="glass panel fade-in delay-3"
           style="text-decoration:none;display:flex;align-items:center;gap:14px;padding:18px 20px;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#EED7C8,#EFAAB0);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#BE0822" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div style="font-size:0.9rem;font-weight:600;color:#3d1a22;">Staff Overview</div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);">{{ $totalUsers }} total employees</div>
            </div>
        </a>

        <a href="{{ route('admin.leaves') }}" class="glass panel fade-in delay-3"
           style="text-decoration:none;display:flex;align-items:center;gap:14px;padding:18px 20px;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#EED7C8,#E86975);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#BE0822" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
                <div style="font-size:0.9rem;font-weight:600;color:#3d1a22;">Pending Leaves</div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);">{{ $pendingLeaves }} awaiting approval</div>
            </div>
        </a>

        <a href="{{ route('admin.recap', ['date' => now()->format('Y-m-d')]) }}" class="glass panel fade-in delay-3"
           style="text-decoration:none;display:flex;align-items:center;gap:14px;padding:18px 20px;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform=''">
            <div style="width:42px;height:42px;background:linear-gradient(135deg,#FD9898,#EFAAB0);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#BE0822" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div style="font-size:0.9rem;font-weight:600;color:#3d1a22;">Today's Activity</div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);">{{ $presentToday }}/{{ $totalUsers }} checked in</div>
            </div>
        </a>
    </div>
</div>
@endsection

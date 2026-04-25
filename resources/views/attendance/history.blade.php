@extends('layouts.app')
@section('title', 'My Attendance — Heartstrings')

@section('content')
<div style="max-width:980px;margin:0 auto;padding:24px 20px 48px;">

    {{-- Page header --}}
    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">
            Attendance Records
        </p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">
            My <em>Attendance</em>
        </h1>
    </div>

    {{-- Month filter --}}
    <div class="fade-in delay-1" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('attendance.history') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <select name="month" class="input-glass" style="width:auto;min-width:140px;padding:10px 14px;font-size:0.85rem;">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                    </option>
                @endforeach
            </select>
            <select name="year" class="input-glass" style="width:auto;min-width:100px;padding:10px 14px;font-size:0.85rem;">
                @foreach(range(now()->year - 2, now()->year) as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline" style="padding:10px 18px;font-size:0.85rem;">Filter</button>
        </form>
    </div>

    {{-- Summary cards --}}
    @php
        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount    = $attendances->where('status', 'late')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $totalDays    = $attendances->count();
    @endphp

    <div class="fade-in delay-2" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
        <div class="glass panel" style="padding:18px;text-align:center;">
            <div style="font-size:1.6rem;font-weight:700;color:#3d1a22;">{{ $totalDays }}</div>
            <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);margin-top:4px;">Total Records</div>
        </div>
        <div class="glass panel" style="padding:18px;text-align:center;background:rgba(22,163,74,0.10);border-color:rgba(22,163,74,0.20);">
            <div style="font-size:1.6rem;font-weight:700;color:#15803d;">{{ $presentCount }}</div>
            <div style="font-size:0.75rem;color:rgba(21,128,61,0.70);margin-top:4px;">Present</div>
        </div>
        <div class="glass panel" style="padding:18px;text-align:center;background:rgba(234,179,8,0.10);border-color:rgba(234,179,8,0.20);">
            <div style="font-size:1.6rem;font-weight:700;color:#92400e;">{{ $lateCount }}</div>
            <div style="font-size:0.75rem;color:rgba(146,64,14,0.70);margin-top:4px;">Late</div>
        </div>
        <div class="glass panel" style="padding:18px;text-align:center;background:rgba(190,8,34,0.08);border-color:rgba(190,8,34,0.18);">
            <div style="font-size:1.6rem;font-weight:700;color:#BE0822;">{{ $absentCount }}</div>
            <div style="font-size:0.75rem;color:rgba(190,8,34,0.65);margin-top:4px;">Absent</div>
        </div>
    </div>

    {{-- Attendance table --}}
    <div class="glass-strong panel fade-in delay-3" style="background:rgba(255,249,245,0.65);padding:0;overflow:hidden;">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(190,8,34,0.08);display:flex;justify-content:space-between;align-items:center;">
            <h3 style="font-size:0.95rem;font-weight:600;color:#3d1a22;margin:0;">
                {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
            </h3>
            <span style="font-size:0.78rem;color:rgba(107,34,50,0.50);">{{ $attendances->count() }} records</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:700px;">
                <thead><tr>
                    <th style="padding-left:24px;">Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Distance</th>
                    <th>Location</th>
                    <th style="padding-right:24px;">Status</th>
                </tr></thead>
                <tbody>
                @forelse($attendances as $a)
                <tr>
                    <td style="padding-left:24px;font-weight:500;">{{ $a->date->format('d M Y') }}</td>
                    <td>{{ $a->clock_in ?? '—' }}</td>
                    <td>{{ $a->clock_out ?? '—' }}</td>
                    <td>{{ $a->distance_meters ? $a->distance_meters . 'm' : '—' }}</td>
                    <td>
                        <span class="badge {{ $a->location_status === 'in_range' ? 'badge-success' : 'badge-danger' }}">
                            {{ $a->location_status === 'in_range' ? 'In Range' : 'Out of Range' }}
                        </span>
                    </td>
                    <td style="padding-right:24px;">
                        <span class="badge {{ $a->status === 'present' ? 'badge-success' : ($a->status === 'late' ? 'badge-warning' : ($a->status === 'leave' ? 'badge-info' : 'badge-danger')) }}">
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No attendance records for this month</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')
@section('title', 'Account Activity — Heartstrings')
@section('content')
<div style="max-width:820px;margin:0 auto;padding:24px 20px 48px;">
    <a href="{{ route('profile.show') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Settings
    </a>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:600;color:#3d1a22;margin:0 0 24px;">Account <em>Activity</em></h2>

    <div class="glass-strong panel" style="background:rgba(255,249,245,0.65);padding:0;overflow:hidden;">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(190,8,34,0.08);">
            <h3 style="font-size:0.9rem;font-weight:600;color:#3d1a22;margin:0;">Last 30 Attendance Records</h3>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:600px;">
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
                            {{ $a->location_status === 'in_range' ? 'In Range' : 'Out' }}
                        </span>
                    </td>
                    <td style="padding-right:24px;">
                        <span class="badge {{ $a->status === 'present' ? 'badge-success' : ($a->status === 'late' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $a->status === 'alpha' ? 'Alpha' : ucfirst($a->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:36px;color:rgba(107,34,50,0.45);">No activity records yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

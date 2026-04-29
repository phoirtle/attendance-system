@extends('layouts.app')
@section('title', 'My Salary — Heartstrings')

@section('content')
<div style="max-width:900px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Employee Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">My <em>Salary</em></h1>
    </div>

    {{-- Position Card --}}
    <div class="glass-strong panel fade-in delay-1" style="background:rgba(255,255,255,0.50);margin-bottom:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-bottom:4px;">Current Position</div>
                <div style="font-size:1.15rem;font-weight:700;color:#3d1a22;">{{ $user->salaryPosition->position_name ?? 'Not assigned' }}</div>
                <div style="font-size:0.82rem;color:rgba(107,34,50,0.60);margin-top:2px;">{{ $user->department ?? '—' }}</div>
            </div>
            @if($user->salaryPosition)
            <div style="text-align:right;">
                <div style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-bottom:4px;">Base Salary</div>
                <div style="font-size:1.4rem;font-weight:800;color:#BE0822;">@rupiah($user->salaryPosition->base_salary)</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="glass panel fade-in delay-1" style="margin-bottom:24px;padding:18px 22px;">
        <form method="GET" action="{{ route('salary.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
            <div>
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#3d1a22;margin-bottom:4px;">Month</label>
                <select name="month" class="input-glass" style="width:130px;padding:10px 14px;">
                    <option value="">All</option>
                    @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#3d1a22;margin-bottom:4px;">Year</label>
                <select name="year" class="input-glass" style="width:100px;padding:10px 14px;">
                    <option value="">All</option>
                    @for($y=now()->year-2; $y<=now()->year+1; $y++)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-outline" style="padding:10px 18px;font-size:0.85rem;">Filter</button>
        </form>
    </div>

    {{-- Payroll History --}}
    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:600px;">
                <thead><tr>
                    <th style="padding-left:24px;">Period</th>
                    <th>Base Salary</th>
                    <th>Allowance</th>
                    <th>Deduction</th>
                    <th>Total</th>
                    <th style="padding-right:24px;text-align:right;">Actions</th>
                </tr></thead>
                <tbody>
                @forelse($payrolls as $p)
                <tr>
                    <td style="padding-left:24px;font-weight:600;">{{ \Carbon\Carbon::create($p->year, $p->month)->format('F Y') }}</td>
                    <td>@rupiah($p->base_salary)</td>
                    <td style="color:#15803d;">@rupiah($p->attendance_allowance)</td>
                    <td style="color:#BE0822;">@rupiah($p->deduction)</td>
                    <td style="font-weight:700;color:#3d1a22;">@rupiah($p->total_salary)</td>
                    <td style="padding-right:24px;text-align:right;">
                        <a href="{{ route('salary.show', $p) }}" class="btn-outline" style="text-decoration:none;padding:6px 14px;font-size:0.78rem;border-radius:8px;margin-right:6px;">View</a>
                        <a href="{{ route('salary.print', $p) }}" target="_blank" class="btn-outline" style="text-decoration:none;padding:6px 14px;font-size:0.78rem;border-radius:8px;">Download</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No payroll history found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


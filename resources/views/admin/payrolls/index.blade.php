@extends('layouts.app')
@section('title', 'Employee Payrolls — Heartstrings')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Employee <em>Payrolls</em></h1>
    </div>

    @if(session('success'))
    <div class="flash flash-success fade-in" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif

    {{-- Filters & Generate --}}
    <div class="glass panel fade-in delay-1" style="margin-bottom:24px;padding:18px 22px;">
        <form method="GET" action="{{ route('admin.payrolls.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
            <div>
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#3d1a22;margin-bottom:4px;">Month</label>
                <select name="month" class="input-glass" style="width:130px;padding:10px 14px;">
                    @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#3d1a22;margin-bottom:4px;">Year</label>
                <select name="year" class="input-glass" style="width:100px;padding:10px 14px;">
                    @for($y=now()->year-2; $y<=now()->year+1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.75rem;font-weight:600;color:#3d1a22;margin-bottom:4px;">Department</label>
                <select name="department" class="input-glass" style="width:160px;padding:10px 14px;">
                    <option value="">All</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ $department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-outline" style="padding:10px 18px;font-size:0.85rem;">Filter</button>
        </form>

        <form method="POST" action="{{ route('admin.payrolls.generate') }}" style="margin-top:14px;border-top:1px solid rgba(190,8,34,0.08);padding-top:14px;display:flex;align-items:center;gap:12px;">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <span style="font-size:0.82rem;color:#3d1a22;font-weight:500;">Generate payroll for {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}:</span>
            <button type="submit" class="btn-primary" style="padding:9px 18px;font-size:0.82rem;border-radius:10px;" onclick="return confirm('Generate payroll slips for all employees with salary positions?');">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;margin-right:4px;"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                Generate Now
            </button>
        </form>
    </div>

    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:800px;">
                <thead><tr>
                    <th style="padding-left:24px;">Employee</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Base Salary</th>
                    <th>Allowance</th>
                    <th>Deduction</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="padding-right:24px;text-align:right;">Actions</th>
                </tr></thead>
                <tbody>
                @forelse($payrolls as $p)
                <tr>
                    <td style="padding-left:24px;font-weight:600;">{{ $p->employee->name ?? '—' }}</td>
                    <td>{{ $p->salaryPosition->position_name ?? '—' }}</td>
                    <td>{{ $p->employee->department ?? '—' }}</td>
                    <td>@rupiah($p->base_salary)</td>
                    <td>@rupiah($p->attendance_allowance)</td>
                    <td style="color:#BE0822;">@rupiah($p->deduction)</td>
                    <td style="font-weight:700;color:#3d1a22;">@rupiah($p->total_salary)</td>
                    <td>
                        @if($p->status === 'finalized')
                            <span class="badge badge-success">Finalized</span>
                        @else
                            <span class="badge badge-warning">Draft</span>
                        @endif
                    </td>
                    <td style="padding-right:24px;text-align:right;">
                        <a href="{{ route('admin.payrolls.show', $p) }}" class="btn-outline" style="text-decoration:none;padding:6px 14px;font-size:0.78rem;border-radius:8px;margin-right:6px;">View</a>
                        <a href="{{ route('admin.payrolls.print', $p) }}" target="_blank" class="btn-outline" style="text-decoration:none;padding:6px 14px;font-size:0.78rem;border-radius:8px;">Print</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No payrolls found for this period. Click Generate Now to create them.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')
@section('title', 'Payroll Recap — Heartstrings')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Payroll <em>Recap</em></h1>
    </div>

    {{-- Filters --}}
    <div class="glass panel fade-in delay-1" style="margin-bottom:24px;padding:18px 22px;">
        <form method="GET" action="{{ route('admin.payrolls.recap') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
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
            <button type="submit" class="btn-outline" style="padding:10px 18px;font-size:0.85rem;">Show Recap</button>
        </form>
    </div>

    {{-- KPI --}}
    <div class="glass-strong panel fade-in delay-1" style="background:rgba(255,255,255,0.50);text-align:center;padding:32px 24px;margin-bottom:24px;">
        <div style="font-size:0.85rem;color:rgba(107,34,50,0.60);font-weight:500;margin-bottom:8px;">Total Payroll Expense — {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</div>
        <div style="font-size:2.4rem;font-weight:800;color:#BE0822;line-height:1;">@rupiah($totalExpense)</div>
        <div style="font-size:0.85rem;color:rgba(107,34,50,0.55);margin-top:6px;">{{ $payrolls->count() }} employees paid</div>
    </div>

    {{-- Bar Chart by Department --}}
    @if(count($byDepartment) > 0)
    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,255,255,0.50);padding-top:36px;margin-bottom:24px;">
        <div style="font-size:0.85rem;font-weight:600;color:#3d1a22;margin-bottom:20px;">Payroll by Department</div>

        @php
            $maxVal = max($byDepartment);
            $deptColors = ['#BE0822','#E86975','#FD9898','#EFAAB0','#EED7C8','#3d1a22'];
        @endphp
        <div style="display:flex;align-items:flex-end;gap:10px;height:180px;padding-bottom:36px;position:relative;margin-top:20px;">
            @foreach($byDepartment as $dept => $amount)
            @php $h = $maxVal > 0 ? max(8, round($amount / $maxVal * 120)) : 8; $color = $deptColors[$loop->index % count($deptColors)]; @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;height:100%;justify-content:flex-end;position:relative;">
                <div style="font-size:0.72rem;font-weight:700;color:#3d1a22;">Rp {{ number_format($amount/1000000,1) }}M</div>
                <div style="width:100%;height:{{ $h }}px;background:{{ $color }};border-radius:6px 6px 3px 3px;opacity:0.85;"></div>
                <div style="position:absolute;bottom:-28px;font-size:0.72rem;font-weight:600;color:#3d1a22;text-align:center;white-space:nowrap;max-width:100%;overflow:hidden;text-overflow:ellipsis;">{{ $dept }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Table --}}
    <div class="glass-strong panel fade-in delay-3" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:600px;">
                <thead><tr>
                    <th style="padding-left:24px;">Employee</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th style="text-align:right;">Base</th>
                    <th style="text-align:right;">Allowance</th>
                    <th style="text-align:right;">Deduction</th>
                    <th style="padding-right:24px;text-align:right;">Total</th>
                </tr></thead>
                <tbody>
                @forelse($payrolls as $p)
                <tr>
                    <td style="padding-left:24px;font-weight:600;">{{ $p->employee->name }}</td>
                    <td>{{ $p->employee->department ?? '—' }}</td>
                    <td>{{ $p->salaryPosition->position_name ?? '—' }}</td>
                    <td style="text-align:right;">@rupiah($p->base_salary)</td>
                    <td style="text-align:right;">@rupiah($p->attendance_allowance)</td>
                    <td style="text-align:right;color:#BE0822;">@rupiah($p->deduction)</td>
                    <td style="padding-right:24px;text-align:right;font-weight:700;">@rupiah($p->total_salary)</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No payroll data for this period.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


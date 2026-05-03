@extends('layouts.app')
@section('title', 'Payroll Slip — Heartstrings')

@section('content')
<div style="max-width:700px;margin:0 auto;padding:24px 20px 48px;">

    {{-- Page Header --}}
    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Employee Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">
            Payroll <em>Slip</em>
        </h1>
    </div>

    {{-- Back Button --}}
    <div class="fade-in" style="margin-bottom:20px;">
        <a href="{{ route('salary.index') }}" class="btn-outline" style="text-decoration:none;padding:8px 16px;font-size:0.82rem;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
            ← Back to My Salary
        </a>
    </div>

    {{-- Employee & Period Info --}}
    <div class="glass-strong panel fade-in delay-1" style="background:rgba(255,255,255,0.50);margin-bottom:20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);font-weight:600;letter-spacing:0.05em;text-transform:uppercase;margin-bottom:6px;">Employee</div>
                <div style="font-size:1.1rem;font-weight:700;color:#3d1a22;">{{ $payroll->employee->name }}</div>
                <div style="font-size:0.82rem;color:rgba(107,34,50,0.60);margin-top:2px;">{{ $payroll->employee->department ?? '—' }}</div>
                <div style="font-size:0.82rem;color:rgba(107,34,50,0.60);margin-top:1px;">{{ $payroll->salaryPosition->position_name ?? 'No Position' }}</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);font-weight:600;letter-spacing:0.05em;text-transform:uppercase;margin-bottom:6px;">Pay Period</div>
                <div style="font-size:1.1rem;font-weight:700;color:#3d1a22;">
                    {{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('F Y') }}
                </div>
                <div style="font-size:0.78rem;color:rgba(107,34,50,0.50);margin-top:4px;">
                    Status:
                    <span style="font-weight:600;color:{{ $payroll->status === 'finalized' ? '#1a7a4a' : '#BE0822' }};">
                        {{ ucfirst($payroll->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Summary --}}
    <div class="glass panel fade-in delay-1" style="margin-bottom:20px;padding:20px 22px;">
        <div style="font-size:0.78rem;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin-bottom:14px;">Attendance Summary</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(100px,1fr));gap:12px;">

            <div style="text-align:center;padding:12px 8px;background:rgba(107,34,50,0.05);border-radius:10px;">
                <div style="font-size:1.5rem;font-weight:800;color:#3d1a22;">{{ $calc['work_days'] }}</div>
                <div style="font-size:0.72rem;color:rgba(107,34,50,0.55);margin-top:2px;">Work Days</div>
            </div>

            <div style="text-align:center;padding:12px 8px;background:rgba(26,122,74,0.07);border-radius:10px;">
                <div style="font-size:1.5rem;font-weight:800;color:#1a7a4a;">{{ $calc['present_days'] }}</div>
                <div style="font-size:0.72rem;color:rgba(107,34,50,0.55);margin-top:2px;">Present</div>
            </div>

            <div style="text-align:center;padding:12px 8px;background:rgba(190,8,34,0.06);border-radius:10px;">
                <div style="font-size:1.5rem;font-weight:800;color:#BE0822;">{{ $calc['alpha'] }}</div>
                <div style="font-size:0.72rem;color:rgba(107,34,50,0.55);margin-top:2px;">Alpha</div>
            </div>

            <div style="text-align:center;padding:12px 8px;background:rgba(210,120,0,0.07);border-radius:10px;">
                <div style="font-size:1.5rem;font-weight:800;color:#b36b00;">{{ $calc['late_days'] }}</div>
                <div style="font-size:0.72rem;color:rgba(107,34,50,0.55);margin-top:2px;">Late</div>
            </div>

            <div style="text-align:center;padding:12px 8px;background:rgba(60,80,200,0.06);border-radius:10px;">
                <div style="font-size:1.5rem;font-weight:800;color:#3450c8;">{{ $calc['leave_days'] }}</div>
                <div style="font-size:0.72rem;color:rgba(107,34,50,0.55);margin-top:2px;">Leave</div>
            </div>

        </div>
    </div>

    {{-- Salary Breakdown --}}
    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);margin-bottom:20px;padding:0;overflow:hidden;">
        <div style="padding:16px 22px 10px;border-bottom:1px solid rgba(107,34,50,0.08);">
            <div style="font-size:0.78rem;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;color:rgba(107,34,50,0.55);">Salary Breakdown</div>
        </div>

        {{-- Base Salary --}}
        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 22px;border-bottom:1px solid rgba(107,34,50,0.06);">
            <div>
                <div style="font-size:0.88rem;font-weight:600;color:#3d1a22;">Base Salary</div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.50);margin-top:1px;">{{ $payroll->salaryPosition->position_name ?? '—' }}</div>
            </div>
            <div style="font-size:1rem;font-weight:700;color:#3d1a22;">@rupiah($calc['base_salary'])</div>
        </div>

        {{-- Deduction --}}
        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 22px;border-bottom:1px solid rgba(107,34,50,0.06);">
            <div>
                <div style="font-size:0.88rem;font-weight:600;color:#BE0822;">Deduction (Alpha)</div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.50);margin-top:1px;">{{ $calc['alpha'] }} hari × Rp 50.000</div>
            </div>
            <div style="font-size:1rem;font-weight:700;color:#BE0822;">− @rupiah($calc['deduction'])</div>
        </div>

        {{-- Total --}}
        <div style="display:flex;justify-content:space-between;align-items:center;padding:18px 22px;background:rgba(107,34,50,0.04);">
            <div style="font-size:1rem;font-weight:800;color:#3d1a22;">Total Take-Home</div>
            <div style="font-size:1.35rem;font-weight:800;color:#BE0822;">@rupiah($calc['total_salary'])</div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="fade-in delay-2" style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('salary.print', $payroll) }}" target="_blank"
           class="btn-outline"
           style="text-decoration:none;padding:10px 20px;font-size:0.85rem;border-radius:10px;display:inline-flex;align-items:center;gap:6px;">
            🖨 Print / Download
        </a>
        <a href="{{ route('salary.index') }}"
           class="btn-outline"
           style="text-decoration:none;padding:10px 20px;font-size:0.85rem;border-radius:10px;">
            ← Back
        </a>
    </div>

</div>
@endsection
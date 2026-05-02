@extends('layouts.app')
@section('title', 'Payroll Slip — Heartstrings')

@section('content')
<div style="max-width:700px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Payroll <em>Slip</em></h1>
    </div>

    <div class="glass-strong panel fade-in delay-1">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;">
            <div>
                <div style="font-size:1.1rem;font-weight:700;color:#3d1a22;">{{ $payroll->employee->name }}</div>
                <div style="font-size:0.85rem;color:rgba(107,34,50,0.60);margin-top:2px;">{{ $payroll->salaryPosition->position_name ?? '—' }} · {{ $payroll->employee->department ?? '—' }}</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:0.85rem;font-weight:600;color:#3d1a22;">{{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('F Y') }}</div>
                <div style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-top:2px;">Status: {{ ucfirst($payroll->status) }}</div>
            </div>
        </div>

        <div style="border-top:1px solid rgba(190,8,34,0.10);padding-top:20px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                <div class="glass panel" style="padding:16px 18px;">
                    <div style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-bottom:4px;">Work Days</div>
                    <div style="font-size:1.2rem;font-weight:700;color:#3d1a22;">{{ $calc['work_days'] }} days</div>
                </div>
                <div class="glass panel" style="padding:16px 18px;">
                    <div style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-bottom:4px;">Present Days</div>
                    <div style="font-size:1.2rem;font-weight:700;color:#15803d;">{{ $calc['present_days'] + $calc['late_days'] }} days</div>
                </div>
                <div class="glass panel" style="padding:16px 18px;">
                    <div style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-bottom:4px;">Absent Days</div>
                    <div style="font-size:1.2rem;font-weight:700;color:#BE0822;">{{ $calc['absent_days'] }} days</div>
                </div>
                <div class="glass panel" style="padding:16px 18px;">
                    <div style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-bottom:4px;">Leave Days</div>
                    <div style="font-size:1.2rem;font-weight:700;color:#1d4ed8;">{{ $calc['leave_days'] }} days</div>
                </div>
            </div>

            <table style="width:100%;font-size:0.9rem;color:#3d1a22;margin-bottom:20px;border-collapse:collapse;">
                <tr style="border-bottom:1px solid rgba(190,8,34,0.08);">
                    <td style="padding:10px 0;">Base Salary</td>
                    <td style="padding:10px 0;text-align:right;font-weight:600;">@rupiah($payroll->base_salary)</td>
                </tr>
<tr style="border-bottom:1px solid rgba(190,8,34,0.08);">
                    <td style="padding:10px 0;">Alpha (Hari Tidak Hadir)</td>
                    <td style="padding:10px 0;text-align:right;font-weight:600;color:#BE0822;">{{ $payroll->alpha }} hari</td>
                </tr>
                <tr style="border-bottom:1px solid rgba(190,8,34,0.08);">
                    <td style="padding:10px 0;">Deduction (Absent)</td>
                    <td style="padding:10px 0;text-align:right;font-weight:600;color:#BE0822;">- @rupiah($payroll->deduction)</td>
                </tr>
                <tr>
                    <td style="padding:14px 0;font-size:1.05rem;font-weight:700;">Total Take-Home Pay</td>
                    <td style="padding:14px 0;text-align:right;font-size:1.15rem;font-weight:800;color:#BE0822;">@rupiah($payroll->total_salary)</td>
                </tr>
            </table>

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('admin.payrolls.index', ['month'=>$payroll->month,'year'=>$payroll->year]) }}" class="btn-outline" style="text-decoration:none;">Back</a>
                <a href="{{ route('admin.payrolls.print', $payroll) }}" target="_blank" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Print / PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


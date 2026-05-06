<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll Slip - {{ $payroll->employee->name }} - {{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('F Y') }}</title>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        body { font-family: 'DM Sans', system-ui, sans-serif; color: #3d1a22; background: #fff; margin: 0; padding: 40px; }
        .header { text-align: center; margin-bottom: 32px; }
        .header h1 { font-family: 'Playfair Display', serif; font-size: 1.6rem; margin: 0 0 4px; color: #BE0822; }
        .header p { font-size: 0.85rem; color: #6b2232; margin: 0; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 24px; font-size: 0.9rem; }
        .info-row div { line-height: 1.6; }
        table.details { width: 100%; border-collapse: collapse; font-size: 0.95rem; margin-bottom: 24px; }
        table.details td { padding: 10px 0; border-bottom: 1px solid #eee; }
        table.details td:last-child { text-align: right; font-weight: 600; }
        table.details tr.total td { border-top: 2px solid #BE0822; border-bottom: none; padding-top: 14px; font-size: 1.05rem; font-weight: 800; color: #BE0822; }
        .footer { margin-top: 40px; text-align: center; font-size: 0.78rem; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Heartstrings</h1>
        <p>Payroll Slip — {{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('F Y') }}</p>
    </div>

    <div class="info-row">
        <div>
            <strong>{{ $payroll->employee->name }}</strong><br>
            {{ $payroll->salaryPosition->position_name ?? '—' }}<br>
            {{ $payroll->employee->department ?? '—' }}
        </div>
        <div style="text-align:right;">
            <strong>Slip ID:</strong> #{{ str_pad($payroll->id, 5, '0', STR_PAD_LEFT) }}<br>
            <strong>Date:</strong> {{ now()->format('d F Y') }}
        </div>
    </div>

    <table class="details">
        <tr>
            <td>Base Salary</td>
            <td>Rp {{ number_format($calc['base_salary'], 0, ',', '.') }}</td>
        </tr>
<tr>
            <td>Alpha (Hari Tidak Hadir)</td>
            <td style="color:#BE0822;">{{ $calc['alpha'] }} hari</td>
        </tr>
        <tr>
            <td>Deduction (Alpha: {{ $calc['absent_days'] }} days x 4.5% base salary)</td>
            <td style="color:#BE0822;">- Rp {{ number_format($calc['deduction'], 0, ',', '.') }}</td>
        </tr>
        <tr class="total">
            <td>Total Take-Home Pay</td>
            <td>Rp {{ number_format($calc['total_salary'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <div style="font-size:0.85rem;color:#666;line-height:1.6;margin-bottom:32px;">
        <strong>Attendance Summary:</strong><br>
        Work Days: {{ $calc['work_days'] }} | Present: {{ $calc['present_days'] + $calc['late_days'] }} | Late: {{ $calc['late_days'] }} | Alpha: {{ $calc['absent_days'] }} | Leave: {{ $calc['leave_days'] }}
    </div>

    <div class="footer">
        This document is computer-generated and does not require a signature.<br>
        Heartstrings Attendance System
    </div>

</body>
</html>


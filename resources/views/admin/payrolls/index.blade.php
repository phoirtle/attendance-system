@extends('layouts.app')
@section('title', 'Employee Payrolls — Heartstrings')

@section('content')
<style>
    .payroll-table {
        table-layout: fixed;
        min-width: 1120px;
    }

    .payroll-table th,
    .payroll-table td {
        vertical-align: middle;
    }

    .payroll-table .col-employee { width: 210px; }
    .payroll-table .col-position { width: 145px; }
    .payroll-table .col-department { width: 120px; }
    .payroll-table .col-money { width: 135px; text-align: right; }
    .payroll-table .col-alpha { width: 85px; text-align: center; }
    .payroll-table .col-status { width: 110px; text-align: center; }
    .payroll-table .col-actions { width: 155px; text-align: right; }

    .payroll-money {
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
        text-align: right;
    }

    .payroll-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        white-space: nowrap;
    }

    .payroll-action-btn {
        min-width: 58px;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        font-size: 0.78rem !important;
        line-height: 1.1;
        text-decoration: none;
    }
</style>

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
            <table class="data-table payroll-table">
                <thead><tr>
                    <th class="col-employee" style="padding-left:24px;">Employee</th>
                    <th class="col-position">Position</th>
                    <th class="col-department">Department</th>
                    <th class="col-money">Base Salary</th>
                    <th class="col-alpha">Alpha</th>
                    <th class="col-money">Deduction</th>
                    <th class="col-money">Total</th>
                    <th class="col-status">Status</th>
                    <th class="col-actions" style="padding-right:24px;">Actions</th>
                </tr></thead>
                <tbody>
                @forelse($payrolls as $p)
                <tr>
                    <td style="padding-left:24px;font-weight:600;">{{ $p->employee->name ?? '—' }}</td>
                    <td>{{ $p->salaryPosition->position_name ?? '—' }}</td>
                    <td>{{ $p->employee->department ?? '—' }}</td>
                    <td class="payroll-money">@rupiah($p->base_salary)</td>
                    <td style="text-align:center;white-space:nowrap;">{{ $p->alpha }} hari</td>
                    <td class="payroll-money" style="color:#BE0822;">@rupiah($p->deduction)</td>
                    <td class="payroll-money" style="font-weight:700;color:#3d1a22;">@rupiah($p->total_salary)</td>
                    <td style="text-align:center;">
                        @if($p->status === 'finalized')
                            <span class="badge badge-success">Finalized</span>
                        @else
                            <span class="badge badge-warning">Draft</span>
                        @endif
                    </td>
                    <td style="padding-right:24px;">
                        <div class="payroll-actions">
                            <a href="{{ route('admin.payrolls.show', $p) }}" class="btn-outline payroll-action-btn">View</a>
                            <a href="{{ route('admin.payrolls.print', $p) }}" target="_blank" class="btn-outline payroll-action-btn">Print</a>
                        </div>
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

@push('scripts')
<script>
    const monthSelect = document.querySelector('select[name="month"]');
    const yearSelect  = document.querySelector('select[name="year"]');
    const hiddenMonth = document.querySelector('form[action*="generate"] input[name="month"]');
    const hiddenYear  = document.querySelector('form[action*="generate"] input[name="year"]');
    const generateLabel = document.querySelector('form[action*="generate"] span');

    function syncGenerate() {
        const m = monthSelect.value;
        const y = yearSelect.value;
        hiddenMonth.value = m;
        hiddenYear.value  = y;

        const monthName = monthSelect.options[monthSelect.selectedIndex].text;
        generateLabel.textContent = `Generate payroll for ${monthName} ${y}:`;
    }

    monthSelect.addEventListener('change', syncGenerate);
    yearSelect.addEventListener('change', syncGenerate);
</script>
@endpush
@endsection


@extends('layouts.app')
@section('title', 'Monthly Recap — Admin')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px 20px 48px;">

    {{-- Header --}}
    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">
            Monthly <em>Recap</em>
        </h1>
    </div>

    {{-- Filter controls --}}
    <div class="glass-strong panel fade-in delay-1" style="background:rgba(238,215,200,0.40);margin-bottom:20px;">

        <form method="GET" action="{{ route('admin.recap') }}" style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
            {{-- Month picker --}}
            <div>
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Month</label>
                <div style="display:flex;align-items:center;gap:8px;">
                    <button type="button" onclick="shiftMonth(-1)"
                            style="width:34px;height:34px;border-radius:10px;border:1.5px solid rgba(190,8,34,0.25);background:rgba(255,255,255,0.50);cursor:pointer;display:flex;align-items:center;justify-content:center;color:#BE0822;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <div class="glass" style="padding:8px 20px;border-radius:10px;font-size:0.9rem;font-weight:600;color:#3d1a22;min-width:130px;text-align:center;" id="monthDisplay">
                        {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
                    </div>
                    <button type="button" onclick="shiftMonth(1)"
                            style="width:34px;height:34px;border-radius:10px;border:1.5px solid rgba(190,8,34,0.25);background:rgba(255,255,255,0.50);cursor:pointer;display:flex;align-items:center;justify-content:center;color:#BE0822;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                    <input type="hidden" name="month" id="monthInput" value="{{ $month }}">
                    <input type="hidden" name="year"  id="yearInput"  value="{{ $year }}">
                </div>
            </div>

            {{-- Buttons --}}
            <button type="submit" class="btn-primary" style="border-radius:12px;padding:10px 22px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;margin-right:6px;vertical-align:-2px;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Filter
            </button>

            <a href="{{ route('admin.recap.export', ['month' => $month, 'year' => $year]) }}"
               class="btn-outline" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:12px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
        </form>
    </div>

    {{-- Summary stats --}}
    @php
        $total    = $attendances->count();
        $present  = $attendances->where('status', 'present')->count();
        $late     = $attendances->where('status', 'late')->count();
        $alpha    = $attendances->whereIn('status', ['absent', 'alpha'])->count();
    @endphp
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
        @foreach([
            ['Total Records', $total,   '#3d1a22', '📋'],
            ['Present',       $present, '#15803d', '✓'],
            ['Late',          $late,    '#92400e', '⏰'],
            ['Alpha',         $alpha,   '#BE0822', 'A'],
        ] as [$label, $val, $color, $icon])
        <div class="glass panel fade-in delay-1" style="padding:18px 20px;text-align:center;">
            <div style="font-size:1.5rem;margin-bottom:4px;">{{ $icon }}</div>
            <div style="font-size:1.6rem;font-weight:700;color:{{ $color }};">{{ $val }}</div>
            <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);font-weight:500;letter-spacing:0.03em;">{{ $label }}</div>
        </div>
        @endforeach
    </div>

    {{-- Data table --}}
    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="padding:20px 24px 0;display:flex;justify-content:space-between;align-items:center;">
            <h3 style="font-size:0.95rem;font-weight:600;color:#3d1a22;margin:0;">
                Attendance Records — {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </h3>
            <span style="font-size:0.78rem;color:rgba(107,34,50,0.50);">{{ $total }} records</span>
        </div>
        <div style="padding:14px 0;overflow-x:auto;">
            <table class="data-table" style="min-width:720px;">
                <thead><tr>
                    <th style="padding-left:24px;">Employee</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>Distance</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th style="padding-right:24px;">Photo</th>
                </tr></thead>
                <tbody>
                @forelse($attendances as $a)
                <tr>
                    <td style="padding-left:24px;font-weight:600;">{{ $a->user->name ?? '—' }}</td>
                    <td style="color:rgba(107,34,50,0.65);">{{ $a->user->department ?? '—' }}</td>
                    <td>{{ $a->date->format('d M') }}</td>
                    <td>
                        @if($a->clock_in)
                            <span style="font-weight:500;">{{ $a->clock_in }}</span>
                        @else
                            <span style="color:rgba(107,34,50,0.35);">—</span>
                        @endif
                    </td>
                    <td>{{ $a->clock_out ?? '—' }}</td>
                    <td>{{ $a->distance_meters ? $a->distance_meters . 'm' : '—' }}</td>
                    <td>
                        <span class="badge {{ $a->location_status === 'in_range' ? 'badge-success' : 'badge-danger' }}">
                            {{ $a->location_status === 'in_range' ? '✓ In Range' : '✗ Out' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $a->status === 'present' ? 'badge-success' : ($a->status === 'late' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $a->status === 'alpha' ? 'Alpha' : ucfirst($a->status) }}
                        </span>
                    </td>
                    <td style="padding-right:24px;">
                        @if($a->photo_path)
                        <button onclick="showPhoto('{{ asset('storage/'.$a->photo_path) }}')"
                                style="background:none;border:none;cursor:pointer;padding:0;">
                            <img src="{{ asset('storage/'.$a->photo_path) }}" alt="Photo"
                                 style="width:34px;height:34px;border-radius:8px;object-fit:cover;border:1.5px solid rgba(255,255,255,0.70);">
                        </button>
                        @else
                            <span style="color:rgba(107,34,50,0.30);font-size:0.78rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">
                        No attendance records for {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Photo modal --}}
<div id="photoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.60);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(8px);" onclick="closePhotoModal()">
    <div class="glass-strong" style="padding:8px;border-radius:20px;max-width:380px;width:90%;" onclick="event.stopPropagation()">
        <img id="modalPhoto" src="" alt="Attendance photo" style="width:100%;border-radius:14px;display:block;">
        <button onclick="closePhotoModal()" style="display:block;width:100%;margin-top:10px;padding:10px;background:rgba(190,8,34,0.12);border:none;border-radius:10px;cursor:pointer;color:#BE0822;font-weight:600;font-family:inherit;font-size:0.875rem;">Close</button>
    </div>
</div>

@push('scripts')
<script>
// Month navigator
let curMonth = {{ $month }};
let curYear  = {{ $year }};
const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

function shiftMonth(delta) {
    curMonth += delta;
    if (curMonth > 12) { curMonth = 1; curYear++; }
    if (curMonth < 1)  { curMonth = 12; curYear--; }
    document.getElementById('monthInput').value  = curMonth;
    document.getElementById('yearInput').value   = curYear;
    document.getElementById('monthDisplay').textContent = monthNames[curMonth - 1] + ' ' + curYear;
}

function showPhoto(url) {
    document.getElementById('modalPhoto').src = url;
    document.getElementById('photoModal').style.display = 'flex';
}
function closePhotoModal() {
    document.getElementById('photoModal').style.display = 'none';
}
</script>
@endpush
@endsection

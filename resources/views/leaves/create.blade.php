@extends('layouts.app')
@section('title', 'Request Leave — Heartstrings')

@section('content')
<div style="max-width:540px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <a href="{{ route('leaves.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            Back to My Leaves
        </a>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Request <em>Leave</em></h1>
        <p style="font-size:0.85rem;color:rgba(107,34,50,0.55);margin-top:6px;">Remaining quota: <strong>{{ auth()->user()->remainingLeaveDays() }} / 12 days</strong></p>
    </div>

    <div class="glass-strong panel fade-in delay-1">
        <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Leave Type</label>
                <select name="type" id="leaveType" class="input-glass" required onchange="toggleDocumentField()">
                    <option value="">Select type</option>
                    <option value="annual" {{ old('type') === 'annual' ? 'selected' : '' }}>Annual Leave</option>
                    <option value="sick" {{ old('type') === 'sick' ? 'selected' : '' }}>Sick Leave</option>
                    <option value="permission" {{ old('type') === 'permission' ? 'selected' : '' }}>Permission</option>
                </select>
                @error('type')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="input-glass" required>
                    @error('start_date')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="input-glass" required>
                    @error('end_date')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Reason</label>
                <textarea name="reason" class="input-glass" rows="4" placeholder="Describe your reason..." required style="resize:vertical;">{{ old('reason') }}</textarea>
                @error('reason')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div id="documentField" style="margin-bottom:24px;display:none;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Medical Document <span style="text-transform:none;font-weight:400;color:rgba(107,34,50,0.50);">(Required for sick leave)</span></label>
                <input type="file" name="document" id="documentInput" class="input-glass" accept=".pdf,.jpg,.jpeg,.png" style="padding:10px 14px;font-size:0.85rem;">
                @error('document')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-primary" style="width:100%;border-radius:12px;">Submit Request</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleDocumentField() {
    const type = document.getElementById('leaveType').value;
    const field = document.getElementById('documentField');
    const input = document.getElementById('documentInput');
    if (type === 'sick') {
        field.style.display = 'block';
        input.required = true;
    } else {
        field.style.display = 'none';
        input.required = false;
    }
}
document.addEventListener('DOMContentLoaded', toggleDocumentField);
</script>
@endpush
@endsection


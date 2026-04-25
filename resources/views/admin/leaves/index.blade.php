@extends('layouts.app')
@section('title', 'Leave Approvals — Heartstrings')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Leave <em>Approvals</em></h1>
    </div>

    @if(session('success'))
    <div class="flash flash-success fade-in" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif

    <div class="glass-strong panel fade-in delay-1" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:900px;">
                <thead><tr>
                    <th style="padding-left:24px;">Employee</th>
                    <th>Type</th>
                    <th>Period</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Doc</th>
                    <th>Status</th>
                    <th style="padding-right:24px;text-align:center;width:160px;">Actions</th>
                </tr></thead>
                <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td style="padding-left:24px;font-weight:600;">{{ $leave->user->name }}</td>
                    <td style="text-transform:capitalize;">{{ $leave->type }}</td>
                    <td>{{ $leave->start_date->format('d M') }} – {{ $leave->end_date->format('d M Y') }}</td>
                    <td>{{ $leave->duration() }} days</td>
                    <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                    <td>
                        @if($leave->document_path)
                            <a href="{{ asset('storage/' . $leave->document_path) }}" target="_blank" style="color:#BE0822;font-size:0.78rem;font-weight:500;text-decoration:none;">View</a>
                        @else
                            <span style="color:rgba(107,34,50,0.40);font-size:0.78rem;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $leave->status === 'approved' ? 'badge-success' : ($leave->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    <td style="padding-right:24px;text-align:center;">
                        @if($leave->status === 'pending')
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <form method="POST" action="{{ route('admin.leaves.approve', $leave) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-outline" style="padding:6px 14px;font-size:0.78rem;border-radius:8px;color:#15803d;border-color:rgba(22,163,74,0.35);white-space:nowrap;">Approve</button>
                            </form>
                            <button type="button" class="btn-outline" style="padding:6px 14px;font-size:0.78rem;border-radius:8px;color:#BE0822;border-color:rgba(190,8,34,0.30);white-space:nowrap;" onclick="openRejectModal({{ $leave->id }}, '{{ $leave->user->name }}')">Reject</button>
                        </div>
                        @else
                            @if($leave->admin_note)
                                <span style="font-size:0.75rem;color:rgba(107,34,50,0.55);cursor:help;" title="{{ $leave->admin_note }}">📝 Note</span>
                            @else
                                <span style="font-size:0.75rem;color:rgba(107,34,50,0.40);">—</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No leave requests yet</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" style="display:none;position:fixed;inset:0;z-index:2000;align-items:center;justify-content:center;background:rgba(0,0,0,0.35);backdrop-filter:blur(4px);">
    <div class="glass-strong panel" style="background:rgba(255,249,245,0.95);max-width:420px;width:90%;padding:28px;position:relative;">
        <button type="button" onclick="closeRejectModal()" style="position:absolute;top:14px;right:16px;background:none;border:none;cursor:pointer;color:rgba(107,34,50,0.50);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:600;color:#3d1a22;margin:0 0 6px;">Reject Leave</h3>
        <p style="font-size:0.85rem;color:rgba(107,34,50,0.60);margin:0 0 20px;">Employee: <strong id="modalEmployeeName" style="color:#3d1a22;"></strong></p>
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Rejection Reason</label>
                <textarea name="admin_note" class="input-glass" rows="3" placeholder="Provide a reason for rejection..." required style="resize:vertical;"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeRejectModal()" class="btn-outline" style="padding:10px 20px;font-size:0.85rem;border-radius:10px;">Cancel</button>
                <button type="submit" class="btn-primary" style="padding:10px 24px;font-size:0.85rem;border-radius:10px;background:linear-gradient(135deg,#BE0822,#E86975);">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal(leaveId, employeeName) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const nameEl = document.getElementById('modalEmployeeName');

    form.action = '{{ url("admin/leaves") }}/' + leaveId + '/reject';
    nameEl.textContent = employeeName;
    modal.style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeRejectModal();
});
</script>
@endpush
@endsection


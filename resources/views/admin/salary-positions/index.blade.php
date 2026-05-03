@extends('layouts.app')
@section('title', 'Salary Positions — Heartstrings')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Salary <em>Positions</em></h1>
    </div>

    @if(session('success'))
    <div class="flash flash-success fade-in" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif

    <div class="fade-in delay-1" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <span style="font-size:0.85rem;color:rgba(107,34,50,0.60);">{{ $positions->count() }} positions</span>
        <a href="{{ route('admin.salary-positions.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;border-radius:12px;padding:10px 20px;font-size:0.85rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Position
        </a>
    </div>

    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:600px;">
                <thead><tr>
                    <th style="padding-left:24px;">No</th>
                    <th>Position Name</th>
                    <th>Department</th>
                    <th>Base Salary</th>
                    <th style="padding-right:24px;text-align:right;">Actions</th>
                </tr></thead>
                <tbody>
                @forelse($positions as $i => $pos)
                <tr>
                    <td style="padding-left:24px;">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $pos->position_name }}</td>
                    <td>{{ $pos->department ?? '—' }}</td>
                    <td style="font-weight:700;color:#3d1a22;">@rupiah($pos->base_salary)</td>
                    <td style="padding-right:24px;text-align:right;">
                        <a href="{{ route('admin.salary-positions.edit', $pos) }}" class="btn-outline" style="text-decoration:none;padding:6px 14px;font-size:0.78rem;border-radius:8px;margin-right:6px;">Edit</a>
                        <form method="POST" action="{{ route('admin.salary-positions.destroy', $pos) }}" style="display:inline;" onsubmit="return confirm('Delete this position?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-outline" style="padding:6px 14px;font-size:0.78rem;border-radius:8px;color:#BE0822;border-color:rgba(190,8,34,0.35);">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No salary positions found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
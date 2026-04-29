@extends('layouts.app')
@section('title', 'Add Salary Position — Heartstrings')

@section('content')
<div style="max-width:600px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Add <em>Position</em></h1>
    </div>

    <div class="glass-strong panel fade-in delay-1">
        <form method="POST" action="{{ route('admin.salary-positions.store') }}">
            @csrf

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:0.82rem;font-weight:600;color:#3d1a22;margin-bottom:6px;">Position Name</label>
                <input type="text" name="position_name" value="{{ old('position_name') }}" class="input-glass" placeholder="e.g. Software Engineer" required>
                @error('position_name')<p style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:0.82rem;font-weight:600;color:#3d1a22;margin-bottom:6px;">Department</label>
                <input type="text" name="department" value="{{ old('department') }}" class="input-glass" placeholder="e.g. Engineering">
                @error('department')<p style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:600;color:#3d1a22;margin-bottom:6px;">Base Salary (Rp)</label>
                    <input type="number" name="base_salary" value="{{ old('base_salary', 0) }}" class="input-glass" placeholder="5000000" min="0" required>
                    @error('base_salary')<p style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;font-size:0.82rem;font-weight:600;color:#3d1a22;margin-bottom:6px;">Allowance (Rp)</label>
                    <input type="number" name="allowance" value="{{ old('allowance', 0) }}" class="input-glass" placeholder="1000000" min="0" required>
                    @error('allowance')<p style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <a href="{{ route('admin.salary-positions.index') }}" class="btn-outline" style="text-decoration:none;">Cancel</a>
                <button type="submit" class="btn-primary">Save Position</button>
            </div>
        </form>
    </div>
</div>
@endsection


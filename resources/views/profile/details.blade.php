{{-- resources/views/profile/details.blade.php --}}
@extends('layouts.app')
@section('title', 'Personal Details — Heartstrings')
@section('content')
<div style="max-width:480px;margin:0 auto;padding:24px 20px 48px;">
    <a href="{{ route('profile.show') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Settings
    </a>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:600;color:#3d1a22;margin:0 0 24px;">Personal <em>Details</em></h2>

    @if(auth()->user()->isUser())
    <div class="glass-strong panel" style="background:rgba(255,249,245,0.65);text-align:center;padding:40px 24px;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#BE0822" stroke-width="1.5" style="margin-bottom:12px;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <div style="font-size:1rem;font-weight:600;color:#3d1a22;margin-bottom:6px;">Admin Only</div>
        <div style="font-size:0.85rem;color:rgba(107,34,50,0.60);">Personal details can only be edited by an administrator.</div>
    </div>
    @else
    <div class="glass-strong panel" style="background:rgba(255,249,245,0.65);">
        @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="flash flash-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('profile.details.update') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-glass" placeholder="Your full name" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Email Address</label>
                <input type="email" value="{{ $user->email }}" class="input-glass" disabled style="opacity:0.60;cursor:not-allowed;">
                <p style="font-size:0.72rem;color:rgba(107,34,50,0.45);margin:5px 0 0;">Email cannot be changed here</p>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Department</label>
                <input type="text" name="department" value="{{ old('department', $user->department) }}" class="input-glass" placeholder="e.g., Engineering, Marketing">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;border-radius:14px;">Save Details</button>
        </form>
    </div>
    @endif
</div>
@endsection

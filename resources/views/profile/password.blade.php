{{-- resources/views/profile/password.blade.php --}}
@extends('layouts.app')
@section('title', 'Change Password — Heartstrings')
@section('content')
<div style="max-width:480px;margin:0 auto;padding:24px 20px 48px;">
    <a href="{{ route('profile.show') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Settings
    </a>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:600;color:#3d1a22;margin:0 0 24px;">Change <em>Password</em></h2>

    <div class="glass-strong panel" style="background:rgba(239,170,176,0.30);">
        @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="flash flash-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Current Password</label>
                <input type="password" name="current_password" class="input-glass" placeholder="••••••••" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">New Password</label>
                <input type="password" name="password" class="input-glass" placeholder="Min. 8 characters" required>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="input-glass" placeholder="Repeat new password" required>
            </div>
            <button type="submit" class="btn-primary" style="width:100%;border-radius:14px;">Update Password</button>
        </form>
    </div>
</div>
@endsection

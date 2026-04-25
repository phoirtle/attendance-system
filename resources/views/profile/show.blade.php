@extends('layouts.app')
@section('title', 'Profile — Heartstrings')

@section('content')
<div style="max-width:820px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Account</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Profile <em>Settings</em></h1>
    </div>

    {{-- Profile card --}}
    <div class="glass-strong panel fade-in delay-1" style="background:rgba(255,255,255,0.45);margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:20px;">
            <div style="position:relative;">
                @if($user->photo_path)
                    <img src="{{ asset('storage/' . $user->photo_path) }}" alt="{{ $user->name }}"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(190,8,34,0.25);">
                @else
                    <div style="width:80px;height:80px;border-radius:50%;background:rgba(190,8,34,0.10);border:3px solid rgba(190,8,34,0.20);display:flex;align-items:center;justify-content:center;">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(107,34,50,0.60)" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                @endif
                <a href="{{ route('profile.photo') }}" style="position:absolute;bottom:0;right:0;width:22px;height:22px;background:#BE0822;border-radius:50%;border:2px solid white;display:flex;align-items:center;justify-content:center;text-decoration:none;cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform=''">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                </a>
            </div>
            <div>
                <div style="font-size:1.3rem;font-weight:700;color:#3d1a22;">{{ $user->name }}</div>
                <div style="font-size:0.85rem;color:rgba(107,34,50,0.65);">{{ $user->email }}</div>
                <div style="margin-top:6px;display:flex;gap:8px;align-items:center;">
                    <span style="font-size:0.72rem;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;background:rgba(190,8,34,0.10);color:#BE0822;padding:3px 10px;border-radius:20px;">{{ ucfirst($user->role) }}</span>
                    @if($user->department)
                    <span style="font-size:0.78rem;color:rgba(107,34,50,0.60);">{{ $user->department }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Settings hub --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        @foreach([
            ['Update Profile Photo', 'profile.photo',    '#EED7C8', 'M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z', 'Upload a new profile picture'],
            ['Change Password',      'profile.password', '#EFAAB0', 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',                       'Update your security password'],
            ['Personal Details',     'profile.details',  '#FFF9F5', 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z', 'Edit your name & department'],
            ['Account Activity',     'profile.activity', '#FD9898', 'M12 20V10M18 20V4M6 20v-4',                                         'View recent attendance logs'],
        ] as [$title, $route, $bg, $icon, $desc])
        @php
            $isPersonalDetails = $route === 'profile.details';
            $hideFromUser = $isPersonalDetails && auth()->user()->isUser();
        @endphp
        @if(!$hideFromUser)
        <a href="{{ route($route) }}"
           class="glass-strong panel fade-in delay-2"
           style="text-decoration:none;background:{{ $bg }}44;display:flex;align-items:center;gap:16px;padding:20px;transition:transform 0.2s,box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 16px 40px rgba(190,8,34,0.18)';"
           onmouseout="this.style.transform='';this.style.boxShadow='';">
            <div style="width:46px;height:46px;background:linear-gradient(135deg,#BE0822,#E86975);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 14px rgba(190,8,34,0.30);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="{{ $icon }}"/></svg>
            </div>
            <div>
                <div style="font-size:0.92rem;font-weight:600;color:#3d1a22;">{{ $title }}</div>
                <div style="font-size:0.75rem;color:rgba(107,34,50,0.55);margin-top:2px;">{{ $desc }}</div>
            </div>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(107,34,50,0.35)" stroke-width="2" style="margin-left:auto;flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        @endif
        @endforeach
    </div>

    @if(session('error'))
    <div class="flash flash-error" style="margin-top:16px;">{{ session('error') }}</div>
    @endif

</div>
@endsection

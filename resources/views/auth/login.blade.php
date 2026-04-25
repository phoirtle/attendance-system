@extends('layouts.app')
@section('title', 'Login — Heartstrings')

@section('content')
<style>
    .page-wrapper { padding-top: 0 !important; }

    /* ── Split-screen layout ──────────────────────────────────── */
    .login-wrapper {
        display: flex;
        width: 100vw;
        min-height: 100vh;
    }

    .login-left {
        flex: 1.1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        text-align: center;
    }

    .login-right {
        flex: 0.9;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 40px 60px 40px 20px;
    }

    /* Responsive: stack on mobile */
    @media (max-width: 768px) {
        .login-wrapper { flex-direction: column; }
        .login-left { min-height: 30vh; padding: 32px 20px; }
        .login-right { padding: 24px 20px 48px; justify-content: center; }
    }

    /* Role buttons */
    .role-btn.selected {
        background: linear-gradient(135deg, #BE0822, #E86975) !important;
        color: white !important;
        border-color: transparent !important;
        box-shadow: 0 4px 16px rgba(190,8,34,0.30);
    }
</style>

<div class="login-wrapper">

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{--  LEFT: Branding                                            --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div class="login-left fade-in">
        <div style="width:110px;height:110px;background:linear-gradient(135deg,#BE0822,#E86975);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:32px;box-shadow:0 8px 32px rgba(190,8,34,0.25);">
            <svg width="52" height="52" viewBox="0 0 24 24" fill="white"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.27 2 8.5 2 5.41 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.41 22 8.5c0 3.77-3.4 6.86-8.55 11.53L12 21.35z"/></svg>
        </div>
        <h1 style="font-family:'Playfair Display',serif;font-size:3.2rem;font-weight:600;color:#3d1a22;letter-spacing:-0.02em;margin:0 0 10px;text-align:center;">heartstrings</h1>
        <p style="color:rgba(107,34,50,0.60);font-size:1.1rem;margin:0;font-weight:500;text-align:center;letter-spacing:0.02em;">Staff Attendance System</p>
    </div>

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{--  RIGHT: Login Card                                         --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div class="login-right">
        <div class="fade-in delay-1" style="width:100%;max-width:380px;">

            {{-- Glass card --}}
            <div class="glass-strong panel" style="padding:32px;">

                <h2 style="font-size:1.5rem;font-weight:700;color:#3d1a22;margin:0 0 6px;text-align:center;">Welcome back</h2>
                <p style="color:rgba(107,34,50,0.55);font-size:0.875rem;margin:0 0 24px;text-align:center;">Sign in to your account</p>

                @if($errors->any())
                <div class="flash flash-error fade-in" style="margin-bottom:16px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="display:inline;margin-right:6px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    {{-- Email --}}
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:#6b2232;letter-spacing:0.04em;text-transform:uppercase;margin-bottom:6px;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="input-glass" placeholder="you@company.com" required autofocus>
                    </div>

                    {{-- Password --}}
                    <div style="margin-bottom:14px;position:relative;">
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:#6b2232;letter-spacing:0.04em;text-transform:uppercase;margin-bottom:6px;">Password</label>
                        <input type="password" name="password" id="passwordInput"
                               class="input-glass" placeholder="••••••••" required
                               style="padding-right:46px;">
                        <button type="button" onclick="togglePassword()"
                                style="position:absolute;right:14px;bottom:13px;background:none;border:none;cursor:pointer;color:rgba(107,34,50,0.50);padding:0;">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>

{{-- Role selector --}}
                    <div style="margin-bottom:16px;">
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:#6b2232;letter-spacing:0.04em;text-transform:uppercase;margin-bottom:8px;">Sign in as</label>
                        <div style="display:flex;gap:10px;">
                            <label style="flex:1;cursor:pointer;">
                                <input type="radio" name="role" value="user"
                                       {{ old('role', 'user') === 'user' ? 'checked' : '' }}
                                       style="display:none;" class="role-radio">
                                <div class="role-btn" data-role="user"
                                     style="text-align:center;padding:11px;border-radius:12px;border:1.5px solid rgba(190,8,34,0.25);background:rgba(255,255,255,0.45);font-size:0.875rem;font-weight:500;color:#6b2232;transition:all 0.2s;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;margin-right:5px;vertical-align:-3px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    User
                                </div>
                            </label>
                            <label style="flex:1;cursor:pointer;">
                                <input type="radio" name="role" value="admin"
                                       {{ old('role') === 'admin' ? 'checked' : '' }}
                                       style="display:none;" class="role-radio">
                                <div class="role-btn" data-role="admin"
                                     style="text-align:center;padding:11px;border-radius:12px;border:1.5px solid rgba(190,8,34,0.25);background:rgba(255,255,255,0.45);font-size:0.875rem;font-weight:500;color:#6b2232;transition:all 0.2s;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;margin-right:5px;vertical-align:-3px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    Admin
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-primary" style="width:100%;border-radius:14px;">
                        Sign In
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;margin-left:8px;vertical-align:-3px;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>

                </form>
            </div>

            {{-- Footer --}}
            <p style="text-align:center;margin-top:20px;font-size:0.78rem;color:rgba(107,34,50,0.45);">
                © 2026 Heartstrings. All rights reserved.
            </p>

        </div>
</div>

<script>
    function togglePassword() {
        const inp = document.getElementById('passwordInput');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }

    // Role toggle styling
    function updateRoleBtns() {
        document.querySelectorAll('.role-radio').forEach(radio => {
            const btn = radio.nextElementSibling;
            btn.classList.toggle('selected', radio.checked);
        });
    }
    document.querySelectorAll('.role-radio').forEach(r => r.addEventListener('change', updateRoleBtns));
    updateRoleBtns();
</script>
@endsection

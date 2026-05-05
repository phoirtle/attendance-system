@php
    $lastSeen = auth()->user()->admin_leaves_last_seen;
    $pendingLeaveCount = \App\Models\Leave::pending()
        ->when($lastSeen, fn ($query) => $query->where('created_at', '>', $lastSeen))
        ->count();
@endphp

<nav class="navbar">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 mr-2" style="text-decoration:none;">
        <div style="width:30px;height:30px;background:linear-gradient(135deg,#BE0822,#E86975);border-radius:50%;display:flex;align-items:center;justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.27 2 8.5 2 5.41 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.41 22 8.5c0 3.77-3.4 6.86-8.55 11.53L12 21.35z"/></svg>
        </div>
        <span style="font-family:'Playfair Display',serif;font-weight:600;font-size:0.95rem;color:#BE0822;letter-spacing:-0.01em;">heartstrings</span>
    </a>

    <div class="nav-divider"></div>

    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Dashboard
    </a>

    <a href="{{ route('admin.recap') }}" class="nav-link {{ request()->routeIs('admin.recap*') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Admin Portal
    </a>

    <a href="{{ route('admin.salary-positions.index') }}" class="nav-link {{ request()->routeIs('admin.salary-positions*') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Salary
    </a>

    <a href="{{ route('admin.payrolls.index') }}" class="nav-link {{ request()->routeIs('admin.payrolls*') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        Payrolls
    </a>

    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Staff
    </a>

    <a href="{{ route('admin.leaves') }}" class="nav-link {{ request()->routeIs('admin.leaves') ? 'active' : '' }}">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Leaves
        @if($pendingLeaveCount > 0)
            <span class="nav-badge nav-badge-red">{{ $pendingLeaveCount > 99 ? '99+' : $pendingLeaveCount }}</span>
        @endif
    </a>

    <div class="nav-divider"></div>

    <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        @if(auth()->user()->photo_path)
            <img src="{{ asset('storage/' . auth()->user()->photo_path) }}" alt="">
        @else
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        @endif
        <span class="hidden sm:inline">{{ Str::words(auth()->user()->name, 1, '') }}</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;font-family:inherit;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </button>
    </form>
</nav>

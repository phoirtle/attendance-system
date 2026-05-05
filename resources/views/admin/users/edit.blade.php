@extends('layouts.app')
@section('title', 'Edit Employee — Heartstrings')

@section('content')
<div style="max-width:520px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <a href="{{ route('admin.users.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Employees
        </a>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Edit <em>Employee</em></h1>
    </div>

    <div class="glass-strong panel fade-in delay-1">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-glass" required>
                @error('name')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-glass" required>
                @error('email')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Department</label>
                <input type="text" name="department" value="{{ old('department', $user->department) }}" class="input-glass">
                @error('department')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            {{-- ─── DATA PRIBADI ─────────────────────────── --}}
            <div style="margin:24px 0 16px;padding-top:20px;border-top:1px solid rgba(190,8,34,0.1);">
                <p style="font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:16px;">Data Pribadi</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">No. HP / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input-glass" placeholder="08xxxxxxxxxx">
                        @error('phone')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" class="input-glass">
                        @error('birth_date')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Jenis Kelamin</label>
                    <select name="gender" class="input-glass">
                        <option value="">-- Pilih --</option>
                        <option value="male"   {{ old('gender', $user->gender) === 'male'   ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Alamat</label>
                    <textarea name="address" class="input-glass" rows="2" placeholder="Jl. Contoh No. 1, Kota">{{ old('address', $user->address) }}</textarea>
                    @error('address')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ─── DATA KEPEGAWAIAN ───────────────────────── --}}
            <div style="margin-bottom:24px;">
                <p style="font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:16px;">Data Kepegawaian</p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">NIK Karyawan</label>
                        <input type="text" name="employee_id_number" value="{{ old('employee_id_number', $user->employee_id_number) }}" class="input-glass" placeholder="EMP-001">
                        @error('employee_id_number')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Tanggal Mulai Kerja</label>
                        <input type="date" name="join_date" value="{{ old('join_date', $user->join_date?->format('Y-m-d')) }}" class="input-glass">
                        @error('join_date')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Status Kepegawaian</label>
                    <select name="employment_status" class="input-glass">
                        <option value="">-- Pilih --</option>
                        <option value="permanent" {{ old('employment_status', $user->employment_status) === 'permanent' ? 'selected' : '' }}>Tetap</option>
                        <option value="contract"  {{ old('employment_status', $user->employment_status) === 'contract'  ? 'selected' : '' }}>Kontrak</option>
                        <option value="intern"    {{ old('employment_status', $user->employment_status) === 'intern'    ? 'selected' : '' }}>Magang</option>
                    </select>
                    @error('employment_status')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">New Password <span style="text-transform:none;font-weight:400;color:rgba(107,34,50,0.50);">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="input-glass" placeholder="Min. 6 characters">
                @error('password')<div style="font-size:0.78rem;color:#BE0822;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-primary" style="width:100%;border-radius:12px;">Update Employee</button>
        </form>
    </div>
</div>
@endsection


@extends('layouts.app')
@section('title', 'Manage Employees — Heartstrings')

@section('content')
<div style="max-width:980px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Manage <em>Employees</em></h1>
    </div>

    @if(session('success'))
    <div class="flash flash-success fade-in" style="margin-bottom:16px;">{{ session('success') }}</div>
    @endif

    <div class="fade-in delay-1" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <span style="font-size:0.85rem;color:rgba(107,34,50,0.60);">{{ $users->count() }} employees</span>
        <a href="{{ route('admin.users.create') }}" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;border-radius:12px;padding:10px 20px;font-size:0.85rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Employee
        </a>
    </div>

    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:600px;">
                <thead><tr>
                    <th style="padding-left:24px;">Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th style="padding-right:24px;text-align:right;">Actions</th>
                </tr></thead>
                <tbody>
                @forelse($users as $u)
                <tr
                    class="staff-row"
                    data-id="{{ $u->id }}"
                    style="cursor:pointer;"
                    title="Click to view details"
                >
                    <td style="padding-left:24px;font-weight:600;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($u->photo_path)
                                <img src="{{ Storage::url($u->photo_path) }}" alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:1.5px solid rgba(190,8,34,0.2);">
                            @else
                                <div style="width:32px;height:32px;border-radius:50%;background:rgba(190,8,34,0.10);display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;color:#BE0822;flex-shrink:0;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            {{ $u->name }}
                        </div>
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->department ?? '—' }}</td>
                    <td style="padding-right:24px;text-align:right;" onclick="event.stopPropagation();">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn-outline" style="text-decoration:none;padding:6px 14px;font-size:0.78rem;border-radius:8px;margin-right:6px;">Edit</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline;" onsubmit="return confirm('Delete this employee?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-outline" style="padding:6px 14px;font-size:0.78rem;border-radius:8px;color:#BE0822;border-color:rgba(190,8,34,0.35);">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No employees found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     SLIDE-IN DETAIL PANEL (dari kanan)
════════════════════════════════════════════ --}}

{{-- Overlay --}}
<div id="staffOverlay" onclick="closePanel()" style="
    display:none;
    position:fixed;inset:0;
    background:rgba(61,26,34,0.35);
    backdrop-filter:blur(2px);
    z-index:900;
    transition:opacity 0.25s;
"></div>

{{-- Panel --}}
<div id="staffPanel" style="
    position:fixed;top:0;right:0;bottom:0;
    width:min(420px, 100vw);
    background:#fff9f5;
    box-shadow:-8px 0 40px rgba(61,26,34,0.18);
    z-index:901;
    transform:translateX(100%);
    transition:transform 0.3s cubic-bezier(.4,0,.2,1);
    display:flex;flex-direction:column;
    overflow:hidden;
">
    {{-- Panel Header --}}
    <div style="padding:20px 24px 16px;border-bottom:1px solid rgba(190,8,34,0.10);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
        <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.50);margin:0;">Employee Detail</p>
        <button onclick="closePanel()" style="background:none;border:none;cursor:pointer;padding:4px;color:rgba(107,34,50,0.50);line-height:1;" title="Close">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    {{-- Panel Body (scrollable) --}}
    <div id="staffPanelBody" style="flex:1;overflow-y:auto;padding:24px;">
        <div id="staffPanelLoading" style="text-align:center;padding:48px 0;color:rgba(107,34,50,0.40);">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="animation:spin 1s linear infinite;display:block;margin:0 auto 12px;"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            Loading...
        </div>
        <div id="staffPanelContent" style="display:none;"></div>
    </div>

    {{-- Panel Footer --}}
    <div id="staffPanelFooter" style="padding:16px 24px;border-top:1px solid rgba(190,8,34,0.10);flex-shrink:0;display:none;">
        <a id="staffEditLink" href="#" class="btn-primary" style="display:block;text-align:center;text-decoration:none;border-radius:12px;padding:10px;font-size:0.85rem;">
            Edit Employee
        </a>
    </div>
</div>

{{-- Embed data JSON dari semua users (sudah diproses di controller) --}}
<script>
const STAFF_DATA = @json($staffData);
</script>

@push('scripts')
<style>
@keyframes spin { to { transform: rotate(360deg); } }

.staff-row:hover { background: rgba(190,8,34,0.04) !important; }
.staff-row.active { background: rgba(190,8,34,0.07) !important; }

.detail-section-title {
    font-size:0.70rem;
    font-weight:700;
    letter-spacing:0.08em;
    text-transform:uppercase;
    color:rgba(107,34,50,0.45);
    margin:20px 0 10px;
}
.detail-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:7px 0;
    border-bottom:1px solid rgba(190,8,34,0.07);
    font-size:0.84rem;
}
.detail-row:last-child { border-bottom:none; }
.detail-label { color:rgba(107,34,50,0.55); }
.detail-value { font-weight:600; color:#3d1a22; text-align:right; max-width:60%; }

.leave-badge {
    display:inline-block;
    padding:2px 8px;
    border-radius:20px;
    font-size:0.70rem;
    font-weight:700;
    letter-spacing:0.03em;
    text-transform:capitalize;
}
.leave-badge.approved  { background:rgba(34,139,34,0.12); color:#166534; }
.leave-badge.pending   { background:rgba(234,179,8,0.15);  color:#92400e; }
.leave-badge.rejected  { background:rgba(190,8,34,0.10);   color:#BE0822; }

.stat-box {
    flex:1;
    text-align:center;
    padding:10px 6px;
    background:rgba(190,8,34,0.05);
    border-radius:10px;
}
.stat-box .stat-num { font-size:1.3rem; font-weight:700; color:#3d1a22; }
.stat-box .stat-lbl { font-size:0.65rem; color:rgba(107,34,50,0.50); text-transform:uppercase; letter-spacing:0.05em; margin-top:2px; }

.status-badge {
    display:inline-block;
    padding:2px 10px;
    border-radius:20px;
    font-size:0.72rem;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:0.04em;
}
</style>

<script>
let activeRow = null;

// Klik baris → buka panel
document.querySelectorAll('.staff-row').forEach(row => {
    row.addEventListener('click', function () {
        const id = parseInt(this.dataset.id);
        openPanel(id, this);
    });
});

function openPanel(id, row) {
    // Highlight baris aktif
    if (activeRow) activeRow.classList.remove('active');
    activeRow = row;
    row.classList.add('active');

    // Tampilkan overlay + panel
    const overlay = document.getElementById('staffOverlay');
    const panel   = document.getElementById('staffPanel');
    overlay.style.display = 'block';
    panel.style.transform = 'translateX(0)';

    // Render konten
    renderPanel(id);
}

function closePanel() {
    document.getElementById('staffOverlay').style.display = 'none';
    document.getElementById('staffPanel').style.transform = 'translateX(100%)';
    document.getElementById('staffPanelFooter').style.display = 'none';
    if (activeRow) { activeRow.classList.remove('active'); activeRow = null; }
}

// Tutup dengan ESC
document.addEventListener('keydown', e => { if (e.key === 'Escape') closePanel(); });

function formatRupiah(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function calcAge(birthStr) {
    if (!birthStr) return null;
    const b = new Date(birthStr);
    if (isNaN(b)) return null;
    const diff = Date.now() - b.getTime();
    return Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
}

function renderPanel(id) {
    const loading  = document.getElementById('staffPanelLoading');
    const content  = document.getElementById('staffPanelContent');
    const footer   = document.getElementById('staffPanelFooter');
    const editLink = document.getElementById('staffEditLink');

    loading.style.display = 'block';
    content.style.display = 'none';
    footer.style.display  = 'none';

    const d = STAFF_DATA[id];
    if (!d) { loading.innerHTML = '<p style="color:#BE0822;">Data tidak ditemukan.</p>'; return; }

    // Avatar
    const avatar = d.photo_url
        ? `<img src="${d.photo_url}" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid rgba(190,8,34,0.25);">`
        : `<div style="width:64px;height:64px;border-radius:50%;background:rgba(190,8,34,0.10);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;color:#BE0822;">${d.initials}</div>`;

    // Label helpers
    const leaveTypeLabel = { annual: 'Annual', sick: 'Sick', permission: 'Permission' };
    const genderLabel    = { male: 'Laki-laki', female: 'Perempuan' };
    const statusLabel    = { permanent: 'Karyawan Tetap', contract: 'Kontrak', intern: 'Magang' };
    const statusColor    = { permanent: '#166534', contract: '#92400e', intern: '#1d4ed8' };

    // Hitung umur
    const age = calcAge(d.birth_date);

    // Baris riwayat cuti
    const leaveRows = d.recent_leaves.length
        ? d.recent_leaves.map(l => `
            <div class="detail-row">
                <span class="detail-label">${leaveTypeLabel[l.type] ?? l.type} · ${l.start_date}${l.duration > 1 ? ' – ' + l.end_date : ''} <span style="color:#3d1a22;font-weight:500;">(${l.duration}d)</span></span>
                <span class="leave-badge ${l.status}">${l.status}</span>
            </div>`).join('')
        : `<p style="font-size:0.82rem;color:rgba(107,34,50,0.40);margin:8px 0;">Belum ada riwayat cuti.</p>`;

    // Attendance stats bulan ini
    const att = d.attendance_this_month;

    // Status badge HTML
    const statusBadge = d.employment_status
        ? `<span class="status-badge" style="background:${statusColor[d.employment_status]}18;color:${statusColor[d.employment_status]};">
               ${statusLabel[d.employment_status] ?? d.employment_status}
           </span>`
        : '—';

    // No HP dengan link WA
    const phoneHtml = d.phone
        ? `<a href="https://wa.me/${d.phone.replace(/\D/g,'')}" target="_blank" style="color:#BE0822;text-decoration:none;">${d.phone}</a>`
        : '—';

    content.innerHTML = `
        {{-- Profil --}}
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:4px;">
            ${avatar}
            <div>
                <div style="font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:600;color:#3d1a22;line-height:1.2;">${d.name}</div>
                <div style="font-size:0.80rem;color:rgba(107,34,50,0.55);margin-top:3px;">${d.email}</div>
                <div style="font-size:0.75rem;font-weight:600;color:rgba(107,34,50,0.45);text-transform:uppercase;letter-spacing:0.05em;margin-top:4px;">${d.department}</div>
            </div>
        </div>

        {{-- Informasi --}}
        <div class="detail-section-title">Informasi</div>
        <div class="detail-row"><span class="detail-label">Posisi / Jabatan</span><span class="detail-value">${d.position}</span></div>
        <div class="detail-row"><span class="detail-label">Gaji Pokok</span><span class="detail-value">${d.base_salary !== null ? formatRupiah(d.base_salary) : '—'}</span></div>
        <div class="detail-row">
            <span class="detail-label">Sisa Cuti Tahunan</span>
            <span class="detail-value" style="color:${d.remaining_leave <= 3 ? '#BE0822' : '#166534'};">
                ${d.remaining_leave} / 12 hari
            </span>
        </div>

        {{-- Data Pribadi --}}
        <div class="detail-section-title">Data Pribadi</div>
        <div class="detail-row">
            <span class="detail-label">No. HP / WhatsApp</span>
            <span class="detail-value">${phoneHtml}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tanggal Lahir</span>
            <span class="detail-value">${d.birth_date ? `${d.birth_date}${age ? ' <span style="color:rgba(107,34,50,0.45);font-weight:400;font-size:0.78rem;"></span>' : ''}` : '—'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Jenis Kelamin</span>
            <span class="detail-value">${d.gender ? (genderLabel[d.gender] ?? d.gender) : '—'}</span>
        </div>
        <div class="detail-row" style="align-items:flex-start;">
            <span class="detail-label">Alamat</span>
            <span class="detail-value" style="max-width:65%;word-break:break-word;text-align:right;line-height:1.5;">${d.address ?? '—'}</span>
        </div>

        {{-- Data Kepegawaian --}}
        <div class="detail-section-title">Data Kepegawaian</div>
        <div class="detail-row">
            <span class="detail-label">NIK Karyawan</span>
            <span class="detail-value" style="font-family:monospace;letter-spacing:0.05em;">${d.employee_id_number ?? '—'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tanggal Mulai Kerja</span>
            <span class="detail-value">${d.join_date ?? '—'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Masa Kerja</span>
            <span class="detail-value">${d.work_duration ?? '—'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status Karyawan</span>
            <span class="detail-value">${statusBadge}</span>
        </div>

        {{-- Kehadiran Bulan Ini --}}
        <div class="detail-section-title">Kehadiran Bulan Ini</div>
        <div style="display:flex;gap:8px;">
            <div class="stat-box"><div class="stat-num" style="color:#166534;">${att.present}</div><div class="stat-lbl">Hadir</div></div>
            <div class="stat-box"><div class="stat-num" style="color:#92400e;">${att.late}</div><div class="stat-lbl">Terlambat</div></div>
            <div class="stat-box"><div class="stat-num" style="color:#BE0822;">${att.absent}</div><div class="stat-lbl">Absen</div></div>
            <div class="stat-box"><div class="stat-num" style="color:#1d4ed8;">${att.leave}</div><div class="stat-lbl">Cuti</div></div>
        </div>

        {{-- Riwayat Cuti --}}
        <div class="detail-section-title">Riwayat Cuti Terbaru</div>
        ${leaveRows}
    `;

    editLink.href = d.edit_url;

    loading.style.display = 'none';
    content.style.display = 'block';
    footer.style.display  = 'block';
}
</script>
@endpush
@endsection
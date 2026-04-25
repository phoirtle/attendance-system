<?php $__env->startSection('title', 'Clock In — Heartstrings'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:980px;margin:0 auto;padding:24px 20px 48px;">

    
    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">
            <?php echo e(now()->format('l, d F Y')); ?>

        </p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">
            Good <?php echo e(now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening')); ?>,
            <em><?php echo e(Str::words(auth()->user()->name, 1, '')); ?></em>
        </h1>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">


        <div class="glass-strong panel fade-in delay-1" style="background:rgba(239,170,176,0.30);">
            <div style="font-size:0.85rem;font-weight:600;color:#3d1a22;margin-bottom:16px;">GPS Geolocation</div>

            
            <div id="mapContainer" style="width:100%;height:220px;border-radius:16px;overflow:hidden;position:relative;background:linear-gradient(135deg,#f5e6e8,#ead4d8);border:1.5px solid rgba(255,255,255,0.60);">
                
                <svg width="100%" height="100%" style="position:absolute;top:0;left:0;opacity:0.18;">
                    <defs>
                        <pattern id="grid" width="30" height="30" patternUnits="userSpaceOnUse">
                            <path d="M 30 0 L 0 0 0 30" fill="none" stroke="#BE0822" stroke-width="0.8"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
                
                <svg width="100%" height="100%" style="position:absolute;top:0;left:0;opacity:0.25;">
                    <line x1="0" y1="110" x2="100%" y2="110" stroke="#BE0822" stroke-width="3"/>
                    <line x1="180" y1="0" x2="180" y2="100%" stroke="#BE0822" stroke-width="3"/>
                    <line x1="0" y1="60" x2="100%" y2="60" stroke="#E86975" stroke-width="1.5"/>
                    <line x1="100" y1="0" x2="100" y2="100%" stroke="#E86975" stroke-width="1.5"/>
                    <line x1="260" y1="0" x2="260" y2="100%" stroke="#E86975" stroke-width="1.5"/>
                    <line x1="0" y1="160" x2="100%" y2="160" stroke="#E86975" stroke-width="1.5"/>
                </svg>
                
                <div id="radiusCircle" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:140px;height:140px;border-radius:50%;border:2px solid rgba(190,8,34,0.50);background:rgba(190,8,34,0.07);"></div>
                
                <div style="position:absolute;top:50%;left:calc(50% + 72px);transform:translateY(-50%);font-size:0.7rem;font-weight:600;color:#BE0822;background:rgba(255,255,255,0.85);padding:2px 8px;border-radius:20px;">100 m</div>
                
                <div id="locationPin" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-100%);">
                    <svg width="28" height="36" viewBox="0 0 28 36" fill="none">
                        <path d="M14 0C6.268 0 0 6.268 0 14c0 10.5 14 22 14 22S28 24.5 28 14C28 6.268 21.732 0 14 0z" fill="#BE0822"/>
                        <circle cx="14" cy="14" r="6" fill="white"/>
                        <circle cx="14" cy="14" r="3" fill="#BE0822"/>
                    </svg>
                </div>
                
                <div id="gpsStatus" style="position:absolute;bottom:10px;left:10px;right:10px;">
                    <div class="glass" style="padding:8px 14px;border-radius:10px;font-size:0.78rem;">
                        <span id="gpsStatusText" style="color:#6b2232;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;margin-right:5px;vertical-align:-2px;"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
                            Locating you...
                        </span>
                    </div>
                </div>
            </div>

            
            <div style="margin-top:14px;padding:12px 16px;background:rgba(255,255,255,0.40);border-radius:12px;border:1px solid rgba(255,255,255,0.60);">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:0.78rem;color:rgba(107,34,50,0.60);">Distance from Office</span>
                    <span id="distanceDisplay" style="font-size:0.85rem;font-weight:700;color:#3d1a22;">— m</span>
                </div>
                <div style="margin-top:8px;height:4px;background:rgba(190,8,34,0.12);border-radius:2px;overflow:hidden;">
                    <div id="distanceBar" style="height:100%;width:0%;background:linear-gradient(90deg,#22c55e,#BE0822);border-radius:2px;transition:width 0.5s ease;"></div>
                </div>
            </div>
        </div>

        
        <div class="glass-strong panel fade-in delay-2" style="background:rgba(232,105,117,0.22);">

            
            <?php if($attendance && $attendance->clock_in): ?>
                <div style="margin-bottom:18px;padding:14px 16px;background:rgba(22,163,74,0.12);border-radius:14px;border:1px solid rgba(22,163,74,0.25);">
                    <div style="font-size:0.75rem;font-weight:600;color:#15803d;letter-spacing:0.05em;text-transform:uppercase;margin-bottom:4px;">✓ Clocked In</div>
                    <div style="font-size:1.4rem;font-weight:700;color:#15803d;"><?php echo e(\Carbon\Carbon::parse($attendance->clock_in)->format('H:i')); ?></div>
                    <?php if($attendance->clock_out): ?>
                    <div style="font-size:0.8rem;color:#15803d;margin-top:4px;">Clocked out: <?php echo e(\Carbon\Carbon::parse($attendance->clock_out)->format('H:i')); ?></div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div style="margin-bottom:18px;padding:14px 16px;background:rgba(255,255,255,0.30);border-radius:14px;border:1px solid rgba(255,255,255,0.50);">
                    <div style="font-size:0.75rem;font-weight:600;color:rgba(107,34,50,0.55);letter-spacing:0.05em;text-transform:uppercase;margin-bottom:4px;">Today's Status</div>
                    <div style="font-size:1rem;font-weight:600;color:#6b2232;">Not yet clocked in</div>
                </div>
            <?php endif; ?>

            
            <div style="position:relative;width:100%;border-radius:16px;overflow:hidden;background:#1a0a0c;aspect-ratio:4/3;margin-bottom:14px;">
                <video id="cameraFeed" autoplay playsinline muted
                       style="width:100%;height:100%;object-fit:cover;display:none;"></video>
                <canvas id="photoCanvas" style="display:none;"></canvas>
                <div id="cameraPlaceholder" style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;color:rgba(255,255,255,0.40);">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    <span style="font-size:0.8rem;">Camera Feed Area</span>
                </div>
                <img id="capturedPhoto" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;" alt="Captured">
                
                <div id="liveIndicator" style="display:none;position:absolute;top:12px;left:12px;background:rgba(239,68,68,0.85);color:white;font-size:0.7rem;font-weight:700;letter-spacing:0.06em;padding:3px 10px;border-radius:20px;backdrop-filter:blur(6px);">
                    ● LIVE
                </div>
            </div>

            
            <div id="actionArea">
                <?php if(!$attendance || ($attendance->clock_in && !$attendance->clock_out)): ?>
                <div style="display:flex;gap:10px;">
                    <button id="startCameraBtn" onclick="startCamera()" class="btn-outline" style="flex:1;border-radius:12px;padding:11px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;margin-right:5px;vertical-align:-3px;"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        Open Camera
                    </button>
                    <button id="clockBtn" onclick="captureAndSubmit()" class="btn-primary" style="flex:2;border-radius:12px;" disabled>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:inline;margin-right:6px;vertical-align:-3px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo e($attendance && $attendance->clock_in ? 'Take Photo to Clock Out' : 'Take Photo to Clock In'); ?>

                    </button>
                </div>
                <?php else: ?>
                <div style="text-align:center;padding:16px;background:rgba(22,163,74,0.10);border-radius:14px;border:1px solid rgba(22,163,74,0.20);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="#15803d" style="display:block;margin:0 auto 8px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    <p style="color:#15803d;font-weight:600;font-size:0.9rem;margin:0;">Attendance complete for today!</p>
                </div>
                <?php endif; ?>
            </div>

            
            <div id="feedbackMsg" style="display:none;margin-top:12px;" class="flash"></div>
        </div>

    </div>

    
    <div class="glass panel fade-in delay-3" style="margin-top:20px;background:rgba(255,249,245,0.55);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
            <div>
                <h3 style="font-size:1rem;font-weight:600;color:#3d1a22;margin:0;">Recent Activity</h3>
            </div>
            <a href="<?php echo e(route('profile.activity')); ?>" style="font-size:0.8rem;color:#BE0822;font-weight:500;text-decoration:none;">View all →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr>
                    <th>Date</th><th>Clock In</th><th>Clock Out</th><th>Distance</th><th>Status</th>
                </tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = auth()->user()->attendances()->orderBy('date','desc')->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-weight:500;"><?php echo e($a->date->format('d M Y')); ?></td>
                    <td><?php echo e($a->clock_in ?? '—'); ?></td>
                    <td><?php echo e($a->clock_out ?? '—'); ?></td>
                    <td><?php echo e($a->distance_meters ? $a->distance_meters . 'm' : '—'); ?></td>
                    <td>
                        <span class="badge <?php echo e($a->status === 'present' ? 'badge-success' : ($a->status === 'late' ? 'badge-warning' : 'badge-danger')); ?>">
                            <?php echo e(ucfirst($a->status)); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" style="text-align:center;color:rgba(107,34,50,0.45);padding:24px;">No attendance records yet</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Config ───────────────────────────────────────────────────────────────
const OFFICE_LAT  = -2.985;
const OFFICE_LNG  = 104.732;
const MAX_DIST    = 100;
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

let userLat = null, userLng = null, userDist = null;
let cameraStream = null;
let cameraActive = false;

// ── Haversine (client-side preview) ──────────────────────────────────────
function haversine(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const dL = (lat2 - lat1) * Math.PI / 180;
    const dO = (lon2 - lon1) * Math.PI / 180;
    const a  = Math.sin(dL/2)**2 + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dO/2)**2;
    return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
}

// ── Geolocation ───────────────────────────────────────────────────────────
function initGPS() {
    if (!navigator.geolocation) {
        setGPSStatus('GPS not supported', false);
        return;
    }
    setGPSStatus('Locating you…', null);
    navigator.geolocation.watchPosition(onGPSSuccess, onGPSError, {
        enableHighAccuracy: true, maximumAge: 10000, timeout: 15000
    });
}

function onGPSSuccess(pos) {
    userLat  = pos.coords.latitude;
    userLng  = pos.coords.longitude;
    userDist = haversine(OFFICE_LAT, OFFICE_LNG, userLat, userLng);

    const inRange = userDist <= MAX_DIST;
    const pct     = Math.min(userDist / MAX_DIST * 100, 100);

    document.getElementById('distanceDisplay').textContent = userDist + ' m';
    document.getElementById('distanceBar').style.width     = pct + '%';
    document.getElementById('distanceBar').style.background = inRange
        ? 'linear-gradient(90deg,#22c55e,#16a34a)'
        : 'linear-gradient(90deg,#f59e0b,#BE0822)';

    setGPSStatus(
        (inRange ? '✓ In Range' : '✗ Out of Range') + ` — ${userDist}m from office`,
        inRange
    );

    updateClockBtn();
}

function onGPSError(err) {
    setGPSStatus('GPS error: ' + err.message, false);
}

function setGPSStatus(msg, inRange) {
    const el = document.getElementById('gpsStatusText');
    el.textContent = msg;
    el.style.color = inRange === true ? '#15803d' : inRange === false ? '#BE0822' : '#6b2232';
}

// ── Camera ────────────────────────────────────────────────────────────────
async function startCamera() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
        const video  = document.getElementById('cameraFeed');
        video.srcObject = cameraStream;
        video.style.display = 'block';
        document.getElementById('cameraPlaceholder').style.display = 'none';
        document.getElementById('capturedPhoto').style.display     = 'none';
        document.getElementById('liveIndicator').style.display     = 'flex';
        document.getElementById('startCameraBtn').textContent      = '⟳ Retake';
        cameraActive = true;
        updateClockBtn();
    } catch (e) {
        showFeedback('Camera access denied: ' + e.message, false);
    }
}

function updateClockBtn() {
    const btn = document.getElementById('clockBtn');
    if (!btn) return;
    btn.disabled = !(cameraActive && userLat !== null);
}

// ── Capture & submit ──────────────────────────────────────────────────────
async function captureAndSubmit() {
    const video  = document.getElementById('cameraFeed');
    const canvas = document.getElementById('photoCanvas');
    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const photoData = canvas.toDataURL('image/jpeg', 0.85);

    // Show captured photo
    const img = document.getElementById('capturedPhoto');
    img.src   = photoData;
    img.style.display = 'block';
    video.style.display = 'none';
    document.getElementById('liveIndicator').style.display = 'none';

    // Stop camera
    if (cameraStream) cameraStream.getTracks().forEach(t => t.stop());
    cameraActive = false;

    const btn = document.getElementById('clockBtn');
    btn.disabled  = true;
    btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;margin-right:6px;vertical-align:-3px;animation:spin 1s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Processing…';

    try {
        const res  = await fetch('<?php echo e(route("attendance.store")); ?>', {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body   : JSON.stringify({ latitude: userLat, longitude: userLng, photo: photoData })
        });
        const data = await res.json();

        if (data.success) {
            showFeedback('✓ ' + data.message, true);
            setTimeout(() => location.reload(), 1800);
        } else {
            showFeedback('✗ ' + data.message, false);
            btn.disabled = false;
            btn.innerHTML = 'Retry';
        }
    } catch (e) {
        showFeedback('Network error. Please try again.', false);
        btn.disabled = false;
    }
}

function showFeedback(msg, success) {
    const el = document.getElementById('feedbackMsg');
    el.className   = 'flash ' + (success ? 'flash-success' : 'flash-error');
    el.textContent = msg;
    el.style.display = 'block';
}

// ── Init ──────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', initGPS);
</script>
<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Windows\Downloads\attendance-system\resources\views/attendance/index.blade.php ENDPATH**/ ?>
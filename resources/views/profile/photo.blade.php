{{-- resources/views/profile/photo.blade.php --}}
@extends('layouts.app')
@section('title', 'Update Photo — Heartstrings')
@section('content')
<div style="max-width:500px;margin:0 auto;padding:24px 20px 48px;">
    <a href="{{ route('profile.show') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Settings
    </a>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:600;color:#3d1a22;margin:0 0 24px;letter-spacing:-0.02em;">Update Profile <em>Photo</em></h2>

    <div class="glass-strong panel" style="background:rgba(238,215,200,0.40);">
        @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
        @endif

        <div style="text-align:center;margin-bottom:20px;">
            <div style="position:relative;display:inline-block;">
                <div id="previewContainer" style="width:120px;height:120px;border-radius:50%;overflow:hidden;border:3px solid rgba(190,8,34,0.25);background:rgba(255,255,255,0.40);display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="document.getElementById('cameraInput').click()">
                    @if($user->photo_path)
                        <img id="photoPreview" src="{{ asset('storage/'.$user->photo_path) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <img id="photoPreview" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
                        <svg id="previewIcon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(107,34,50,0.40)" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    @endif
                </div>
            </div>
            <p style="font-size:0.78rem;color:rgba(107,34,50,0.55);margin-top:10px;">Click to take a new photo</p>
        </div>

        {{-- Camera --}}
        <div id="cameraArea" style="display:none;margin-bottom:16px;">
            <video id="camVideo" autoplay playsinline muted style="width:100%;border-radius:14px;"></video>
            <button type="button" onclick="capturePhoto()" class="btn-primary" style="width:100%;margin-top:10px;border-radius:12px;">
                📸 Capture
            </button>
        </div>

        <form method="POST" action="{{ route('profile.photo.update') }}" id="photoForm">
            @csrf
            <input type="hidden" name="photo" id="photoData">
            <canvas id="photoCanvas" style="display:none;"></canvas>
            <input type="file" id="cameraInput" accept="image/*" capture="user" style="display:none;" onchange="handleFileSelect(this)">

            <div style="display:flex;gap:10px;">
                <button type="button" onclick="startWebcam()" class="btn-outline" style="flex:1;border-radius:12px;">
                    🎥 Webcam
                </button>
                <button type="submit" id="saveBtn" class="btn-primary" style="flex:2;border-radius:12px;" disabled>
                    Save Photo
                </button>
            </div>
        </form>
    </div>
</div>
<script>
let stream = null;
async function startWebcam() {
    document.getElementById('cameraArea').style.display = 'block';
    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
    document.getElementById('camVideo').srcObject = stream;
}
function capturePhoto() {
    const video   = document.getElementById('camVideo');
    const canvas  = document.getElementById('photoCanvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const data = canvas.toDataURL('image/jpeg', 0.85);
    document.getElementById('photoData').value = data;
    document.getElementById('photoPreview').src = data;
    document.getElementById('photoPreview').style.display = 'block';
    if (document.getElementById('previewIcon')) document.getElementById('previewIcon').style.display = 'none';
    document.getElementById('saveBtn').disabled = false;
    document.getElementById('cameraArea').style.display = 'none';
    if (stream) stream.getTracks().forEach(t => t.stop());
}
function handleFileSelect(input) {
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoData').value = e.target.result;
        document.getElementById('photoPreview').src = e.target.result;
        document.getElementById('photoPreview').style.display = 'block';
        if (document.getElementById('previewIcon')) document.getElementById('previewIcon').style.display = 'none';
        document.getElementById('saveBtn').disabled = false;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection

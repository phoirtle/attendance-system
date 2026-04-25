/**
 * Heartstrings Attendance System
 * Main JavaScript entry point
 */

// ── GPS Geolocation helper ────────────────────────────────────────
window.HeartstringsGPS = {
    OFFICE_LAT:  parseFloat(document.querySelector('meta[name="office-lat"]')?.content  ?? '-2.985'),
    OFFICE_LNG:  parseFloat(document.querySelector('meta[name="office-lng"]')?.content  ?? '104.732'),
    MAX_DIST:    parseInt(document.querySelector('meta[name="office-radius"]')?.content ?? '100'),

    /**
     * Haversine distance in meters between two GPS points.
     */
    haversine(lat1, lon1, lat2, lon2) {
        const R    = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a    = Math.sin(dLat / 2) ** 2
                   + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
                   * Math.sin(dLon / 2) ** 2;
        return Math.round(6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
    },

    /**
     * Watch GPS and invoke callback(lat, lng, distanceMeters, inRange)
     */
    watch(callback) {
        if (!navigator.geolocation) {
            callback(null, null, null, false, 'GPS not supported on this device.');
            return null;
        }
        return navigator.geolocation.watchPosition(
            pos => {
                const lat  = pos.coords.latitude;
                const lng  = pos.coords.longitude;
                const dist = this.haversine(this.OFFICE_LAT, this.OFFICE_LNG, lat, lng);
                callback(lat, lng, dist, dist <= this.MAX_DIST, null);
            },
            err => callback(null, null, null, false, err.message),
            { enableHighAccuracy: true, maximumAge: 10000, timeout: 15000 }
        );
    },
};

// ── Camera helper ─────────────────────────────────────────────────
window.HeartstringsCamera = {
    stream: null,

    async start(videoEl) {
        this.stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
            audio: false,
        });
        videoEl.srcObject = this.stream;
        return this.stream;
    },

    capture(videoEl, quality = 0.85) {
        const canvas  = document.createElement('canvas');
        canvas.width  = videoEl.videoWidth  || 640;
        canvas.height = videoEl.videoHeight || 480;
        canvas.getContext('2d').drawImage(videoEl, 0, 0);
        return canvas.toDataURL('image/jpeg', quality);
    },

    stop() {
        if (this.stream) {
            this.stream.getTracks().forEach(t => t.stop());
            this.stream = null;
        }
    },
};

// ── CSRF helper for fetch ─────────────────────────────────────────
window.csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.content ?? '';

window.postJSON = async (url, data) => {
    const res = await fetch(url, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken() },
        body:    JSON.stringify(data),
    });
    return res.json();
};

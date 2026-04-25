# 💗 Heartstrings — Staff Attendance System

> Laravel 11 · GPS Geofencing · Webcam Clock-In · Glassmorphism UI

---

## ✨ Features

| Feature | Description |
|---|---|
| 🔐 Auth & Roles | Email/password login with `admin` / `user` roles, enforced by middleware |
| 📍 GPS Geofencing | Haversine formula — blocks clock-in if > 100 m from office |
| 📷 Webcam Capture | Live camera photo required for every clock-in / clock-out |
| 📊 Admin Dashboard | Present % donut chart + Late clock-ins bar chart |
| 📋 Monthly Recap | Filter by month/year, paginated table, CSV export |
| 👤 Profile Hub | Photo upload, password change, personal details, activity log |
| 🎨 Glassmorphism UI | Frosted panels, floating navbar, DM Sans + Playfair Display |

---

## 🖥️ Screenshots (Palette Reference)

| Panel | Hex | Content |
|---|---|---|
| Top-Left | `#E86975` | Clock-In camera feed |
| Top-Right | `#EFAAB0` | GPS map + radius circle |
| Middle-Left | `#EED7C8` | Admin monthly recap |
| Middle-Right | `#FFF9F5` | Navigation menu |
| Bottom-Left | `#BE0822` | Profile card |
| Bottom-Right | `#FD9898` | Admin dashboard charts |

---

## 🚀 Quick Start

### 1. Clone & Install

```bash
git clone https://github.com/your-org/heartstrings-attendance.git
cd heartstrings-attendance
composer install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` — set your database credentials and **office GPS coordinates**:

```env
DB_DATABASE=heartstrings_attendance
DB_USERNAME=root
DB_PASSWORD=your_password

# Office location (change to your actual office coordinates)
OFFICE_LATITUDE=-2.985
OFFICE_LONGITUDE=104.732
OFFICE_RADIUS_METERS=100
```

### 3. Database Setup

```bash
# MySQL
php artisan migrate --seed

# OR SQLite (no setup needed)
touch database/database.sqlite
php artisan migrate --seed
```

### 4. Storage Link

```bash
php artisan storage:link
```

This links `storage/app/public` → `public/storage` for photo serving.

### 5. Run

```bash
php artisan serve
```

Open: **http://localhost:8000**

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php         # Login, logout, role check
│   │   ├── AttendanceController.php        # Haversine, store, recap, export
│   │   └── ProfileController.php          # Photo, password, details, activity
│   └── Middleware/
│       └── AdminMiddleware.php             # Blocks non-admins from /admin/*
└── Models/
    ├── User.php                            # isAdmin(), attendances()
    └── Attendance.php                      # Full attendance model

database/
├── migrations/
│   ├── create_users_table.php              # +role, department, photo_path
│   └── create_attendances_table.php        # lat/lng, distance, photos, status
└── seeders/DatabaseSeeder.php

resources/views/
├── layouts/app.blade.php                   # Fixed glass navbar
├── auth/login.blade.php                    # Role-select login
├── attendance/index.blade.php             # GPS + Webcam clock-in
├── admin/
│   ├── dashboard.blade.php                 # KPIs + charts
│   └── recap.blade.php                     # Monthly table + CSV export
└── profile/
    ├── show.blade.php                      # Hub / settings menu
    ├── photo.blade.php                     # Webcam photo upload
    ├── password.blade.php                  # Change password
    ├── details.blade.php                   # Name, department
    └── activity.blade.php                  # Attendance history

routes/web.php                              # All routes with middleware
bootstrap/app.php                           # Middleware alias registration
```

---

## 🗄️ Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| password | string hashed | |
| role | enum(admin,user) | default: user |
| department | string nullable | |
| photo_path | string nullable | stored in `public/profile_photos/` |

### `attendances`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | |
| date | date | unique per user |
| clock_in | time nullable | |
| clock_out | time nullable | |
| clock_in_latitude | decimal(10,8) | |
| clock_in_longitude | decimal(11,8) | |
| distance_meters | decimal(8,2) | Haversine result |
| photo_path | string | `attendance_photos/clock_in_…jpg` |
| clock_out_photo_path | string nullable | |
| location_status | enum(in_range, out_of_range) | |
| status | enum(present, late, absent) | late if clock_in > 09:00 |

---

## 🧮 Haversine Formula

```php
// AttendanceController.php
private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $R    = 6371000; // Earth radius in meters
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2)**2
       + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;

    return round($R * 2 * atan2(sqrt($a), sqrt(1-$a)), 2);
}
```

Result in **meters**. If `$distance > OFFICE_RADIUS_METERS` → **block with 422**.

---

## 🔒 Security Notes

- CSRF protection on all POST routes
- Passwords hashed with `bcrypt` via Laravel's `hashed` cast
- Role enforcement: `AdminMiddleware` returns 403 for non-admins
- Base64 photos stripped of data URI prefix before decode
- File type enforced to `.jpg` on save

---

## 🎨 Design Tokens

```css
--ruby:    #BE0822;   /* primary CTA, nav active */
--rose:    #E86975;   /* panel tint, gradients   */
--blush:   #FD9898;   /* soft accents            */
--pink:    #EFAAB0;   /* light panel tint        */
--cream:   #EED7C8;   /* warm card backgrounds   */
--ivory:   #FFF9F5;   /* near-white panels       */

--glass-bg:     rgba(255,255,255,0.28);
--glass-border: rgba(255,255,255,0.45);
--blur:         blur(18px) saturate(160%);
```

---

## 🔧 Tailwind Config (in `<script>` tag)

```js
tailwind.config = {
    theme: {
        extend: {
            colors: {
                rose:  { DEFAULT: '#E86975', dark: '#BE0822', light: '#EFAAB0' },
                blush: { DEFAULT: '#FD9898' },
                cream: { DEFAULT: '#EED7C8' },
                ivory: { DEFAULT: '#FFF9F5' },
                ruby:  { DEFAULT: '#BE0822' },
            },
            fontFamily: {
                sans:    ['"DM Sans"', 'system-ui'],
                display: ['"Playfair Display"', 'serif'],
            }
        }
    }
}
```

---

## 📱 Browser Requirements

| Feature | Requirement |
|---|---|
| Geolocation API | HTTPS or localhost |
| getUserMedia (camera) | HTTPS or localhost |
| backdrop-filter (glass) | Chrome 76+, Firefox 103+, Safari 9+ |

> **Important**: GPS and Camera require **HTTPS** in production. Use `php artisan serve` locally (localhost is exempt).

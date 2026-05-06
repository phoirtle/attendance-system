<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'clock_in', 'clock_out',
        'clock_in_latitude', 'clock_in_longitude',
        'clock_out_latitude', 'clock_out_longitude',
        'distance_meters', 'photo_path', 'clock_out_photo_path',
        'location_status', 'status', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function setDateAttribute($value): void
    {
        $this->attributes['date'] = $value instanceof Carbon
            ? $value->toDateString()
            : Carbon::parse($value)->toDateString();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    public function isLate(): bool
    {
        if (!$this->clock_in) return false;
        $workStart = '09:00:00';
        return $this->clock_in > $workStart;
    }
}
  

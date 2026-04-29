<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'department', 'photo_path', 'session_id', 'salary_position_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function salaryPosition()
    {
        return $this->belongsTo(SalaryPosition::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }

    public function approvedLeaveDaysThisYear(): int
    {
        return (int) $this->leaves()
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->get()
            ->sum(fn ($leave) => $leave->duration());
    }

    public function remainingLeaveDays(): int
    {
        return max(0, 12 - $this->approvedLeaveDaysThisYear());
    }

    public function hasApprovedLeaveForDate($date): bool
    {
        return $this->leaves()
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
    }
}


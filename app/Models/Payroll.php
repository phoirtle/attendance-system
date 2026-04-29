<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_position_id',
        'month',
        'year',
        'base_salary',
        'attendance_allowance',
        'deduction',
        'total_salary',
        'status',
    ];

    protected $casts = [
        'month'                => 'integer',
        'year'                 => 'integer',
        'base_salary'          => 'integer',
        'attendance_allowance' => 'integer',
        'deduction'            => 'integer',
        'total_salary'         => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function salaryPosition()
    {
        return $this->belongsTo(SalaryPosition::class);
    }
}


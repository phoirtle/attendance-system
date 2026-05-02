<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_name',
        'department',
        'base_salary',
    ];

    protected $casts = [
        'base_salary' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
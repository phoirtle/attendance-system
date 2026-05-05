<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('salary_positions')->insert([
            [
                'position_name' => 'Finance Staff',
                'department'    => 'Finance',
                'base_salary'   => 7000000,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'position_name' => 'HR Staff',
                'department'    => 'HR',
                'base_salary'   => 8000000,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'position_name' => 'Manager',
                'department'    => 'Management',
                'base_salary'   => 15000000,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'position_name' => 'Marketing Staff',
                'department'    => 'Marketing',
                'base_salary'   => 8000000,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'position_name' => 'Senior Staff',
                'department'    => 'Engineering',
                'base_salary'   => 10000000,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'position_name' => 'Staff',
                'department'    => 'Engineering',
                'base_salary'   => 7000000,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\SalaryPosition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PositionSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'       => 'Admin Heartstrings',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
                'department' => 'Management',
            ]
        );

        $staff = [
            ['Axkeisha Azura Alwaqar', 'axkeisha@gmail.com', 'Engineering', '081234567890', 'Jl. Engineering 123', '1995-03-15', 'female', 'EMP-001', '2024-01-01', 'permanent'],
            ['Careena Putri', 'careena@gmail.com', 'Marketing', '081234567891', 'Jl. Marketing 456', '1996-07-22', 'female', 'EMP-002', '2024-01-15', 'permanent'],
            ['Dea Amalia Rombon', 'dea@gmail.com', 'Finance', '081234567892', 'Jl. Finance 789', '1994-11-30', 'female', 'EMP-003', '2023-12-01', 'permanent'],
            ['Arkan Syahputra', 'arkan@gmail.com', 'HR', '081234567893', 'Jl. HR 101', '1993-05-10', 'male', 'EMP-004', '2023-11-20', 'permanent'],
        ];

        $positions = SalaryPosition::all()->keyBy('department');
        $users = [];

        foreach ($staff as [$name, $email, $dept, $phone, $address, $birthDate, $gender, $nik, $joinDate, $status]) {
            $users[] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'               => $name,
                    'password'           => Hash::make('password'),
                    'role'               => 'user',
                    'department'         => $dept,
                    'salary_position_id' => $positions->get($dept)?->id,
                    'phone'              => $phone,
                    'address'            => $address,
                    'birth_date'         => $birthDate,
                    'gender'             => $gender,
                    'employee_id_number' => $nik,
                    'join_date'          => $joinDate,
                    'employment_status'  => $status,
                ]
            );
        }

        foreach ($users as $user) {
            for ($i = 30; $i >= 1; $i--) {
                $date = Carbon::today()->subDays($i);

                if ($date->isWeekend()) {
                    continue;
                }

                if (rand(1, 10) > 8) {
                    continue;
                }

                $isLate = rand(1, 10) > 7;
                $clockInH = $isLate ? rand(9, 10) : 8;
                $clockInM = rand(0, 59);
                $clockOutH = rand(17, 18);
                $clockOutM = rand(0, 59);
                $distance = rand(5, 95);

                Attendance::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'date'    => $date->toDateString(),
                    ],
                    [
                        'clock_in'           => sprintf('%02d:%02d:00', $clockInH, $clockInM),
                        'clock_out'          => sprintf('%02d:%02d:00', $clockOutH, $clockOutM),
                        'clock_in_latitude'  => -3.21948078 + (rand(-50, 50) / 100000),
                        'clock_in_longitude' => 104.65116482 + (rand(-50, 50) / 100000),
                        'distance_meters'    => $distance,
                        'location_status'    => 'in_range',
                        'status'             => $isLate ? 'late' : 'present',
                    ]
                );
            }
        }

        $payrollPeriods = [
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonth(),
        ];

        foreach ($users as $index => $user) {
            $position = $user->salaryPosition;

            if (!$position) {
                continue;
            }

            foreach ($payrollPeriods as $periodIndex => $period) {
                $alpha = ($index + $periodIndex) % 3;
                $deductionPerAlpha = (int) round($position->base_salary * 0.045);
                $deduction = $alpha * $deductionPerAlpha;

                Payroll::updateOrCreate(
                    [
                        'employee_id' => $user->id,
                        'month'       => $period->month,
                        'year'        => $period->year,
                    ],
                    [
                        'salary_position_id' => $position->id,
                        'base_salary'        => $position->base_salary,
                        'alpha'              => $alpha,
                        'deduction'          => $deduction,
                        'total_salary'       => max(0, $position->base_salary - $deduction),
                        'status'             => 'finalized',
                    ]
                );
            }
        }

        $this->command->info('Seeded admin + 4 staff users with 30-day attendance history.');
        $this->command->info('Seeded My Salary payroll data for all 4 staff users.');
        $this->command->info('Admin login : admin@gmail.com / password');
        $this->command->info('User login  : dea@gmail.com / password');
    }
}

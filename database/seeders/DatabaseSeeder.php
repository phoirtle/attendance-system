<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ────────────────────────────────────────────────────
        $admin = User::create([
            'name'       => 'Admin Heartstrings',
            'email'      => 'admin@gmail.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'department' => 'Management',
        ]);

        // ── Staff users ───────────────────────────────────────────────────
        $staff = [
            ['Axkeisha Azura Alwaqar', 'axkeisha@gmail.com', 'Engineering', '081234567890', 'Jl. Engineering 123', '1995-03-15', 'female', 'EMP-001', '2024-01-01', 'permanent'],
            ['Careena Putri', 'careena@gmail.com', 'Marketing', '081234567891', 'Jl. Marketing 456', '1996-07-22', 'female', 'EMP-002', '2024-01-15', 'permanent'],
            ['Dea Amalia Rombon', 'dea@gmail.com', 'Finance', '081234567892', 'Jl. Finance 789', '1994-11-30', 'female', 'EMP-003', '2023-12-01', 'permanent'],
            ['Arkan Syahputra', 'arkan@gmail.com', 'HR', '081234567893', 'Jl. HR 101', '1993-05-10', 'male', 'EMP-004', '2023-11-20', 'permanent']
        ];

        $users = [];
        foreach ($staff as [$name, $email, $dept, $phone, $address, $birth_date, $gender, $nik, $join_date, $status]) {
            $users[] = User::create([
                'name'               => $name,
                'email'              => $email,
                'password'           => Hash::make('password'),
                'role'               => 'user',
                'department'         => $dept,
                'phone'              => $phone,
                'address'            => $address,
                'birth_date'         => $birth_date,
                'gender'             => $gender,
                'employee_id_number' => $nik,
                'join_date'          => $join_date,
                'employment_status'  => $status,
            ]);
        }

        // ── Seed attendance records (last 30 days) ────────────────────────
        foreach ($users as $user) {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);

                // Skip weekends
                if ($date->isWeekend()) continue;

                // 80% chance of attendance
                if (rand(1, 10) > 8) continue;

                $isLate     = rand(1, 10) > 7; // 30% late
                $clockInH   = $isLate ? rand(9, 10) : 8;
                $clockInM   = rand(0, 59);
                $clockOutH  = rand(17, 18);
                $clockOutM  = rand(0, 59);
                $distance   = rand(5, 95); // within range for demo

                Attendance::create([
                'user_id'            => $user->id,
                'date'               => $date->toDateString(),
                'clock_in'           => sprintf('%02d:%02d:00', $clockInH, $clockInM),
                'clock_out'          => sprintf('%02d:%02d:00', $clockOutH, $clockOutM),
                
                // GANTI BAGIAN INI:
                'clock_in_latitude'  => -3.21948078 + (rand(-50, 50) / 100000), 
                'clock_in_longitude' => 104.65116482 + (rand(-50, 50) / 100000),
                
                'distance_meters'    => $distance,
                'location_status'    => 'in_range',
                'status'             => $isLate ? 'late' : 'present'
            ]);
            }
        }

        $this->command->info('✓ Seeded admin + 4 staff users with 30-day attendance history.');
        $this->command->info('  Admin login : admin@gmail.com / password');
        $this->command->info('  User login  : dea@gmail.com / password');
    }
}

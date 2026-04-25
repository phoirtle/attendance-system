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
            ['Axkeisha Azura Alwaqar', 'axkeisha@gmail.com', 'Engineering'],
            ['Careena Putri', 'careena@gmail.com', 'Marketing'],
            ['Dea Amalia Rombon', 'dea@gmail.com', 'Finance'],
            ['Arkan Syahputra', 'arkan@gmail.com', 'HR']
        ];

        $users = [];
        foreach ($staff as [$name, $email, $dept]) {
            $users[] = User::create([
                'name'       => $name,
                'email'      => $email,
                'password'   => Hash::make('password'),
                'role'       => 'user',
                'department' => $dept,
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

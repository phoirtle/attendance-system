<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Leave;
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

        $positions = SalaryPosition::all()
            ->keyBy(fn (SalaryPosition $position) => $position->department . '|' . $position->position_name);

        User::create([
            'name' => 'Admin Heartstrings',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'Management',
            'phone' => '081234567800',
            'address' => 'Jl. Management 1, Palembang',
            'birth_date' => '1990-02-11',
            'gender' => 'female',
            'employee_id_number' => 'ADM-001',
            'join_date' => '2023-01-01',
            'employment_status' => 'permanent',
            'salary_position_id' => $positions->get('Management|Manager')?->id,
        ]);

        $staff = [
            ['Axkeisha Azura Alwaqar', 'axkeisha@gmail.com', 'Engineering', 'Senior Staff', '081234567890', 'Jl. Engineering 123, Palembang', '1995-03-15', 'female', 'EMP-001', '2024-01-01', 'permanent'],
            ['Careena Putri', 'careena@gmail.com', 'Marketing', 'Marketing Staff', '081234567891', 'Jl. Marketing 456, Palembang', '1996-07-22', 'female', 'EMP-002', '2024-01-15', 'permanent'],
            ['Dea Amalia Rombon', 'dea@gmail.com', 'Finance', 'Finance Staff', '081234567892', 'Jl. Finance 789, Palembang', '1994-11-30', 'female', 'EMP-003', '2023-12-01', 'permanent'],
            ['Arkan Syahputra', 'arkan@gmail.com', 'HR', 'HR Staff', '081234567893', 'Jl. HR 101, Palembang', '1993-05-10', 'male', 'EMP-004', '2023-11-20', 'permanent'],
            ['Nadia Larasati', 'nadia@gmail.com', 'Engineering', 'Staff', '081234567894', 'Jl. Anggrek No. 12, Palembang', '1997-04-18', 'female', 'EMP-005', '2024-02-05', 'permanent'],
            ['Rafi Pratama', 'rafi@gmail.com', 'Engineering', 'Staff', '081234567895', 'Jl. Demang Lebar Daun No. 8, Palembang', '1998-09-09', 'male', 'EMP-006', '2024-03-01', 'contract'],
            ['Maya Salsabila', 'maya@gmail.com', 'Marketing', 'Marketing Staff', '081234567896', 'Jl. Rajawali No. 21, Palembang', '1996-12-24', 'female', 'EMP-007', '2023-10-16', 'permanent'],
            ['Dimas Pradana', 'dimas@gmail.com', 'Finance', 'Finance Staff', '081234567897', 'Jl. Veteran No. 33, Palembang', '1992-06-03', 'male', 'EMP-008', '2022-08-22', 'permanent'],
            ['Sinta Maharani', 'sinta@gmail.com', 'HR', 'HR Staff', '081234567898', 'Jl. Basuki Rahmat No. 17, Palembang', '1995-01-27', 'female', 'EMP-009', '2023-05-08', 'permanent'],
            ['Bima Erlangga', 'bima@gmail.com', 'Management', 'Manager', '081234567899', 'Jl. Kol. H. Burlian No. 40, Palembang', '1989-10-14', 'male', 'EMP-010', '2021-07-12', 'permanent'],
        ];

        $users = [];

        foreach ($staff as [$name, $email, $department, $positionName, $phone, $address, $birthDate, $gender, $employeeNumber, $joinDate, $employmentStatus]) {
            $users[] = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'user',
                'department' => $department,
                'phone' => $phone,
                'address' => $address,
                'birth_date' => $birthDate,
                'gender' => $gender,
                'employee_id_number' => $employeeNumber,
                'join_date' => $joinDate,
                'employment_status' => $employmentStatus,
                'salary_position_id' => $positions->get($department . '|' . $positionName)?->id,
            ]);
        }

        $lastMonth = Carbon::today()->subMonthNoOverflow();
        $this->seedApprovedLeaves($users, $lastMonth);
        $this->seedLastMonthAttendances($users, $lastMonth);

        $this->command->info('Seeded admin + 10 staff users with complete profiles and last-month attendance history.');
        $this->command->info('Admin login : admin@gmail.com / password');
        $this->command->info('User login  : dea@gmail.com / password');
    }

    private function seedApprovedLeaves(array $users, Carbon $lastMonth): void
    {
        $approvedLeaves = [
            'EMP-002' => ['type' => 'annual', 'start' => $lastMonth->copy()->startOfMonth()->addWeekdays(7), 'days' => 2, 'reason' => 'Family event'],
            'EMP-006' => ['type' => 'sick', 'start' => $lastMonth->copy()->startOfMonth()->addWeekdays(12), 'days' => 1, 'reason' => 'Medical rest'],
            'EMP-009' => ['type' => 'permission', 'start' => $lastMonth->copy()->startOfMonth()->addWeekdays(15), 'days' => 1, 'reason' => 'Personal permit'],
        ];

        foreach ($users as $user) {
            $leaveConfig = $approvedLeaves[$user->employee_id_number] ?? null;

            if (!$leaveConfig) {
                continue;
            }

            $start = $leaveConfig['start']->copy();
            $end = $start->copy()->addWeekdays($leaveConfig['days'] - 1);

            Leave::create([
                'user_id' => $user->id,
                'type' => $leaveConfig['type'],
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'reason' => $leaveConfig['reason'],
                'status' => 'approved',
                'admin_note' => 'Seeded approved leave for demo data.',
            ]);
        }
    }

    private function seedLastMonthAttendances(array $users, Carbon $lastMonth): void
    {
        foreach ($users as $user) {
            $cursor = $lastMonth->copy()->startOfMonth();
            $endOfMonth = $lastMonth->copy()->endOfMonth();
            $workdayIndex = 0;

            while ($cursor->lte($endOfMonth)) {
                $date = $cursor->copy();
                $cursor->addDay();

                if ($date->isWeekend()) {
                    continue;
                }

                $workdayIndex++;
                $isLeave = $user->hasApprovedLeaveForDate($date);
                $isAlpha = !$isLeave && (($workdayIndex + $user->id) % 11 === 0);
                $isLate = !$isLeave && !$isAlpha && (($workdayIndex + $user->id) % 5 === 0);

                if ($isLeave || $isAlpha) {
                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date->toDateString(),
                        'clock_in' => null,
                        'clock_out' => null,
                        'clock_in_latitude' => null,
                        'clock_in_longitude' => null,
                        'distance_meters' => null,
                        'location_status' => $isLeave ? 'in_range' : 'out_of_range',
                        'status' => $isLeave ? 'leave' : 'alpha',
                        'notes' => $isLeave ? 'Approved leave' : 'Seeded alpha record',
                    ]);

                    continue;
                }

                $clockInH = $isLate ? 9 : 8;
                $clockInM = $isLate
                    ? 10 + (($workdayIndex + $user->id) % 35)
                    : 5 + (($workdayIndex + $user->id) % 40);
                $clockOutH = 17 + (($workdayIndex + $user->id) % 2);
                $clockOutM = 5 + (($workdayIndex * 3 + $user->id) % 50);
                $distance = 12 + (($workdayIndex * 7 + $user->id) % 80);

                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'clock_in' => sprintf('%02d:%02d:00', $clockInH, $clockInM),
                    'clock_out' => sprintf('%02d:%02d:00', $clockOutH, $clockOutM),
                    'clock_in_latitude' => -3.21948078 + ((($workdayIndex % 9) - 4) / 100000),
                    'clock_in_longitude' => 104.65116482 + ((($user->id % 9) - 4) / 100000),
                    'distance_meters' => $distance,
                    'location_status' => 'in_range',
                    'status' => $isLate ? 'late' : 'present',
                    'notes' => 'Seeded attendance for last month',
                ]);
            }
        }
    }
}

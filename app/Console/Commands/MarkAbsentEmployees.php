<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature   = 'attendance:mark-absent {--date= : Date to process (Y-m-d), defaults to today}';
    protected $description = 'Mark employees who never clocked in today as absent';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::today();

        // Skip weekends
        if ($date->isWeekend()) {
            $this->info("Skipping {$date->toDateString()} — weekend.");
            return self::SUCCESS;
        }

        $users = User::where('role', 'user')->get();
        $marked = 0;

        foreach ($users as $user) {
            $exists = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->exists();

            if (!$exists) {
                $onLeave = $user->hasApprovedLeaveForDate($date);

                Attendance::create([
                    'user_id'         => $user->id,
                    'date'            => $date->toDateString(),
                    'location_status' => 'out_of_range',
                    'status'          => $onLeave ? 'leave' : 'absent',
                    'notes'           => $onLeave ? 'On approved leave.' : 'Auto-marked absent by system.',
                ]);
                $marked++;
                $this->line($onLeave
                    ? "  ○ Marked on leave: {$user->name}"
                    : "  ✗ Marked absent: {$user->name}");
            }
        }

        $this->info("Done. Marked {$marked} employee(s) absent for {$date->toDateString()}.");
        return self::SUCCESS;
    }
}

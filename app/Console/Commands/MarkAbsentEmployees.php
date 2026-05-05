<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceAlphaService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent {--date= : Date to process (Y-m-d), defaults to today}';
    protected $description = 'Mark employees who never clocked in on a workday as alpha';

    public function handle(AttendanceAlphaService $alphaService): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->startOfDay()
            : Carbon::today();

        if (!$alphaService->isWorkday($date)) {
            $this->info("Skipping {$date->toDateString()} - weekend.");
            return self::SUCCESS;
        }

        $users = User::where('role', 'user')->orderBy('name')->get();
        $marked = 0;

        foreach ($users as $user) {
            $alreadyRecorded = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->exists();

            if ($alreadyRecorded) {
                continue;
            }

            $marked += $alphaService->markMissingForDate($date, $user, true);
            $this->line($user->hasApprovedLeaveForDate($date)
                ? "  Leave: {$user->name}"
                : "  Alpha: {$user->name}");
        }

        $this->info("Done. Marked {$marked} employee attendance record(s) for {$date->toDateString()}.");
        return self::SUCCESS;
    }
}

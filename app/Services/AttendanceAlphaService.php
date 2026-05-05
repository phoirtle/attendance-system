<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AttendanceAlphaService
{
    public function markMissingForDate(Carbon|string $date, ?User $user = null, bool $allowToday = false): int
    {
        $date = Carbon::parse($date)->startOfDay();

        if (!$this->isWorkday($date)) {
            return 0;
        }

        if (!$allowToday && $date->greaterThanOrEqualTo(today())) {
            return 0;
        }

        $users = $user
            ? collect([$user])
            : User::where('role', 'user')->get();

        $marked = 0;

        foreach ($users as $employee) {
            if ($employee->join_date && $date->lessThan($employee->join_date->copy()->startOfDay())) {
                continue;
            }

            $exists = Attendance::where('user_id', $employee->id)
                ->whereDate('date', $date)
                ->exists();

            if ($exists) {
                continue;
            }

            $onLeave = $employee->hasApprovedLeaveForDate($date);

            Attendance::create([
                'user_id'         => $employee->id,
                'date'            => $date->toDateString(),
                'location_status' => 'out_of_range',
                'status'          => $onLeave ? 'leave' : 'alpha',
                'notes'           => $onLeave
                    ? 'Auto-marked as approved leave by system.'
                    : 'Auto-marked alpha because no attendance was recorded on a workday.',
            ]);

            $marked++;
        }

        return $marked;
    }

    public function markMissingForMonth(int $month, int $year, ?User $user = null): int
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $cutoff = today()->subDay()->endOfDay();

        if ($end->greaterThan($cutoff)) {
            $end = $cutoff;
        }

        if ($end->lessThan($start)) {
            return 0;
        }

        $marked = 0;

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $marked += $this->markMissingForDate($date, $user);
        }

        return $marked;
    }

    public function isWorkday(Carbon $date): bool
    {
        return $date->isWeekday();
    }
}

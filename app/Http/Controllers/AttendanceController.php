<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Office coordinates — configure via .env (OFFICE_LATITUDE, OFFICE_LONGITUDE, OFFICE_RADIUS_METERS)
    private function officeLat(): float  { return (float) env('OFFICE_LATITUDE',  -2.985); }
    private function officeLong(): float { return (float) env('OFFICE_LONGITUDE', 104.732); }
    private function maxDistance(): int  { return (int)   env('OFFICE_RADIUS_METERS', 100); }

    /**
     * Haversine formula: returns distance in meters between two GPS coordinates.
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Show the attendance index page for users.
     */
    public function index()
    {
        $user       = auth()->user();
        $attendance = $user->todayAttendance();

        return view('attendance.index', compact('user', 'attendance'));
    }

    /**
     * Show user's own attendance history.
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $attendances = $user->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.history', compact('user', 'attendances', 'month', 'year'));
    }

    /**
     * Clock In — validates GPS range, saves photo, stores record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'photo'     => ['required', 'string'], // base64 image data URI
        ]);

        $user     = auth()->user();
        $lat      = (float) $request->latitude;
        $lon      = (float) $request->longitude;
        $distance = $this->haversineDistance($this->officeLat(), $this->officeLong(), $lat, $lon);

        // Check existing today record
        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('date', today())
                                ->first();

        if ($attendance && $attendance->clock_in && $attendance->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'You have already clocked in and out today.',
            ], 422);
        }

        // Geofence check only for clock-in
        if (!$attendance) {
            if ($distance > $this->maxDistance()) {
                return response()->json([
                    'success'  => false,
                    'message'  => "You are {$distance}m away from the office. Must be within " . $this->maxDistance() . "m to clock in.",
                    'distance' => $distance,
                ], 422);
            }
        }

        // Save photo from base64
        $photoPath = $this->saveBase64Photo($request->photo, 'clock_in');

        $now        = Carbon::now();
        $workStart  = Carbon::today()->setHour(9);
        $status     = $now->greaterThan($workStart) ? 'late' : 'present';
        $locStatus  = $distance <= $this->maxDistance() ? 'in_range' : 'out_of_range';

        if (!$attendance) {
            // Clock In
            $attendance = Attendance::create([
                'user_id'            => $user->id,
                'date'               => today(),
                'clock_in'           => $now->format('H:i:s'),
                'clock_in_latitude'  => $lat,
                'clock_in_longitude' => $lon,
                'distance_meters'    => $distance,
                'photo_path'         => $photoPath,
                'location_status'    => $locStatus,
                'status'             => $status,
            ]);

            $message = "Clocked in successfully at {$now->format('H:i')}!";
        } else {
            // Clock Out
            $clockOutPhoto = $this->saveBase64Photo($request->photo, 'clock_out');

            $attendance->update([
                'clock_out'            => $now->format('H:i:s'),
                'clock_out_latitude'   => $lat,
                'clock_out_longitude'  => $lon,
                'clock_out_photo_path' => $clockOutPhoto,
            ]);

            $message = "Clocked out successfully at {$now->format('H:i')}!";
        }

        return response()->json([
            'success'    => true,
            'message'    => $message,
            'distance'   => $distance,
            'attendance' => $attendance->fresh(),
        ]);
    }

    /**
     * Admin: Monthly recap view.
     */
    public function adminRecap(Request $request)
    {
        if ($request->filled('date')) {
            $date = \Carbon\Carbon::parse($request->input('date'));
            $month = $date->month;
            $year  = $date->year;

            $attendances = Attendance::with('user')
                ->whereDate('date', $date)
                ->orderBy('user_id')
                ->get();
        } else {
            $month = $request->integer('month', now()->month);
            $year  = $request->integer('year', now()->year);

            $attendances = Attendance::with('user')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->orderBy('date', 'desc')
                ->orderBy('user_id')
                ->get();
        }

        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.recap', compact('attendances', 'users', 'month', 'year'));
    }

    /**
     * Admin: Dashboard stats.
     */
    public function adminDashboard()
    {
        $today = today();

        $totalUsers    = User::where('role', 'user')->count();
        $presentToday  = Attendance::whereDate('date', $today)->where('status', '!=', 'absent')->where('status', '!=', 'leave')->count();
        $lateToday     = Attendance::whereDate('date', $today)->where('status', 'late')->count();
        $leaveToday    = Attendance::whereDate('date', $today)->where('status', 'leave')->count();
        $absentToday   = $totalUsers - $presentToday - $leaveToday;
        $pendingLeaves = \App\Models\Leave::where('status', 'pending')->count();

        // Late clock-ins per day this week
        $weekStart = $today->copy()->startOfWeek();
        $lateByDay = [];
        for ($d = 0; $d < 7; $d++) {
            $day = $weekStart->copy()->addDays($d);
            $lateByDay[] = [
                'day'   => $day->format('D'),
                'date'  => $day->format('Y-m-d'),
                'count' => Attendance::whereDate('date', $day)->where('status', 'late')->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'presentToday', 'lateToday', 'leaveToday', 'absentToday', 'pendingLeaves', 'lateByDay'
        ));
    }

    /**
     * Export monthly recap to CSV.
     */
    public function exportRecap(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $attendances = Attendance::with('user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        $monthName = Carbon::create($year, $month)->format('F_Y');
        $filename  = "attendance_recap_{$monthName}.csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee', 'Department', 'Date', 'Clock In', 'Clock Out', 'Distance (m)', 'Location Status', 'Status']);

            foreach ($attendances as $a) {
                fputcsv($handle, [
                    $a->user->name ?? '—',
                    $a->user->department ?? '—',
                    $a->date->format('d M Y'),
                    $a->clock_in ?? '—',
                    $a->clock_out ?? '—',
                    $a->distance_meters ?? '—',
                    ucfirst(str_replace('_', ' ', $a->location_status)),
                    ucfirst($a->status),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // -----------------------------------------------------------------------
    // Employee (User) Management
    // -----------------------------------------------------------------------

    public function usersIndex()
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        $users = User::where('role', 'user')
            ->with([
                'salaryPosition',
                'attendances' => fn($q) => $q->whereMonth('date', $currentMonth)
                                             ->whereYear('date', $currentYear),
                'leaves',
            ])
            ->orderBy('name')
            ->get();

        // Siapkan data JSON untuk panel detail (hindari logika kompleks di blade)
        $staffData = $users->map(function ($u) {
            return [
                'id'              => $u->id,
                'name'            => $u->name,
                'email'           => $u->email,
                'department'      => $u->department ?? '—',
                'photo_url'       => $u->photo_path ? Storage::url($u->photo_path) : null,
                'initials'        => strtoupper(substr($u->name, 0, 1)),
                'position'        => $u->salaryPosition?->position_name ?? '—',
                'base_salary'     => $u->salaryPosition?->base_salary ?? null,
                'remaining_leave' => $u->remainingLeaveDays(),
                'edit_url'        => route('admin.users.edit', $u),
                'attendance_this_month' => [
                    'present' => $u->attendances->where('status', 'present')->count(),
                    'late'    => $u->attendances->where('status', 'late')->count(),
                    'absent'  => $u->attendances->where('status', 'absent')->count(),
                    'leave'   => $u->attendances->where('status', 'leave')->count(),
                ],
                'recent_leaves' => $u->leaves
                    ->sortByDesc('created_at')
                    ->take(5)
                    ->values()
                    ->map(fn ($l) => [
                        'type'       => $l->type,
                        'start_date' => $l->start_date->format('d M Y'),
                        'end_date'   => $l->end_date->format('d M Y'),
                        'duration'   => $l->duration(),
                        'status'     => $l->status,
                    ])->toArray(),
            ];
        })->keyBy('id');

        return view('admin.users.index', compact('users', 'staffData'));
    }

    public function usersCreate()
    {
        return view('admin.users.create');
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'department' => ['nullable', 'string', 'max:255'],
            'password'   => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'department' => $request->department,
            'password'   => bcrypt($request->password),
            'role'       => 'user',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Employee created successfully.');
    }

    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email,' . $user->id],
            'department' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($request->only('name', 'email', 'department'));

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Employee updated successfully.');
    }

    public function usersDestroy(User $user)
    {
        $user->attendances()->delete();
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Employee deleted successfully.');
    }

    // -----------------------------------------------------------------------
    // Leave Requests
    // -----------------------------------------------------------------------

    public function leavesIndex()
    {
        $leaves = auth()->user()->leaves()->orderBy('created_at', 'desc')->get();
        return view('leaves.index', compact('leaves'));
    }

    public function leavesCreate()
    {
        return view('leaves.create');
    }

    public function leavesStore(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'type'        => ['required', 'in:sick,permission,annual'],
            'start_date'  => ['required', 'date', 'after_or_equal:today'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'reason'      => ['required', 'string', 'max:1000'],
        ];

        if ($request->input('type') === 'sick') {
            $rules['document'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }

        $request->validate($rules);

        $duration = \Carbon\Carbon::parse($request->start_date)
            ->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;

        // Hanya annual leave yang dibatasi oleh kuota cuti
        if ($request->input('type') === 'annual' && $duration > $user->remainingLeaveDays()) {
            return back()->withErrors(['end_date' => 'Leave quota exceeded. Remaining annual leave: ' . $user->remainingLeaveDays() . ' days.']);
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('leave_documents', 'public');
        }

        $user->leaves()->create([
            'type'          => $request->type,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'reason'        => $request->reason,
            'document_path' => $documentPath,
            'status'        => 'pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully and is awaiting approval.');
    }

    public function leavesDestroy(\App\Models\Leave $leave)
    {
        if ($leave->user_id !== auth()->id()) {
            abort(403);
        }
        if ($leave->status === 'approved') {
            return back()->with('error', 'Cannot delete an already approved leave request.');
        }
        if ($leave->document_path) {
            Storage::disk('public')->delete($leave->document_path);
        }
        $leave->delete();
        return back()->with('success', 'Leave request cancelled.');
    }

    public function adminLeaves()
    {
        $leaves = \App\Models\Leave::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.leaves.index', compact('leaves'));
    }

    public function approveLeave(\App\Models\Leave $leave)
    {
        $leave->update(['status' => 'approved']);
        return back()->with('success', 'Leave request approved.');
    }

    public function rejectLeave(Request $request, \App\Models\Leave $leave)
    {
        $leave->update([
            'status'     => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);
        return back()->with('success', 'Leave request rejected.');
    }

    // -----------------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------------

    private function saveBase64Photo(string $base64Data, string $prefix = 'photo'): string
    {
        // Strip data URI prefix: "data:image/jpeg;base64,..."
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
        $decoded   = base64_decode($imageData);

        $filename  = $prefix . '_' . auth()->id() . '_' . now()->format('Ymd_His') . '.jpg';
        $path      = 'attendance_photos/' . $filename;

        Storage::disk('public')->put($path, $decoded);

        return $path;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\SalaryPosition;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PayrollController extends Controller
{
    /* ================================================================
       ADMIN: Salary Positions (Daftar Gaji per Posisi)
       ================================================================ */

    public function salaryPositionsIndex()
    {
        $positions = SalaryPosition::orderBy('position_name')->get();
        return view('admin.salary-positions.index', compact('positions'));
    }

    public function salaryPositionsCreate()
    {
        return view('admin.salary-positions.create');
    }

    public function salaryPositionsStore(Request $request)
    {
        $request->validate([
            'position_name' => ['required', 'string', 'max:255'],
            'department'    => ['nullable', 'string', 'max:255'],
            'base_salary'   => ['required', 'integer', 'min:0'],
        ]);

        SalaryPosition::create($request->only('position_name', 'department', 'base_salary'));

        return redirect()->route('admin.salary-positions.index')
            ->with('success', 'Posisi salary berhasil ditambahkan.');
    }

    public function salaryPositionsEdit(SalaryPosition $salaryPosition)
    {
        return view('admin.salary-positions.edit', compact('salaryPosition'));
    }

    public function salaryPositionsUpdate(Request $request, SalaryPosition $salaryPosition)
    {
        $request->validate([
            'position_name' => ['required', 'string', 'max:255'],
            'department'    => ['nullable', 'string', 'max:255'],
            'base_salary'   => ['required', 'integer', 'min:0'],
        ]);

        $salaryPosition->update($request->only('position_name', 'department', 'base_salary'));

        return redirect()->route('admin.salary-positions.index')
            ->with('success', 'Posisi salary berhasil diperbarui.');
    }

    public function salaryPositionsDestroy(SalaryPosition $salaryPosition)
    {
        $salaryPosition->delete();
        return redirect()->route('admin.salary-positions.index')
            ->with('success', 'Posisi salary berhasil dihapus.');
    }

    /* ================================================================
       ADMIN: Payroll / Slip Gaji
       ================================================================ */

    public function payrollIndex(Request $request)
    {
        $month = $request->integer('month', now()->subMonth()->month);
$year  = $request->integer('year', now()->subMonth()->year);
        $department = $request->input('department');

        $query = Payroll::with(['employee', 'salaryPosition'])
            ->where('month', $month)
            ->where('year', $year);

        if ($department) {
            $query->whereHas('employee', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        $payrolls    = $query->get();
        $departments = User::where('role', 'user')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        return view('admin.payrolls.index', compact('payrolls', 'month', 'year', 'department', 'departments'));
    }

    public function payrollGenerate(Request $request)
    {
        $request->validate([
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year'  => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $month = $request->integer('month');
        $year  = $request->integer('year');

        // Blokir generate jika bulan yang dipilih adalah bulan berjalan atau masa depan
        $selected = Carbon::create($year, $month, 1)->startOfMonth();
        $current  = Carbon::now()->startOfMonth();

        if ($selected >= $current) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['month' => 'Payroll hanya bisa digenerate setelah periode bulan berakhir. Pilih bulan sebelum ' . $current->translatedFormat('F Y') . '.']);
        }

        $users = User::where('role', 'user')
            ->whereNotNull('salary_position_id')
            ->with('salaryPosition')
            ->get();

        $generated = 0;
        foreach ($users as $user) {
            $position = $user->salaryPosition;
            if (!$position) continue;

            $calc = $this->calculatePayroll($user, $position, $month, $year);

            $payroll = Payroll::firstOrNew([
                'employee_id' => $user->id,
                'month'       => $month,
                'year'        => $year,
            ]);

            $payroll->salary_position_id = $position->id;
            $payroll->base_salary        = $calc['base_salary'];
            $payroll->alpha              = $calc['alpha'];
            $payroll->deduction          = $calc['deduction'];
            $payroll->total_salary       = $calc['total_salary'];
            $payroll->status             = 'finalized';
            $payroll->save();

            $generated++;
        }

        return redirect()->route('admin.payrolls.index', ['month' => $month, 'year' => $year])
            ->with('success', "Berhasil generate {$generated} slip gaji untuk " . Carbon::create($year, $month)->translatedFormat('F Y') . '.');
    }

    public function payrollShow(Payroll $payroll)
    {
        $payroll->load(['employee', 'salaryPosition']);
        $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
        return view('admin.payrolls.show', compact('payroll', 'calc'));
    }

    public function payrollPrint(Payroll $payroll)
    {
        $payroll->load(['employee', 'salaryPosition']);
        $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
        return view('admin.payrolls.print', compact('payroll', 'calc'));
    }

    /* ================================================================
       ADMIN: Recap Penggajian
       ================================================================ */

    public function payrollRecap(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $payrolls = Payroll::with(['employee', 'salaryPosition'])
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $totalExpense = $payrolls->sum('total_salary');

        // Group by department
        $byDepartment = [];
        foreach ($payrolls as $p) {
            $dept                  = $p->employee->department ?? 'Tanpa Departemen';
            $byDepartment[$dept]   = ($byDepartment[$dept] ?? 0) + $p->total_salary;
        }

        return view('admin.payrolls.recap', compact('payrolls', 'month', 'year', 'totalExpense', 'byDepartment'));
    }

    /* ================================================================
       EMPLOYEE: My Salary
       ================================================================ */

    public function mySalary(Request $request)
    {
        $user  = auth()->user();
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $payrolls = $user->payrolls()
            ->with('salaryPosition')
            ->when($request->filled('month'), fn($q) => $q->where('month', $month))
            ->when($request->filled('year'),  fn($q) => $q->where('year', $year))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('salary.index', compact('user', 'payrolls', 'month', 'year'));
    }

    public function mySalaryShow(Payroll $payroll)
    {
        if ($payroll->employee_id !== auth()->id()) {
            abort(403);
        }
        $payroll->load(['employee', 'salaryPosition']);
        $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
        return view('salary.show', compact('payroll', 'calc'));
    }

    public function mySalaryPrint(Payroll $payroll)
    {
        if ($payroll->employee_id !== auth()->id()) {
            abort(403);
        }
        $payroll->load(['employee', 'salaryPosition']);
        $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
        return view('salary.print', compact('payroll', 'calc'));
    }

    /* ================================================================
       PRIVATE: Payroll Calculation
       ================================================================ */

    private function calculatePayroll(User $user, ?SalaryPosition $position, int $month, int $year): array
    {
        if (!$position) {
            return [
                'base_salary'  => 0,
                'alpha'        => 0,
                'deduction'    => 0,
                'total_salary' => 0,
                'work_days'    => 0,
                'present_days' => 0,
                'late_days'    => 0,
                'absent_days'  => 0,
                'leave_days'   => 0,
            ];
        }

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // Hitung hari kerja (Senin–Sabtu, tidak termasuk Minggu)
        $period   = CarbonPeriod::create($start, $end);
        $workDays = 0;
        foreach ($period as $date) {
            if ($date->dayOfWeek !== Carbon::SUNDAY) {
                $workDays++;
            }
        }

        // Rekap kehadiran dari tabel attendance
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

       $presentDays = $attendances->where('status', 'present')->count();
$lateDays    = $attendances->where('status', 'late')->count();
$absentDays  = $attendances->where('status', 'absent')->count();
$leaveDays   = $attendances->where('status', 'leave')->count();
$alphaDays   = $attendances->where('status', 'alpha')->count(); // tambah ini
        // Hari kerja yang tidak ada record → cek apakah ada cuti disetujui, jika tidak = alpha
        $recordedDates = $attendances->pluck('date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();

        foreach ($period as $date) {
    if ($date->dayOfWeek === Carbon::SUNDAY) continue;
    $dStr = $date->format('Y-m-d');
    if (!in_array($dStr, $recordedDates)) {
        if ($user->hasApprovedLeaveForDate($date)) {
            $leaveDays++;
        } else {
            $alphaDays++; // hari tidak ada record sama sekali juga alpha
            $absentDays++;
        }
    }
}

  // Potongan = Rp 50.000 per hari alpha
$deduction = $alphaDays * 50000;

// Total gaji = gaji pokok - potongan
$total = $position->base_salary - $deduction;

        return [
            'base_salary'  => $position->base_salary,
            'alpha'        => $alphaDays,
            'deduction'    => $deduction,
            'total_salary' => max(0, $total),
            'work_days'    => $workDays,
            'present_days' => $presentDays,
            'late_days'    => $lateDays,
            'absent_days'  => $absentDays,
            'leave_days'   => $leaveDays,
        ];
    }
}
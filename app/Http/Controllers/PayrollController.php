<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\SalaryPosition;
use App\Models\User;
use App\Services\AttendanceAlphaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    private const STANDARD_WORK_DAYS = 22;
    private const ALPHA_DEDUCTION_RATE = 0.045;

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

    public function payrollIndex(Request $request)
    {
        $month = $request->integer('month', now()->subMonth()->month);
        $year = $request->integer('year', now()->subMonth()->year);
        $department = $request->input('department');

        $query = Payroll::with(['employee', 'salaryPosition'])
            ->where('month', $month)
            ->where('year', $year);

        if ($department) {
            $query->whereHas('employee', fn ($q) => $q->where('department', $department));
        }

        $payrolls = $query->get();
        $this->syncPayrollCollection($payrolls);

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
        $year = $request->integer('year');
        $selected = Carbon::create($year, $month, 1)->startOfMonth();
        $current = Carbon::now()->startOfMonth();

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

            if (!$position) {
                continue;
            }

            $calc = $this->calculatePayroll($user, $position, $month, $year);

            $payroll = Payroll::firstOrNew([
                'employee_id' => $user->id,
                'month'       => $month,
                'year'        => $year,
            ]);

            $payroll->salary_position_id = $position->id;
            $payroll->base_salary = $calc['base_salary'];
            $payroll->alpha = $calc['alpha'];
            $payroll->deduction = $calc['deduction'];
            $payroll->total_salary = $calc['total_salary'];
            $payroll->status = 'finalized';
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
        $this->syncPayrollSnapshot($payroll, $calc);

        return view('admin.payrolls.show', compact('payroll', 'calc'));
    }

    public function payrollPrint(Payroll $payroll)
    {
        $payroll->load(['employee', 'salaryPosition']);
        $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
        $this->syncPayrollSnapshot($payroll, $calc);

        return Pdf::loadView('admin.payrolls.print', compact('payroll', 'calc'))
            ->setPaper('a4')
            ->stream($this->payrollFilename($payroll));
    }

    public function payrollRecap(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $payrolls = Payroll::with(['employee', 'salaryPosition'])
            ->where('month', $month)
            ->where('year', $year)
            ->get();
        $this->syncPayrollCollection($payrolls);

        $totalExpense = $payrolls->sum('total_salary');
        $byDepartment = [];

        foreach ($payrolls as $payroll) {
            $dept = $payroll->employee->department ?? 'Tanpa Departemen';
            $byDepartment[$dept] = ($byDepartment[$dept] ?? 0) + $payroll->total_salary;
        }

        return view('admin.payrolls.recap', compact('payrolls', 'month', 'year', 'totalExpense', 'byDepartment'));
    }

    public function mySalary(Request $request)
    {
        $user = auth()->user();
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $payrolls = $user->payrolls()
            ->with('salaryPosition')
            ->when($request->filled('month'), fn ($q) => $q->where('month', $month))
            ->when($request->filled('year'), fn ($q) => $q->where('year', $year))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        $this->syncPayrollCollection($payrolls);

        return view('salary.index', compact('user', 'payrolls', 'month', 'year'));
    }

    public function mySalaryShow(Payroll $payroll)
    {
        if ($payroll->employee_id !== auth()->id()) {
            abort(403);
        }

        $payroll->load(['employee', 'salaryPosition']);
        $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
        $this->syncPayrollSnapshot($payroll, $calc);

        return view('salary.show', compact('payroll', 'calc'));
    }

    public function mySalaryPrint(Payroll $payroll)
    {
        if ($payroll->employee_id !== auth()->id()) {
            abort(403);
        }

        $payroll->load(['employee', 'salaryPosition']);
        $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
        $this->syncPayrollSnapshot($payroll, $calc);

        return Pdf::loadView('salary.print', compact('payroll', 'calc'))
            ->setPaper('a4')
            ->stream($this->payrollFilename($payroll));
    }

    private function calculatePayroll(User $user, ?SalaryPosition $position, int $month, int $year): array
    {
        if (!$position) {
            return [
                'base_salary'         => 0,
                'alpha'               => 0,
                'deduction'           => 0,
                'deduction_per_alpha' => 0,
                'total_salary'        => 0,
                'work_days'           => 0,
                'present_days'        => 0,
                'late_days'           => 0,
                'absent_days'         => 0,
                'leave_days'          => 0,
            ];
        }

        app(AttendanceAlphaService::class)->markMissingForMonth($month, $year, $user);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $leaveDays = $attendances->where('status', 'leave')->count();
        $alphaDays = $attendances->whereIn('status', ['alpha', 'absent'])->count();

        $deductionPerAlpha = (int) round($position->base_salary * self::ALPHA_DEDUCTION_RATE);
        $deduction = $alphaDays * $deductionPerAlpha;
        $total = $position->base_salary - $deduction;

        return [
            'base_salary'         => $position->base_salary,
            'alpha'               => $alphaDays,
            'deduction'           => $deduction,
            'deduction_per_alpha' => $deductionPerAlpha,
            'total_salary'        => max(0, $total),
            'work_days'           => self::STANDARD_WORK_DAYS,
            'present_days'        => $presentDays,
            'late_days'           => $lateDays,
            'absent_days'         => $alphaDays,
            'leave_days'          => $leaveDays,
        ];
    }

    private function syncPayrollCollection($payrolls): void
    {
        $payrolls->each(function (Payroll $payroll) {
            $payroll->loadMissing(['employee', 'salaryPosition']);
            $calc = $this->calculatePayroll($payroll->employee, $payroll->salaryPosition, $payroll->month, $payroll->year);
            $this->syncPayrollSnapshot($payroll, $calc);
        });
    }

    private function syncPayrollSnapshot(Payroll $payroll, array $calc): void
    {
        $payroll->fill([
            'base_salary'  => $calc['base_salary'],
            'alpha'        => $calc['alpha'],
            'deduction'    => $calc['deduction'],
            'total_salary' => $calc['total_salary'],
        ]);

        if ($payroll->isDirty(['base_salary', 'alpha', 'deduction', 'total_salary'])) {
            $payroll->save();
        }
    }

    private function payrollFilename(Payroll $payroll): string
    {
        $employee = str($payroll->employee->name)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '-')
            ->trim('-');

        $period = Carbon::create($payroll->year, $payroll->month, 1)->format('Y-m');

        return "slip-gaji-{$employee}-{$period}.pdf";
    }
}

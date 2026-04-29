<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayrollController;

// ── Public ──────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // User attendance
    Route::get('/attendance',         [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::post('/attendance/store',  [AttendanceController::class, 'store'])->name('attendance.store');

    // Leave requests
    Route::get('/leaves',             [AttendanceController::class, 'leavesIndex'])->name('leaves.index');
    Route::get('/leaves/create',      [AttendanceController::class, 'leavesCreate'])->name('leaves.create');
    Route::post('/leaves',            [AttendanceController::class, 'leavesStore'])->name('leaves.store');
    Route::delete('/leaves/{leave}',  [AttendanceController::class, 'leavesDestroy'])->name('leaves.destroy');

    // Profile
    Route::get('/profile',               [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/photo',         [ProfileController::class, 'editPhoto'])->name('profile.photo');
    Route::post('/profile/photo',        [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::get('/profile/password',      [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::post('/profile/password',     [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/details',       [ProfileController::class, 'editDetails'])->name('profile.details');
    Route::post('/profile/details',      [ProfileController::class, 'updateDetails'])->name('profile.details.update');
    Route::get('/profile/activity',      [ProfileController::class, 'activity'])->name('profile.activity');

    // My Salary (employee)
    Route::get('/my-salary',              [PayrollController::class, 'mySalary'])->name('salary.index');
    Route::get('/my-salary/{payroll}',    [PayrollController::class, 'mySalaryShow'])->name('salary.show');
    Route::get('/my-salary/{payroll}/print', [PayrollController::class, 'mySalaryPrint'])->name('salary.print');

    // ── Admin only ──────────────────────────────────────────────────────────
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard',       [AttendanceController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/recap',           [AttendanceController::class, 'adminRecap'])->name('recap');
        Route::get('/recap/export',    [AttendanceController::class, 'exportRecap'])->name('recap.export');

        // Employee management
        Route::get('/users',           [AttendanceController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/create',    [AttendanceController::class, 'usersCreate'])->name('users.create');
        Route::post('/users',          [AttendanceController::class, 'usersStore'])->name('users.store');
        Route::get('/users/{user}/edit',[AttendanceController::class, 'usersEdit'])->name('users.edit');
        Route::put('/users/{user}',    [AttendanceController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{user}', [AttendanceController::class, 'usersDestroy'])->name('users.destroy');

        // Leave approvals
        Route::get('/leaves',          [AttendanceController::class, 'adminLeaves'])->name('leaves');
        Route::post('/leaves/{leave}/approve', [AttendanceController::class, 'approveLeave'])->name('leaves.approve');
        Route::post('/leaves/{leave}/reject',  [AttendanceController::class, 'rejectLeave'])->name('leaves.reject');

        // Salary Positions
        Route::get('/salary-positions',              [PayrollController::class, 'salaryPositionsIndex'])->name('salary-positions.index');
        Route::get('/salary-positions/create',       [PayrollController::class, 'salaryPositionsCreate'])->name('salary-positions.create');
        Route::post('/salary-positions',             [PayrollController::class, 'salaryPositionsStore'])->name('salary-positions.store');
        Route::get('/salary-positions/{salaryPosition}/edit', [PayrollController::class, 'salaryPositionsEdit'])->name('salary-positions.edit');
        Route::put('/salary-positions/{salaryPosition}',      [PayrollController::class, 'salaryPositionsUpdate'])->name('salary-positions.update');
        Route::delete('/salary-positions/{salaryPosition}',   [PayrollController::class, 'salaryPositionsDestroy'])->name('salary-positions.destroy');

        // Payrolls
        Route::get('/payrolls',              [PayrollController::class, 'payrollIndex'])->name('payrolls.index');
        Route::post('/payrolls/generate',    [PayrollController::class, 'payrollGenerate'])->name('payrolls.generate');
        Route::get('/payrolls/{payroll}',    [PayrollController::class, 'payrollShow'])->name('payrolls.show');
        Route::get('/payrolls/{payroll}/print', [PayrollController::class, 'payrollPrint'])->name('payrolls.print');
        Route::get('/payrolls-recap',        [PayrollController::class, 'payrollRecap'])->name('payrolls.recap');
    });
});


# TODO: Fitur Manajemen Gaji

## Step 1: Database & Model ✅
- [x] Migration: add salary_position_id to users table
- [x] Migration: create salary_positions table
- [x] Migration: create payrolls table
- [x] Model: SalaryPosition
- [x] Model: Payroll
- [x] Update Model: User (relasi salaryPosition & payrolls)

## Step 2: Backend Logic ✅
- [x] Controller: PayrollController (semua fitur admin & karyawan)
- [x] Update AppServiceProvider: helper format Rupiah (@rupiah)
- [x] Update routes/web.php

## Step 3: Views - Admin Salary Positions ✅
- [x] admin/salary-positions/index.blade.php
- [x] admin/salary-positions/create.blade.php
- [x] admin/salary-positions/edit.blade.php

## Step 4: Views - Admin Payroll ✅
- [x] admin/payrolls/index.blade.php
- [x] admin/payrolls/show.blade.php
- [x] admin/payrolls/print.blade.php
- [x] admin/payrolls/recap.blade.php

## Step 5: Views - Employee Salary ✅
- [x] salary/index.blade.php
- [x] salary/show.blade.php
- [x] salary/print.blade.php

## Step 6: Layout & Integration ✅
- [x] Update layouts/app.blade.php (navigasi Salary admin + My Salary karyawan)

## Step 7: Testing ✅
- [x] Jalankan migrate — sukses
- [x] Verifikasi route dan view — sukses

## Catatan Implementasi
- Potongan gaji: Rp 50.000 per hari absent tanpa keterangan (sesuai permintaan user)
- Cuti/izin/sakit tidak dipotong gaji
- Format mata uang: Rupiah via @rupiah Blade directive
- PDF Export: halaman print-friendly dengan `window.print()`
- Grafik rekap: inline SVG bar chart per departemen


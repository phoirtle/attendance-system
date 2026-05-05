<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payrolls') || Schema::hasColumn('payrolls', 'alpha')) {
            return;
        }

        Schema::table('payrolls', function (Blueprint $table) {
            if (Schema::hasColumn('payrolls', 'attendance_allowance')) {
                $table->dropColumn('attendance_allowance');
            }
            $table->unsignedTinyInteger('alpha')->default(0)->after('base_salary');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('payrolls') || !Schema::hasColumn('payrolls', 'alpha')) {
            return;
        }

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('alpha');

            if (!Schema::hasColumn('payrolls', 'attendance_allowance')) {
                $table->unsignedBigInteger('attendance_allowance')->default(0)->after('base_salary');
            }
        });
    }
};

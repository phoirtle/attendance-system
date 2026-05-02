<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('attendance_allowance');
            $table->unsignedTinyInteger('alpha')->default(0)->after('base_salary');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('alpha');
            $table->unsignedBigInteger('attendance_allowance')->default(0)->after('base_salary');
        });
    }
};
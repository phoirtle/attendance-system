<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'late', 'absent', 'leave', 'alpha') NOT NULL DEFAULT 'present'");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("UPDATE attendances SET status = 'absent' WHERE status IN ('leave', 'alpha')");
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'late', 'absent') NOT NULL DEFAULT 'present'");
        }
    }
};

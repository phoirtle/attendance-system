<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Data Pribadi
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');

            // Data Kepegawaian
            $table->string('employee_id_number')->nullable()->unique()->after('gender'); // NIK
            $table->date('join_date')->nullable()->after('employee_id_number');
            $table->enum('employment_status', ['permanent', 'contract', 'intern'])
                  ->default('permanent')->after('join_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'address', 'birth_date', 'gender',
                'employee_id_number', 'join_date', 'employment_status',
            ]);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('salary_position_id')->nullable()->constrained('salary_positions')->nullOnDelete();
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedBigInteger('base_salary')->default(0);
            $table->unsignedBigInteger('attendance_allowance')->default(0);
            $table->unsignedBigInteger('deduction')->default(0);
            $table->unsignedBigInteger('total_salary')->default(0);
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};


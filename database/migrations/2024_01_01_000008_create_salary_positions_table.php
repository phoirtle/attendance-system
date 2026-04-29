<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_positions', function (Blueprint $table) {
            $table->id();
            $table->string('position_name');
            $table->string('department')->nullable();
            $table->unsignedBigInteger('base_salary')->default(0);
            $table->unsignedBigInteger('allowance')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_positions');
    }
};


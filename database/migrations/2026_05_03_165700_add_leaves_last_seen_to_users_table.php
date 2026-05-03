<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/2026_05_03_165700_add_leaves_last_seen_to_users_table.php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->timestamp('leaves_last_seen')->nullable();
        $table->timestamp('admin_leaves_last_seen')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['leaves_last_seen', 'admin_leaves_last_seen']);
    });
}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Enhances database performance with additional critical indexes.
     */
    public function up(): void
    {
        // 🛠 1. Users Table Optimization
        Schema::table('users', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('users', ['role_id'])) $table->index('role_id');
            if (!DB::getSchemaBuilder()->hasIndex('users', ['status'])) $table->index('status');
            if (!DB::getSchemaBuilder()->hasIndex('users', ['department_id'])) $table->index('department_id');
        });

        // 🛠 2. Academic Structure Optimization
        Schema::table('subjects', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('subjects', ['department_id'])) $table->index('department_id');
        });

        // 🛠 3. Results & Performance Optimization
        Schema::table('results', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('results', ['attempt_id'])) $table->index('attempt_id');
        });

        // 🛠 4. System & Notification Optimization
        Schema::table('notifications', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('notifications', ['read_at'])) $table->index('read_at');
        });

        Schema::table('login_histories', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('login_histories', ['login_at'])) $table->index('login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['department_id']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(['department_id']);
        });

        Schema::table('results', function (Blueprint $table) {
            $table->dropIndex(['attempt_id']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['read_at']);
        });

        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropIndex(['login_at']);
        });
    }
};

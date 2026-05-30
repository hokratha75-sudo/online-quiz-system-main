<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRoleToClassUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the role column doesn't exist before adding
        if (!Schema::hasColumn('class_user', 'role')) {
            Schema::table('class_user', function (Blueprint $table) {
                $table->enum('role', ['student', 'teacher'])->after('user_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('class_user', 'role')) {
            Schema::table('class_user', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
}
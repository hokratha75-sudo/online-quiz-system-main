<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role column to class_user table if it doesn't exist
        if (Schema::hasTable('class_user') && !Schema::hasColumn('class_user', 'role')) {
            Schema::table('class_user', function (Blueprint $table) {
                $table->string('role')->default('student')->after('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('class_user') && Schema::hasColumn('class_user', 'role')) {
            Schema::table('class_user', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};

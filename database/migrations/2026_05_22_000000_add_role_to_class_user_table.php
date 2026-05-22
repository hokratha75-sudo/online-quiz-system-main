<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('class_user') && !Schema::hasColumn('class_user', 'role')) {
            Schema::table('class_user', function (Blueprint $table) {
                $table->string('role')->default('student')->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('class_user') && Schema::hasColumn('class_user', 'role')) {
            Schema::table('class_user', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};

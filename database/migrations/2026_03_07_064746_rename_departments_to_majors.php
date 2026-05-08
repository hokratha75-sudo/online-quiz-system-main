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
        // Rename table
        Schema::rename('departments', 'majors');

        // Update majors table
        Schema::table('majors', function (Blueprint $table) {
            $table->string('code', 20)->after('name')->nullable();
            $table->text('description')->after('code')->nullable();
            $table->softDeletes();
        });

        // Update related tables only if they exist to keep migrations idempotent
        if (Schema::hasTable('class_models') && Schema::hasColumn('class_models', 'department_id')) {
            Schema::table('class_models', function (Blueprint $table) {
                $table->renameColumn('department_id', 'major_id');
            });
        }

        if (Schema::hasTable('subjects') && !Schema::hasColumn('subjects', 'major_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->foreignId('major_id')->nullable()->after('id')->constrained('majors')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'major_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->renameColumn('major_id', 'department_id');
            });
        }

        if (Schema::hasTable('class_models') && Schema::hasColumn('class_models', 'major_id')) {
            Schema::table('class_models', function (Blueprint $table) {
                $table->renameColumn('major_id', 'department_id');
            });
        }

        Schema::table('majors', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['code', 'description']);
        });

        Schema::rename('majors', 'departments');
    }
};

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
        // Rename only if departments exists and majors doesn't
        if (
            Schema::hasTable('departments') &&
            !Schema::hasTable('majors')
        ) {
            Schema::rename('departments', 'majors');
        }

        // Update majors table safely
        if (Schema::hasTable('majors')) {
            Schema::table('majors', function (Blueprint $table) {

                if (!Schema::hasColumn('majors', 'code')) {
                    $table->string('code', 20)
                          ->after('name')
                          ->nullable();
                }

                if (!Schema::hasColumn('majors', 'description')) {
                    $table->text('description')
                          ->after('code')
                          ->nullable();
                }

                if (!Schema::hasColumn('majors', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // Rename foreign key in class_models
        if (
            Schema::hasTable('class_models') &&
            Schema::hasColumn('class_models', 'department_id') &&
            !Schema::hasColumn('class_models', 'major_id')
        ) {
            Schema::table('class_models', function (Blueprint $table) {
                $table->renameColumn('department_id', 'major_id');
            });
        }

        // Add major_id to subjects
        if (
            Schema::hasTable('subjects') &&
            !Schema::hasColumn('subjects', 'major_id')
        ) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->foreignId('major_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('majors')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('subjects') &&
            Schema::hasColumn('subjects', 'major_id')
        ) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropForeign(['major_id']);
                $table->dropColumn('major_id');
            });
        }

        if (
            Schema::hasTable('class_models') &&
            Schema::hasColumn('class_models', 'major_id')
        ) {
            Schema::table('class_models', function (Blueprint $table) {
                $table->renameColumn('major_id', 'department_id');
            });
        }

        if (Schema::hasTable('majors')) {
            Schema::table('majors', function (Blueprint $table) {

                if (Schema::hasColumn('majors', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }

                $columns = [];

                if (Schema::hasColumn('majors', 'code')) {
                    $columns[] = 'code';
                }

                if (Schema::hasColumn('majors', 'description')) {
                    $columns[] = 'description';
                }

                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (
            Schema::hasTable('majors') &&
            !Schema::hasTable('departments')
        ) {
            Schema::rename('majors', 'departments');
        }
    }
};
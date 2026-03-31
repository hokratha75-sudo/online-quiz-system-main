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
        Schema::table('departments', function (Blueprint $table) {
            if (! Schema::hasColumn('departments', 'code')) {
                $table->string('code')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('departments', 'description')) {
                $table->text('description')->nullable()->after('department_name');
            }
        });

        if (! Schema::hasTable('majors')) {
            Schema::create('majors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
                $table->string('code')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('class_models')) {
            Schema::create('class_models', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
                $table->string('academic_year')->nullable();
                $table->boolean('show_in_enrollments')->default(false);
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('class_models', function (Blueprint $table) {
                if (! Schema::hasColumn('class_models', 'major_id')) {
                    $table->foreignId('major_id')->nullable()->after('name')->constrained('majors')->nullOnDelete();
                }

                if (! Schema::hasColumn('class_models', 'show_in_enrollments')) {
                    $table->boolean('show_in_enrollments')->default(false)->after('academic_year');
                }
            });
        }

        Schema::table('subjects', function (Blueprint $table) {
            if (! Schema::hasColumn('subjects', 'code')) {
                $table->string('code')->nullable()->after('id');
            }

            if (! Schema::hasColumn('subjects', 'major_id')) {
                $table->foreignId('major_id')->nullable()->after('department_id')->constrained('majors')->nullOnDelete();
            }

            if (! Schema::hasColumn('subjects', 'credits')) {
                $table->unsignedInteger('credits')->default(3)->after('subject_name');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'major_id')) {
                $table->foreignId('major_id')->nullable()->after('department_id')->constrained('majors')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'major_id')) {
                $table->dropConstrainedForeignId('major_id');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'major_id')) {
                $table->dropConstrainedForeignId('major_id');
            }

            if (Schema::hasColumn('subjects', 'credits')) {
                $table->dropColumn('credits');
            }

            if (Schema::hasColumn('subjects', 'code')) {
                $table->dropColumn('code');
            }
        });

        if (Schema::hasTable('class_models')) {
            Schema::table('class_models', function (Blueprint $table) {
                if (Schema::hasColumn('class_models', 'show_in_enrollments')) {
                    $table->dropColumn('show_in_enrollments');
                }

                if (Schema::hasColumn('class_models', 'major_id')) {
                    $table->dropConstrainedForeignId('major_id');
                }
            });
        }

        if (Schema::hasTable('majors')) {
            Schema::dropIfExists('majors');
        }

        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('departments', 'code')) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            }
        });
    }
};

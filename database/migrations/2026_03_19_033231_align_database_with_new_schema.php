<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Drop existing tables safely to rebuild them
        $tables = [
            'class_subject',
            'class_user',
            'student_answers',
            'results',
            'attempts',
            'answers',
            'questions',
            'quizzes',
            'class_models',

            'subjects', // 🔥 drop before majors
            'majors',

            'departments',
            'users',
            'roles',
            'activity_log',
            'cache',
            'cache_locks',
            'sessions',
            'jobs',
            'job_batches',
            'failed_jobs'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        // 2. Clear Types to ensure clean re-run (PostgreSQL only)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("DROP TYPE IF EXISTS user_status CASCADE");
            DB::statement("DROP TYPE IF EXISTS quiz_status CASCADE");
            DB::statement("DROP TYPE IF EXISTS question_difficulty CASCADE");
            DB::statement("DROP TYPE IF EXISTS attempt_status CASCADE");

            // 3. ENUM TYPES
            DB::statement("CREATE TYPE user_status AS ENUM ('active', 'inactive')");
            DB::statement("CREATE TYPE quiz_status AS ENUM ('draft', 'published')");
            DB::statement("CREATE TYPE question_difficulty AS ENUM ('easy', 'medium', 'hard')");
            DB::statement("CREATE TYPE attempt_status AS ENUM ('in_progress', 'completed', 'abandoned')");
        }

        // 4. ROLES TABLE
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name', 50)->unique();
        });

        // 5. DEPARTMENTS TABLE
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('department_name', 100);
            $table->string('code', 20)->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. USERS TABLE
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique()->nullable();
            $table->string('password_hash', 255);
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('profile_photo', 255)->nullable();

            if (DB::getDriverName() === 'pgsql') {
                $table->enum('status', ['active', 'inactive'])->default('active');
            } else {
                $table->string('status', 20)->default('active');
            }

            $table->timestamps();
        });

        // 7. SUBJECTS TABLE
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_name', 100);
            $table->string('code', 20)->nullable();
            $table->unsignedInteger('credits')->default(3);
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. QUIZZES TABLE
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            if (DB::getDriverName() === 'pgsql') {
                $table->enum('status', ['draft', 'published'])->default('draft');
            } else {
                $table->string('status', 20)->default('draft');
            }

            $table->timestamps();
        });

        // 9. QUESTIONS TABLE
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');

            if (DB::getDriverName() === 'pgsql') {
                $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            } else {
                $table->string('difficulty', 20)->default('medium');
            }

            $table->timestamps();
        });

        // 10. ANSWERS TABLE
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('answer_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // 11. ATTEMPTS TABLE
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');

            if (DB::getDriverName() === 'pgsql') {
                $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            } else {
                $table->string('status', 20)->default('in_progress');
            }

            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
        });

        // 12. RESULTS TABLE
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('attempts')->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'results',
            'attempts',
            'answers',
            'questions',
            'quizzes',
            'subjects',
            'users',
            'departments',
            'roles'
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("DROP TYPE IF EXISTS user_status CASCADE");
            DB::statement("DROP TYPE IF EXISTS quiz_status CASCADE");
            DB::statement("DROP TYPE IF EXISTS question_difficulty CASCADE");
            DB::statement("DROP TYPE IF EXISTS attempt_status CASCADE");
        }
    }
};

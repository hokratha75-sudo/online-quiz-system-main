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
        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'shuffle_questions')) {
                $table->boolean('shuffle_questions')->default(false)->after('status');
            }
            if (!Schema::hasColumn('quizzes', 'pass_percentage')) {
                $table->unsignedInteger('pass_percentage')->default(60)->after('shuffle_questions');
            }
        });

        Schema::table('questions', function (Blueprint $table) {
            // Rename question_text to content as per specification
            if (Schema::hasColumn('questions', 'question_text') && !Schema::hasColumn('questions', 'content')) {
                $table->renameColumn('question_text', 'content');
            }
            if (!Schema::hasColumn('questions', 'is_reusable')) {
                $table->boolean('is_reusable')->default(false)->after('points');
            }
        });

        // Sync Results with architect spec: (id, user_id, quiz_id, score, started_at, completed_at)
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('results', 'quiz_id')) {
                $table->foreignId('quiz_id')->nullable()->after('user_id')->constrained('quizzes')->onDelete('cascade');
            }
            if (!Schema::hasColumn('results', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('score');
            }
            if (!Schema::hasColumn('results', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['shuffle_questions', 'pass_percentage']);
        });

        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'content')) {
                $table->renameColumn('content', 'question_text');
            }
            $table->dropColumn('is_reusable');
        });

        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'quiz_id', 'started_at', 'completed_at']);
        });
    }
};

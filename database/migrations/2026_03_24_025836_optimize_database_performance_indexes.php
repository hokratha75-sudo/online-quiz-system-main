<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add indexes to optimize expensive queries for analytics, reports, and proctoring.
     */
    public function up(): void
    {
        // Results table optimization
        Schema::table('results', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('results', ['user_id'])) $table->index('user_id');
            if (!DB::getSchemaBuilder()->hasIndex('results', ['quiz_id'])) $table->index('quiz_id');
            if (!DB::getSchemaBuilder()->hasIndex('results', ['score'])) $table->index('score');
        });

        // Attempts table optimization
        Schema::table('attempts', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('attempts', ['user_id'])) $table->index('user_id');
            if (!DB::getSchemaBuilder()->hasIndex('attempts', ['quiz_id'])) $table->index('quiz_id');
            if (!DB::getSchemaBuilder()->hasIndex('attempts', ['status'])) $table->index('status');
        });

        // Quizzes table optimization
        Schema::table('quizzes', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('quizzes', ['subject_id'])) $table->index('subject_id');
            if (!DB::getSchemaBuilder()->hasIndex('quizzes', ['created_by'])) $table->index('created_by');
            if (!DB::getSchemaBuilder()->hasIndex('quizzes', ['status'])) $table->index('status');
        });

        // Questions & Answers
        Schema::table('questions', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('questions', ['quiz_id'])) $table->index('quiz_id');
        });

        Schema::table('answers', function (Blueprint $table) {
            if (!DB::getSchemaBuilder()->hasIndex('answers', ['question_id'])) $table->index('question_id');
            if (!DB::getSchemaBuilder()->hasIndex('answers', ['is_correct'])) $table->index('is_correct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['quiz_id']);
            $table->dropIndex(['score']);
        });

        Schema::table('attempts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['quiz_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['subject_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['status']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['quiz_id']);
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropIndex(['question_id']);
            $table->dropIndex(['is_correct']);
        });
    }
};

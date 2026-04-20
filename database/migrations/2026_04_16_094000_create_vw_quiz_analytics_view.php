<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define Database View for Analytics (PostgreSQL version)
        $viewSQL = "
            CREATE OR REPLACE VIEW vw_quiz_analytics AS
            SELECT 
                qz.id as quiz_id,
                qz.title as quiz_title,
                qz.subject_id,
                u.id as student_id,
                u.first_name,
                u.last_name,
                COUNT(res.id) as total_attempts,
                MAX(res.score) as highest_score,
                MIN(res.score) as lowest_score,
                ROUND(AVG(res.score), 2) as average_score
            FROM quizzes qz
            JOIN results res ON qz.id = res.quiz_id
            JOIN users u ON res.user_id = u.id
            GROUP BY qz.id, qz.title, qz.subject_id, u.id, u.first_name, u.last_name;
        ";

        DB::statement($viewSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_quiz_analytics;");
    }
};

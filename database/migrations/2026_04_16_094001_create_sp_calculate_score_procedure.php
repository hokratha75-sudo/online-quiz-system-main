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
        // Define Stored Procedure for Automatic Score Calculation (PostgreSQL version)
        $procedure = "
            CREATE OR REPLACE PROCEDURE sp_calculate_attempt_score(p_attempt_id BIGINT)
            LANGUAGE plpgsql
            AS $$
            DECLARE 
                v_total_score NUMERIC DEFAULT 0;
            BEGIN
                -- Calculate the score by summing the points_awarded for correct answers
                SELECT COALESCE(SUM(points_awarded), 0) INTO v_total_score
                FROM attempt_answers
                WHERE attempt_id = p_attempt_id AND is_correct = true;

                -- Update the total score for the attempt in results
                UPDATE results 
                SET score = v_total_score
                WHERE attempt_id = p_attempt_id;
            END;
            $$;
        ";
        
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_calculate_attempt_score;");
    }
};

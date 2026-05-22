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
        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'total_questions')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->unsignedInteger('total_questions')->default(0)->after('status');
            });

            // Populate existing quizzes with counts from questions
            // Use a single update statement for efficiency
            DB::statement(
                "UPDATE quizzes q SET total_questions = COALESCE((SELECT COUNT(*) FROM questions WHERE quiz_id = q.id), 0)"
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'total_questions')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('total_questions');
            });
        }
    }
};

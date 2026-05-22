<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ResultsTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();
        $quizzes = DB::table('quizzes')->get();

        if ($users->isEmpty() || $quizzes->isEmpty()) {

            $this->command->warn(
                'Skipping ResultsTableSeeder: users or quizzes table is empty.'
            );

            return;
        }

        $resultsInserted = 0;
        $attemptsInserted = 0;

        foreach ($users as $user) {

            // Generate 3-8 quiz results per user
            $randomQuizzes = $quizzes->random(
                min(rand(3, 8), $quizzes->count())
            );

            foreach ($randomQuizzes as $quiz) {

                // Skip duplicate result for same quiz/user
                $exists = DB::table('results')
                    ->where('user_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                // Random timestamps
                $startedAt = Carbon::now()
                    ->subDays(rand(1, 60))
                    ->subMinutes(rand(10, 120));

                $completedAt = (clone $startedAt)
                    ->addMinutes(rand(15, 90));

                // Random score
                $score = rand(45, 100);

                // Pass logic
                $passed = $score >= 50;

                // Published status
                $isPublished = rand(0, 100) > 10;

                // Violations
                $violations = rand(0, 100) > 85
                    ? rand(1, 3)
                    : 0;

                // Create attempt
                $attemptId = DB::table('attempts')->insertGetId([
                    'user_id'      => $user->id,
                    'quiz_id'      => $quiz->id,
                    'status'       => 'completed',
                    'started_at'   => $startedAt,
                    'completed_at' => $completedAt,
                    'created_at'   => $startedAt,
                    'updated_at'   => $completedAt,
                ]);

                $attemptsInserted++;

                // Create result
                DB::table('results')->insert([
                    'user_id'          => $user->id,
                    'quiz_id'          => $quiz->id,
                    'attempt_id'       => $attemptId,

                    'score'            => $score,
                    'passed'           => $passed,
                    'is_published'     => $isPublished,

                    'violations_count' => $violations,

                    'started_at'       => $startedAt,
                    'completed_at'     => $completedAt,

                    'created_at'       => $completedAt,
                    'updated_at'       => $completedAt,
                ]);

                $resultsInserted++;
            }
        }

        $this->command->info("
========================================
 Results Seeder Completed Successfully
========================================
 Attempts Inserted : {$attemptsInserted}
 Results Inserted  : {$resultsInserted}
========================================
");
    }
}
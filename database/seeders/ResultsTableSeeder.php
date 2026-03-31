<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultsTableSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->first();
        /** @var \App\Models\Quiz $quiz */
        $quiz = DB::table('quizzes')->first();

        if ($user instanceof \stdClass && $quiz instanceof \stdClass) {
            $attempt1 = DB::table('attempts')->insertGetId([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'status' => 'completed',
                'started_at' => now()->subHours(2),
                'completed_at' => now()->subHours(1),
            ]);

            DB::table('results')->insert([
                [
                    'user_id' => $user->id,
                    'quiz_id' => $quiz->id,
                    'attempt_id' => $attempt1,
                    'score' => 85,
                    'started_at' => now()->subHours(2),
                    'completed_at' => now()->subHours(1),
                    'created_at' => now()->subHours(1),
                ],
            ]);
        }
    }
}

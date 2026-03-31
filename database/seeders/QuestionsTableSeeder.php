<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    public function run()
    {
        $quiz = DB::table('quizzes')->first();
        DB::table('questions')->insert([
            [
                'quiz_id' => $quiz->id,
                'content' => 'What is 2 + 2?',
                'type' => 'single_choice',
                'points' => 10,
                'created_at' => now(),
            ],
            [
                'quiz_id' => $quiz->id,
                'content' => 'What is 5 x 3?',
                'type' => 'single_choice',
                'points' => 15,
                'created_at' => now(),
            ],
        ]);
    }
}
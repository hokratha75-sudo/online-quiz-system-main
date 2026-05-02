<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnswersTableSeeder extends Seeder
{
    public function run()
    {
        $questionColumn = Schema::hasColumn('questions', 'question_text') ? 'question_text' : 'content';
        $q1 = DB::table('questions')->where($questionColumn, 'What is 2 + 2?')->first();
        $q2 = DB::table('questions')->where($questionColumn, 'What is 5 x 3?')->first();

        DB::table('answers')->insert([
            // Question 1: 2+2
            ['question_id' => $q1->id, 'answer_text' => '3', 'is_correct' => false],
            ['question_id' => $q1->id, 'answer_text' => '4', 'is_correct' => true],
            ['question_id' => $q1->id, 'answer_text' => '5', 'is_correct' => false],
            ['question_id' => $q1->id, 'answer_text' => '6', 'is_correct' => false],

            // Question 2: 5x3
            ['question_id' => $q2->id, 'answer_text' => '8', 'is_correct' => false],
            ['question_id' => $q2->id, 'answer_text' => '15', 'is_correct' => true],
            ['question_id' => $q2->id, 'answer_text' => '10', 'is_correct' => false],
            ['question_id' => $q2->id, 'answer_text' => '20', 'is_correct' => false],
        ]);
    }
}

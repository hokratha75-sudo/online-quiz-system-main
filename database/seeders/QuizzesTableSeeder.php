<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class QuizzesTableSeeder extends Seeder
{
    public function run()
    {
        $subject = DB::table('subjects')->first();
        $admin = DB::table('users')->where('username', 'admin')->first();

        DB::table('quizzes')->insert([
            [
                'title' => 'Basic Math Quiz',
                'subject_id' => $subject->id,
                'created_by' => $admin->id,
                'time_limit' => 30,
                'shuffle_questions' => true,
                'pass_percentage' => 70,
                'status' => 'published',
                'created_at' => now(),
            ],
        ]);
    }
}
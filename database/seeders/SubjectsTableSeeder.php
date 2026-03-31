<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SubjectsTableSeeder extends Seeder
{
    public function run()
    {
        $admin = DB::table('users')->where('username', 'admin')->first();
        DB::table('subjects')->insert([
            [
                'subject_name' => 'General Mathematics',
                'created_by' => $admin->id,
                'created_at' => now(),
            ],
        ]);
    }
}